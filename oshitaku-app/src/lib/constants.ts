import { Zap, Droplets, PenTool as Tooth, Shirt, Bath, Book, Backpack, LogOut, Sunset, Sun, Calendar as CalendarIcon, CheckCircle2, Circle, Settings } from 'lucide-react';

export type TaskTime = 'morning' | 'evening';

export interface Task {
  id: string;
  text: string;
  icon: string;
  time: TaskTime;
  done: boolean;
}

export const INITIAL_TASKS: Task[] = [
  { id: 'm1', text: '顔を洗う', icon: '💦', time: 'morning', done: false },
  { id: 'm2', text: '歯みがき', icon: '🪥', time: 'morning', done: false },
  { id: 'm3', text: '着替え', icon: '👕', time: 'morning', done: false },
  { id: 'm4', text: 'トイレ', icon: '🚽', time: 'morning', done: false },
  { id: 'e1', text: '手洗い・うがい', icon: '🧼', time: 'evening', done: false },
  { id: 'e2', text: 'お風呂', icon: '🛁', time: 'evening', done: false },
  { id: 'e3', text: '歯みがき', icon: '🪥', time: 'evening', done: false },
  { id: 'e4', text: 'トイレ', icon: '🚽', time: 'evening', done: false },
  { id: 'e5', text: '宿題', icon: '📚', time: 'evening', done: false },
  { id: 'e6', text: '明日の用意', icon: '🎒', time: 'evening', done: false },
];

export const getDisplayDate = () => {
  const now = new Date();
  const days = ['日', '月', '火', '水', '木', '金', '土'];
  return `${now.getMonth() + 1}月 ${now.getDate()}日 (${days[now.getDay()]})`;
};

export const getTodayKey = () => {
  const now = new Date();
  return `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}-${String(now.getDate()).padStart(2, '0')}`;
};

export const getInitialTab = (): TaskTime => {
  const hour = new Date().getHours();
  return (hour >= 5 && hour < 15) ? 'morning' : 'evening';
};
