import sqlite3 from 'sqlite3';
import path from 'path';

// Promisify sqlite3 for easier async/await usage
export class Database {
  private db: sqlite3.Database;

  constructor(dbFilePath: string) {
    this.db = new sqlite3.Database(dbFilePath, (err) => {
      if (err) {
        console.error('Error connecting to database', err);
      } else {
        console.log('Connected to SQLite database.');
      }
    });

    this.init();
  }

  private init() {
    this.db.serialize(() => {
      // 開発指示 (Instructions) を保存するテーブル
      this.db.run(`
        CREATE TABLE IF NOT EXISTS instructions (
          id INTEGER PRIMARY KEY AUTOINCREMENT,
          content TEXT NOT NULL,
          created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
      `);

      // タスク (Tasks) を保存するテーブル
      this.db.run(`
        CREATE TABLE IF NOT EXISTS tasks (
          id INTEGER PRIMARY KEY AUTOINCREMENT,
          description TEXT NOT NULL,
          status TEXT CHECK(status IN ('todo', 'in_progress', 'done')) DEFAULT 'todo',
          created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
          updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
      `);
    });
  }

  public run(sql: string, params: any[] = []): Promise<void> {
    return new Promise((resolve, reject) => {
      this.db.run(sql, params, function(err) {
        if (err) reject(err);
        else resolve();
      });
    });
  }

  public all<T>(sql: string, params: any[] = []): Promise<T[]> {
    return new Promise((resolve, reject) => {
      this.db.all(sql, params, (err, rows: T[]) => {
        if (err) reject(err);
        else resolve(rows);
      });
    });
  }
}

// データベースファイルのパスを設定
const dbPath = path.resolve(process.cwd(), 'database.sqlite');
export const db = new Database(dbPath);
