/**
 * Project Manager
 * 役割: プロジェクト内のタスク状況やファイル内容を管理・提供する
 */

import fs from 'fs/promises';
import path from 'path';

export class ProjectManager {
  private static taskListPath = '/Users/takamatsukei/.gemini/antigravity/brain/25ab6115-76ec-47f6-a81f-8acf2aebe005/task.md';

  /**
   * task.mdから現在のタスク進捗を取得する
   */
  static async getProgressSummary(): Promise<string> {
    try {
      const content = await fs.readFile(this.taskListPath, 'utf-8');
      const lines = content.split('\n');
      
      const todo = lines.filter((l: string) => l.includes('- [ ]')).length;
      const inProgress = lines.filter((l: string) => l.includes('- [/]')).length;
      const done = lines.filter((l: string) => l.includes('- [x]')).length;

      let summary = `📊 **現在の進捗状況**\n`;
      summary += `✅ 完了: ${done}\n`;
      summary += `🚧 進行中: ${inProgress}\n`;
      summary += `📝 未完了: ${todo}\n\n`;
      
      summary += `**進行中のタスク:**\n`;
      summary += lines.filter((l: string) => l.includes('- [/]')).map((l: string) => l.replace('- [/]', '•').trim()).join('\n') || 'なし';

      return summary;
    } catch (error) {
      console.error('Error reading task.md:', error);
      return '進捗情報を取得できませんでした。';
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
   * 開発指示を記録する
   */
  static async addInstruction(text: string): Promise<void> {
    const instructionPath = path.resolve(process.cwd(), 'docs/instructions.md');
    const timestamp = new Date().toLocaleString('ja-JP');
    const entry = `## [${timestamp}]\n${text}\n\n`;
    
    try {
      await fs.appendFile(instructionPath, entry, 'utf-8');
    } catch (error) {
      console.error('Error logging instruction:', error);
      throw error;
    }
  }
}
