import React, { useState } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { X, Plus, Minus } from 'lucide-react';

import type { Transaction } from '../lib/constants';

interface CalendarProps {
  history: Record<string, { morning: boolean; evening: boolean }>;
  onDateClick?: (dateKey: string) => void;
  allTransactions?: Transaction[];
  initialBalance?: number;
  totalBalance?: number;
}

export const Calendar: React.FC<CalendarProps> = ({ history, onDateClick, allTransactions = [], initialBalance = 0, totalBalance = 0 }) => {
  const [currentDate, setCurrentDate] = React.useState(new Date());
  const [showTransactionModal, setShowTransactionModal] = useState(false);
  const graphContainerRef = React.useRef<HTMLDivElement>(null);

  const prevMonth = () => {
    setCurrentDate(new Date(currentDate.getFullYear(), currentDate.getMonth() - 1, 1));
  };

  const nextMonth = () => {
    setCurrentDate(new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 1));
  };

  const daysInMonth = (year: number, month: number) => new Date(year, month + 1, 0).getDate();
  const firstDayOfMonth = (year: number, month: number) => new Date(year, month, 1).getDay();

  const year = currentDate.getFullYear();
  const month = currentDate.getMonth();
  const days = daysInMonth(year, month);
  const offset = firstDayOfMonth(year, month);

  React.useEffect(() => {
    if (graphContainerRef.current) {
      const today = new Date();
      if (today.getFullYear() === year && today.getMonth() === month) {
        // Center the scroll roughly on the current day
        const currentDay = today.getDate();
        const scrollPosition = Math.max(0, (currentDay - 3) * 56);
        // Timeout ensures the elements are rendered and measurements are correct
        setTimeout(() => {
          graphContainerRef.current?.scrollTo({ left: scrollPosition, behavior: 'smooth' });
        }, 100);
      }
    }
  }, [year, month, currentDate]);

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

  // お小遣いデータの計算（今月分）
  const currentMonthTransactions = allTransactions.filter(t => {
    const [y, m] = t.date.split('-');
    return parseInt(y) === year && parseInt(m) === month + 1;
  });

  const monthlyIncome = currentMonthTransactions
    .filter(t => t.type === 'income')
    .reduce((acc, t) => acc + Number(t.amount), 0);
    
  const monthlyExpense = currentMonthTransactions
    .filter(t => t.type === 'expense')
    .reduce((acc, t) => acc + Number(t.amount), 0);
    
  const monthlyBalance = monthlyIncome - monthlyExpense;

  return (
    <div className="space-y-6 pb-20">
      <div className="flex justify-center items-center px-4 relative">
        <h2 className="text-2xl font-black text-purple-600 tracking-tight">
          がんばりひょう
        </h2>
      </div>

      <div className="bg-white rounded-[2.5rem] p-6 shadow-sm border border-slate-100 grid grid-cols-2 gap-4">
        <div className="text-center">
          <div className="text-xs font-bold text-slate-400 mb-1">おしたく へいきん</div>
          <div className="text-3xl font-black text-purple-600">{averagePercent}%</div>
        </div>
        <div className="text-center">
          <div className="text-xs font-bold text-slate-400 mb-1">パーフェクト</div>
          <div className="text-3xl font-black text-orange-400">
            {perfectDaysCount} <span className="text-sm font-bold text-slate-600">にち</span>
          </div>
        </div>
        
        {/* お小遣いサマリー */}
        <div className="col-span-2 pt-4 mt-2 border-t border-slate-100 flex flex-col gap-3">
          <div className="grid grid-cols-2 gap-4 items-center">
            <div className="flex flex-col">
              <span className="text-[10px] font-bold text-slate-400">こんげつの しゅうし</span>
              <span className={`text-xl font-black ${monthlyBalance >= 0 ? 'text-emerald-500' : 'text-rose-500'}`}>
                {monthlyBalance >= 0 ? '+' : ''}¥{monthlyBalance.toLocaleString()}
              </span>
            </div>
            <div className="flex flex-col border-l border-slate-100 pl-4">
              <span className="text-[10px] font-bold text-slate-400">のこりのお金（ぜんぶ）</span>
              <span className="text-xl font-black text-slate-900">
                ¥{totalBalance.toLocaleString()}
              </span>
            </div>
          </div>
          
          <div className="grid grid-cols-2 gap-2 bg-slate-50 p-3 rounded-2xl">
            <div className="flex flex-col">
              <span className="text-[10px] font-bold text-slate-400">もらった</span>
              <span className="text-sm font-bold text-emerald-500">+¥{monthlyIncome.toLocaleString()}</span>
            </div>
            <div className="flex flex-col">
              <span className="text-[10px] font-bold text-slate-400">つかった</span>
              <span className="text-sm font-bold text-rose-500">-¥{monthlyExpense.toLocaleString()}</span>
            </div>
          </div>
        </div>
        
        {/* 取引一覧モーダルを開くボタン */}
        <div className="col-span-2 pt-2">
          <button 
            onClick={() => setShowTransactionModal(true)}
            className="w-full py-3 bg-slate-50 text-slate-600 font-bold rounded-2xl text-sm hover:bg-slate-100 transition-colors"
          >
            {month + 1}月の きろく をみる
          </button>
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
        
        <div ref={graphContainerRef} className="flex gap-2 overflow-x-auto pb-4 scrollbar-hide snap-x">
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
        <div className="flex items-center justify-between mb-6">
          <button onClick={prevMonth} className="p-2 hover:bg-green-50 rounded-full text-green-600 transition-colors">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="m15 18-6-6 6-6"/></svg>
          </button>
          
          <div className="flex items-center gap-2">
            <div className="text-green-500">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
              </svg>
            </div>
            <div className="flex flex-col items-center">
              <h3 className="font-black text-green-500">{year}年 {month + 1}月</h3>
              <span className="text-[10px] font-bold text-slate-400">タップして なおせるよ</span>
            </div>
          </div>
          
          <button onClick={nextMonth} className="p-2 hover:bg-green-50 rounded-full text-green-600 transition-colors">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="m9 18 6-6-6-6"/></svg>
          </button>
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
          const hasTransaction = allTransactions.some(t => t.date === key);
          
          return (
            <div 
              key={d} 
              onClick={() => onDateClick?.(key)}
              className={`aspect-square flex flex-col items-center justify-center relative rounded-2xl border cursor-pointer hover:bg-slate-50 transition-colors ${d === currentDate.getDate() ? 'border-purple-200' : 'border-slate-50 bg-white'}`}
            >
              <span className={`text-xs font-bold z-10 ${dayStatus || hasTransaction ? 'text-slate-800' : 'text-slate-400'}`}>
                {d}
              </span>
              {(dayStatus || hasTransaction) && (
                <div className="absolute inset-0 flex items-center justify-center pointer-events-none">
                  {(dayStatus?.morning && dayStatus?.evening) && (
                    <div className="absolute inset-0 bg-purple-50 rounded-2xl -z-0 border border-purple-100" />
                  )}
                  <div className="flex gap-1 mt-6 z-10">
                    {dayStatus?.morning && <div className="w-1.5 h-1.5 rounded-full bg-morning" />}
                    {dayStatus?.evening && <div className="w-1.5 h-1.5 rounded-full bg-evening" />}
                    {hasTransaction && <div className="w-1.5 h-1.5 rounded-full bg-amber-400" />}
                  </div>
                </div>
              )}
            </div>
          );
        })}
        </div>
      </div>

      {/* 月別 取引一覧モーダル */}
      <AnimatePresence>
        {showTransactionModal && (
          <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
            <motion.div 
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
              exit={{ opacity: 0 }}
              onClick={() => setShowTransactionModal(false)}
              className="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"
            />
            <motion.div 
              initial={{ opacity: 0, scale: 0.95, y: 20 }}
              animate={{ opacity: 1, scale: 1, y: 0 }}
              exit={{ opacity: 0, scale: 0.95, y: 20 }}
              className="bg-slate-50 w-full max-w-md rounded-[2.5rem] shadow-xl overflow-hidden z-10 flex flex-col max-h-[80vh]"
            >
              <div className="bg-white p-6 pb-4 border-b border-slate-100 flex items-center justify-between sticky top-0 z-20">
                <div className="flex items-center gap-3">
                  <div className="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"/><path d="M3 5v14a2 2 0 0 0 2 2h16v-5"/><path d="M18 12a2 2 0 0 0 0 4h4v-4Z"/></svg>
                  </div>
                  <div>
                    <h3 className="font-black text-slate-800">{month + 1}月の おこづかい</h3>
                    <div className="text-xs font-bold text-slate-400">
                      しゅうし：
                      <span className={monthlyBalance >= 0 ? 'text-emerald-500' : 'text-rose-500'}>
                        {monthlyBalance >= 0 ? '+' : ''}¥{monthlyBalance.toLocaleString()}
                      </span>
                    </div>
                  </div>
                </div>
                <button 
                  onClick={() => setShowTransactionModal(false)}
                  className="p-2 bg-slate-50 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-full transition-colors"
                >
                  <X className="w-6 h-6" />
                </button>
              </div>
              
              <div className="flex-1 overflow-y-auto p-4 space-y-3">
                {currentMonthTransactions.length === 0 ? (
                  <div className="text-center py-12">
                    <p className="text-slate-400 font-bold">この月の きろく はありません</p>
                  </div>
                ) : (
                  currentMonthTransactions
                    .sort((a, b) => new Date(b.date).getTime() - new Date(a.date).getTime())
                    .map(t => (
                      <div key={t.id} className="flex items-center justify-between p-4 bg-white rounded-2xl shadow-sm border border-slate-100">
                        <div className="flex items-center gap-3">
                          <div className={`p-2 rounded-xl ${t.type === 'income' ? 'bg-emerald-50 text-emerald-500' : 'bg-rose-50 text-rose-500'}`}>
                            {t.type === 'income' ? <Plus className="w-4 h-4" /> : <Minus className="w-4 h-4" />}
                          </div>
                          <div className="flex flex-col">
                            <span className="font-bold text-slate-700">{t.name}</span>
                            <span className="text-[10px] font-bold text-slate-400">{t.date}</span>
                          </div>
                        </div>
                        <div className={`font-black ${t.type === 'income' ? 'text-emerald-500' : 'text-rose-500'}`}>
                          {t.type === 'income' ? '+' : '-'}¥{Number(t.amount).toLocaleString()}
                        </div>
                      </div>
                    ))
                )}
              </div>
            </motion.div>
          </div>
        )}
      </AnimatePresence>
    </div>
  );
};
