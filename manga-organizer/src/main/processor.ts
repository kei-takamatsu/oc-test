import path from 'node:path';
import fs from 'node:fs/promises';
import os from 'node:os';
import sharp from 'sharp';
import AdmZip from 'adm-zip';
import * as unrar from 'node-unrar-js';

export interface ProcessOptions {
  trimSensitivity: number; // 0-100
  enableScaleTrimming: boolean;
  splitSpreads: boolean;
  outputZipName?: string;
}

export interface ProcessStatus {
  total: number;
  current: number;
  message: string;
}

export class MangaProcessor {
  private tempDir: string;

  constructor() {
    this.tempDir = path.join(os.tmpdir(), 'manga-organizer-' + Date.now());
  }

  private async ensureTempDir() {
    await fs.mkdir(this.tempDir, { recursive: true });
  }

  private async cleanup() {
    try {
        await fs.rm(this.tempDir, { recursive: true, force: true });
    } catch (e) {
        console.error('Cleanup error:', e);
    }
  }

  /**
   * Main entry point for processing an archive
   */
  async processArchive(filePath: string, options: ProcessOptions, onProgress: (status: ProcessStatus) => void) {
    await this.ensureTempDir();
    const extractPath = path.join(this.tempDir, 'extracted');
    const outputPath = path.join(this.tempDir, 'processed');
    await fs.mkdir(extractPath, { recursive: true });
    await fs.mkdir(outputPath, { recursive: true });

    onProgress({ total: 100, current: 5, message: 'アーカイブを解凍中...' });

    // 1. Extraction
    const ext = path.extname(filePath).toLowerCase();
    if (ext === '.zip' || ext === '.cbz') {
      const zip = new AdmZip(filePath);
      zip.extractAllTo(extractPath, true);
    } else if (ext === '.rar' || ext === '.cbr') {
      await this.extractRar(filePath, extractPath);
    } else {
      throw new Error(`サポートされていない形式です: ${ext}`);
    }

    // List all images
    const files = await this.getAllFiles(extractPath);
    const imageFiles = files.filter(f => /\.(jpg|jpeg|png|webp)$/i.test(f));
    const total = imageFiles.length;

    if (total === 0) {
      throw new Error('アーカイブ内に画像が見つかりませんでした。');
    }

    // Sort files to keep order
    imageFiles.sort((a, b) => a.localeCompare(b, undefined, { numeric: true, sensitivity: 'base' }));

    // 2. Pre-analysis for "Scale Awareness"
    onProgress({ total, current: 0, message: '作品のスケールを解析中...' });
    const bookScale = await this.analyzeBookScale(imageFiles);

    // 3. Image Processing
    for (let i = 0; i < total; i++) {
      const file = imageFiles[i];
      const relativePath = path.relative(extractPath, file);
      const targetPath = path.join(outputPath, relativePath);
      await fs.mkdir(path.dirname(targetPath), { recursive: true });

      onProgress({ total, current: i + 1, message: `${path.basename(file)} を処理中...` });
      
      try {
          const processedFiles = await this.processImage(file, outputPath, extractPath, options, bookScale);
          // If the original processArchive loop needs to know about the new files for some reason, we can track them.
          // For now, since addLocalFolder is used later, we just need to ensure they are created in the right place.
          console.log(`Processed ${file} into ${processedFiles.length} files.`);
      } catch (err) {
          console.error(`Error processing ${file}:`, err);
          const relativePath = path.relative(extractPath, file);
          const targetPath = path.join(outputPath, relativePath);
          await fs.mkdir(path.dirname(targetPath), { recursive: true });
          await fs.copyFile(file, targetPath);
      }
    }

    // 4. Re-compression
    onProgress({ total: 100, current: 95, message: 'ZIPとして再圧縮中...' });
    const finalZipName = options.outputZipName || `${path.basename(filePath, ext)}_organized_zip.zip`;
    const finalZipPath = path.join(path.dirname(filePath), finalZipName);
    
    const outputZip = new AdmZip();
    outputZip.addLocalFolder(outputPath);
    outputZip.writeZip(finalZipPath);

    await this.cleanup();
    onProgress({ total: 100, current: 100, message: '完了しました！' });
    
    return finalZipPath;
  }

  private async extractRar(filePath: string, targetPath: string) {
    const data = await fs.readFile(filePath);
    // Use any as a temporary workaround for type mismatches in different versions
    const extractor = await unrar.createExtractorFromData({ data: data as any });
    const list = extractor.getFileList();
    const headers = [...list.fileHeaders];
    const extraction = extractor.extract({ files: headers.map(h => h.name) });
    
    for (const file of extraction.files) {
        if (file.fileHeader.flags.directory) continue;
        const fullPath = path.join(targetPath, file.fileHeader.name);
        await fs.mkdir(path.dirname(fullPath), { recursive: true });
        await fs.writeFile(fullPath, file.extraction!);
    }
  }

  private async getAllFiles(dir: string): Promise<string[]> {
    const entries = await fs.readdir(dir, { withFileTypes: true });
    const files = await Promise.all(entries.map((res) => {
      const resPath = path.resolve(dir, res.name);
      return res.isDirectory() ? this.getAllFiles(resPath) : [resPath];
    }));
    return (Array.prototype.concat(...files) as string[]).flat();
  }

  private async analyzeBookScale(imagePaths: string[]) {
    const sampleSize = Math.min(10, imagePaths.length);
    let totalRatio = 0;
    let count = 0;

    for (let i = 0; i < sampleSize; i++) {
        try {
            const meta = await sharp(imagePaths[i]).metadata();
            if (meta.width && meta.height) {
                const ratio = meta.width / meta.height;
                if (ratio < 0.9) { // Avoid spreads for scale analysis
                   totalRatio += ratio;
                   count++;
                }
            }
        } catch (e) { /* ignore */ }
    }
    return count > 0 ? totalRatio / count : 0.707;
  }

  private async processImage(
    inputPath: string, 
    outputBaseDir: string, 
    extractPath: string,
    options: ProcessOptions, 
    targetAspect: number
  ): Promise<string[]> {
    // 1. First pass: Trim to find the actual content area
    // Use the threshold from options
    const trimmedBufferObj = await sharp(inputPath)
      .trim({ threshold: options.trimSensitivity })
      .toBuffer({ resolveWithObject: true });

    const { data, info: trimmedMeta } = trimmedBufferObj;
    
    // 2. Decide if it's a spread based on TRIMMED dimensions
    const isSpread = trimmedMeta.width > trimmedMeta.height;

    const relativePath = path.relative(extractPath, inputPath);
    const fileExt = path.extname(inputPath);
    const fileNameBase = path.basename(inputPath, fileExt);
    const fileSubDir = path.dirname(relativePath);
    
    const results: string[] = [];

    const saveBuffer = async (buffer: Buffer, suffix: string = '') => {
        const targetPath = path.join(outputBaseDir, fileSubDir, `${fileNameBase}${suffix}${fileExt}`);
        await fs.mkdir(path.dirname(targetPath), { recursive: true });
        await fs.writeFile(targetPath, buffer);
        results.push(targetPath);
    };

    if (isSpread && options.splitSpreads) {
        // Split the trimmed content Right-to-Left
        const halfWidth = Math.floor(trimmedMeta.width / 2);
        
        // 1. Right side (Page 1)
        const rightSide = await sharp(data).extract({
            left: halfWidth,
            top: 0,
            width: trimmedMeta.width - halfWidth,
            height: trimmedMeta.height
        }).toBuffer();
        await saveBuffer(rightSide, '_1');

        // 2. Left side (Page 2)
        const leftSide = await sharp(data).extract({
            left: 0,
            top: 0,
            width: halfWidth,
            height: trimmedMeta.height
        }).toBuffer();
        await saveBuffer(leftSide, '_2');
    } else {
        // Just save the trimmed image
        await saveBuffer(data);
    }

    return results;
  }
}
