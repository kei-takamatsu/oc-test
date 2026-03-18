import express from 'express';
import cors from 'cors';
import { db } from '../db/database.ts';

const app = express();
const PORT = process.env.API_PORT || 3001;

app.use(cors());
app.use(express.json());

// 稼働状況確認API
app.get('/api/health', (req, res) => {
  res.json({ status: 'ok', message: 'Antigravity API is running' });
});

// タスク一覧取得API
app.get('/api/tasks', async (req, res) => {
  try {
    const tasks = await db.all('SELECT * FROM tasks ORDER BY created_at DESC');
    res.json(tasks);
  } catch (error: any) {
    res.status(500).json({ error: error.message });
  }
});

// 指示ログ取得API
app.get('/api/instructions', async (req, res) => {
  try {
    const instructions = await db.all('SELECT * FROM instructions ORDER BY created_at DESC LIMIT 50');
    res.json(instructions);
  } catch (error: any) {
    res.status(500).json({ error: error.message });
  }
});

export const startApiServer = () => {
  app.listen(PORT, () => {
    console.log(`API Server is running on http://localhost:${PORT}`);
  });
};
