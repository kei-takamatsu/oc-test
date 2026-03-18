/**
 * Project Manager
 * 役割: プロジェクト内のタスク状況やファイル内容を管理・提供する
 */

import fs from 'fs/promises';
import path from 'path';
import { db } from '../db/database.ts';

export interface PushResult {
  output: string;
  repoUrl: string;
  pagesUrl: string;
}

export class ProjectManager {
  /**
   * DBから現在のタスク進捗を取得する
   */
  static async getProgressSummary(): Promise<string> {
    try {
      const tasks = await db.all<any>('SELECT * FROM tasks ORDER BY created_at DESC');
      
      const todo = tasks.filter(t => t.status === 'todo').length;
      const inProgress = tasks.filter(t => t.status === 'in_progress').length;
      const done = tasks.filter(t => t.status === 'done').length;

      let summary = `📊 **現在の進捗状況**\n`;
      summary += `✅ 完了: ${done}\n`;
      summary += `🚧 進行中: ${inProgress}\n`;
      summary += `📝 未完了: ${todo}\n\n`;
      
      const inProgressTasks = tasks.filter(t => t.status === 'in_progress');
      summary += `**進行中のタスク:**\n`;
      summary += inProgressTasks.length > 0
        ? inProgressTasks.map(t => `• ${t.description}`).join('\n')
        : 'なし';

      return summary;
    } catch (error) {
      console.error('Error reading tasks from DB:', error);
      return '進捗情報を取得できませんでした。';
    }
  }

  /**
   * DBに新しいタスクを追加する
   */
  static async addTask(description: string): Promise<void> {
    try {
      await db.run('INSERT INTO tasks (description, status) VALUES (?, ?)', [description, 'todo']);
    } catch (error) {
      console.error('Error adding task:', error);
    }
  }

  /**
   * 指定されたファイルの内容を読み取る
   */
  static async readFile(filePath: string): Promise<string> {
    try {
      const fullPath = path.resolve(process.cwd(), filePath);
      if (!fullPath.startsWith(process.cwd())) {
        return 'アクセス権限がありません。';
      }

      const content = await fs.readFile(fullPath, 'utf-8');
      return content.length > 1500 ? content.substring(0, 1500) + '\n... (省略)' : content;
    } catch (error) {
      console.error('Error reading file:', error);
      return 'ファイルの読み込みに失敗しました。';
    }
  }

  /**
   * 開発指示をデータベースとファイルの両方に記録する
   */
  static async addInstruction(text: string): Promise<void> {
    // 1. レガシーなMarkdownへの記録 (後方互換性のため残す)
    const instructionPath = path.resolve(process.cwd(), 'docs/instructions.md');
    const timestamp = new Date().toLocaleString('ja-JP');
    const entry = `## [${timestamp}]\n${text}\n\n`;
    
    try {
      await fs.appendFile(instructionPath, entry, 'utf-8');
    } catch (error) {
      console.error('Error logging instruction to file:', error);
    }

    // 2. 新しいSQLiteDBへの記録
    try {
      await db.run('INSERT INTO instructions (content) VALUES (?)', [text]);
    } catch (error) {
      console.error('Error logging instruction to DB:', error);
      throw error;
    }
  }

  /**
   * Git Pushを実行し、関連URLを返す
   */
  static async runGitPush(): Promise<PushResult> {
    const { exec } = await import('child_process');
    const { promisify } = await import('util');
    const execPromise = promisify(exec);

    try {
      const { stdout: repoRaw } = await execPromise('git remote get-url origin');
      const repoUrl = repoRaw.trim().replace(/\.git$/, '');
      
      // GitHub Pages URLの推測
      let pagesUrl = '';
      const match = repoUrl.match(/github\.com\/([^/]+)\/([^/]+)/);
      if (match) {
        let baseUrl = `https://${match[1]}.github.io/${match[2]}/`;
        
        // 直近で変更されたディレクトリ（clock-sampleなど）を特定してURLに付与
        try {
          const items = await fs.readdir(process.cwd(), { withFileTypes: true });
          const dirs = items
            .filter(item => item.isDirectory() && !item.name.startsWith('.') && !['node_modules', 'src', 'docs', 'scripts'].includes(item.name));
          
          if (dirs.length > 0) {
            // 最も新しいディレクトリを取得 (暫定的に最初の有効なディレクトリ)
            pagesUrl = `${baseUrl}${dirs[0].name}/`;
          } else {
            pagesUrl = baseUrl;
          }
        } catch (e) {
          pagesUrl = baseUrl;
        }
      }

      const { stdout, stderr } = await execPromise('git push origin main');
      return {
        output: stdout || stderr || 'Push successful',
        repoUrl,
        pagesUrl
      };
    } catch (error: any) {
      console.error('Git push error:', error);
      throw new Error(`Git push failed: ${error.message}`);
    }
  }
}
