import React from 'react';
import { motion } from 'framer-motion';

interface CalendarProps {
  history: Record<string, { morning: boolean; evening: boolean }>;
  onDateClick?: (dateKey: string) => void;
}

export const Calendar: React.FC<CalendarProps> = ({ history, onDateClick }) => {
  const [currentDate] = React.useState(new Date());

  const daysInMonth = (year: number, month: number) => new Date(year, month + 1, 0).getDate();
  const firstDayOfMonth = (year: number, month: number) => new Date(year, month, 1).getDay();

  const year = currentDate.getFullYear();
  const month = currentDate.getMonth();
  const days = daysInMonth(year, month);
  const offset = firstDayOfMonth(year, month);

  const weekDays = ['日', '月', '火', '水', '木', '金', '土'];

  // 統計データの計算
  const currentMonthDays = Object.entries(history).filter(([key]) => {
    const [y, m] = key.split('-');
    return parseInt(y) === year && parseInt(m) === month + 1;
  });

  // eslint-disable-next-line @typescript-eslint/no-unused-vars
  const perfectDaysCount = currentMonthDays.filter(([_, status]) => status.morning && status.evening).length;
  // 仮の計算：朝夜の合計の割合
  const totalTasksInMonth = days * 2; // シンプルに朝夜1回ずつと仮定
  // eslint-disable-next-line @typescript-eslint/no-unused-vars
  const completedTasksInMonth = currentMonthDays.reduce((acc, [_, status]) => {
    return acc + (status.morning ? 1 : 0) + (status.evening ? 1 : 0);
  }, 0);
  const averagePercent = totalTasksInMonth > 0 ? Math.round((completedTasksInMonth / totalTasksInMonth) * 100) : 0;

  // グラフ用データ（当月の日付分）
  const graphData = Array.from({ length: days }).map((_, i) => {
    const d = i + 1;
    const key = `${year}-${String(month + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
    const status = history[key];
    let score = 0;
    if (status) {
      if (status.morning) score += 50;
      if (status.evening) score += 50;
    }
    return { date: d, score };
  });

  return (
    <div className="space-y-6">
      <div className="flex justify-center items-center px-4 relative">
        <h2 className="text-2xl font-black text-purple-600 tracking-tight">がんばりひょう</h2>
      </div>

      <div className="bg-white rounded-[2.5rem] p-6 shadow-sm border border-slate-100 flex divide-x divide-slate-100">

        <div className="flex-1 text-center">
          <div className="text-xs font-bold text-slate-400 mb-1">へいきん</div>
          <div className="text-4xl font-black text-purple-600">{averagePercent}%</div>
        </div>
        <div className="flex-1 text-center">
          <div className="text-xs font-bold text-slate-400 mb-1">パーフェクト</div>
          <div className="text-4xl font-black text-orange-400">
            {perfectDaysCount} <span className="text-sm font-bold text-slate-600">にち</span>
          </div>
        </div>
      </div>

      <div className="bg-white rounded-[2.5rem] p-6 shadow-sm border border-slate-100">
        <div className="flex items-center gap-2 mb-6">
          <div className="text-purple-600">
            {/* 簡易的なグラフアイコン */}
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
              <path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/>
            </svg>
          </div>
          <h3 className="font-bold text-purple-600">つきべつの グラフ</h3>
        </div>
        
        <div className="flex gap-2 overflow-x-auto pb-4 scrollbar-hide snap-x">
          {graphData.map(data => (
            <div key={data.date} className="flex flex-col items-center gap-2 flex-shrink-0 snap-start">
              <div className="h-24 w-12 bg-slate-50 rounded-xl relative overflow-hidden">
                <motion.div 
                  initial={{ height: 0 }}
                  animate={{ height: `${data.score}%` }}
                  className="absolute bottom-0 left-0 right-0 bg-purple-400 rounded-b-xl"
                />
              </div>
              <span className="text-xs font-bold text-slate-400">{data.date}</span>
            </div>
          ))}
        </div>
      </div>

      <div className="bg-white rounded-[2.5rem] p-6 shadow-sm border border-slate-100">
        <div className="flex items-center gap-2 mb-6">
          <div className="text-green-500">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
              <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
          </div>
          <h3 className="font-bold text-green-500">カレンダー <span className="text-sm">（タップして なおせるよ）</span></h3>
        </div>

        <div className="grid grid-cols-7 gap-2 mb-2">
          {weekDays.map(d => (
            <div key={d} className="text-center text-[10px] font-bold text-slate-400 py-1">
              {d === '日' ? 'にち' : d === '月' ? 'げつ' : d === '火' ? 'か' : d === '水' ? 'すい' : d === '木' ? 'もく' : d === '金' ? 'きん' : 'ど'}
            </div>
          ))}
        </div>

        <div className="grid grid-cols-7 gap-2">
        {Array.from({ length: offset }).map((_, i) => (
          <div key={`empty-${i}`} />
        ))}
        {Array.from({ length: days }).map((_, i) => {
          const d = i + 1;
          const key = `${year}-${String(month + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
          const dayStatus = history[key];
          
          return (
            <div 
              key={d} 
              onClick={() => onDateClick?.(key)}
              className={`aspect-square flex flex-col items-center justify-center relative rounded-2xl border cursor-pointer hover:bg-slate-50 transition-colors ${d === currentDate.getDate() ? 'border-purple-200' : 'border-slate-50 bg-white'}`}
            >
              <span className={`text-xs font-bold z-10 ${dayStatus ? 'text-slate-800' : 'text-slate-400'}`}>
                {d}
              </span>
              {dayStatus && (
                <div className="absolute inset-0 flex items-center justify-center pointer-events-none">
                  {(dayStatus.morning && dayStatus.evening) && (
                    <div className="absolute inset-0 bg-purple-50 rounded-2xl -z-0 border border-purple-100" />
                  )}
                  <div className="flex gap-1 mt-6 z-10">
                    {dayStatus.morning && <div className="w-1.5 h-1.5 rounded-full bg-morning" />}
                    {dayStatus.evening && <div className="w-1.5 h-1.5 rounded-full bg-evening" />}
                  </div>
                </div>
              )}
            </div>
          );
        })}
        </div>
      </div>
    </div>
  );
};
