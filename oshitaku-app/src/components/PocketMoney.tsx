import React, { useState } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { Plus, Minus, Trash2, Wallet, TrendingUp, TrendingDown } from 'lucide-react';
import type { Transaction } from '../lib/constants';

interface PocketMoneyProps {
  transactions: Transaction[];
  onAddTransaction: (name: string, amount: number, type: 'expense' | 'income') => void;
  onDeleteTransaction: (id: string) => void;
  initialBalance: number;
  totalBalance: number;
}

export const PocketMoney: React.FC<PocketMoneyProps> = ({ 
  transactions, 
  onAddTransaction, 
  onDeleteTransaction,
  initialBalance,
  totalBalance
}) => {
  const [name, setName] = useState('');
  const [amount, setAmount] = useState('');
  const [type, setType] = useState<'expense' | 'income'>('expense');

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!name || !amount) return;
    onAddTransaction(name, parseInt(amount), type);
    setName('');
    setAmount('');
  };

  const dailyTotal = transactions.reduce((acc, t) => {
    return t.type === 'income' ? acc + Number(t.amount) : acc - Number(t.amount);
  }, 0);

  return (
    <div className="space-y-6">
      {/* 資産サマリー */}
      <div className="grid grid-cols-2 gap-4">
        <div className="bg-white p-4 rounded-3xl shadow-sm border border-slate-100">
          <div className="flex items-center gap-2 text-slate-400 mb-1">
            <Wallet className="w-4 h-4" />
            <span className="text-[10px] font-bold uppercase tracking-wider">のこりのお金</span>
          </div>
          <div className="text-2xl font-black text-slate-900">
            ¥{totalBalance.toLocaleString()}
          </div>
        </div>
        <div className="bg-white p-4 rounded-3xl shadow-sm border border-slate-100">
          <div className="flex items-center gap-2 text-slate-400 mb-1">
            <TrendingUp className="w-4 h-4" />
            <span className="text-[10px] font-bold uppercase tracking-wider">きょうの しゅうし</span>
          </div>
          <div className={`text-2xl font-black ${dailyTotal >= 0 ? 'text-emerald-500' : 'text-rose-500'}`}>
            {dailyTotal >= 0 ? '+' : ''}¥{dailyTotal.toLocaleString()}
          </div>
        </div>
      </div>

      {/* 入力フォーム */}
      <form onSubmit={handleSubmit} className="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 space-y-4">
        <div className="flex p-1 bg-slate-100 rounded-2xl relative">
          <motion.div
            layoutId="type-bg"
            className={`absolute inset-1 rounded-xl shadow-sm ${type === 'expense' ? 'bg-rose-500' : 'bg-emerald-500'}`}
            transition={{ type: "spring", bounce: 0.2, duration: 0.6 }}
          />
          <button
            type="button"
            onClick={() => setType('expense')}
            className={`flex-1 flex items-center justify-center gap-2 py-2 rounded-xl z-10 font-bold transition-colors ${type === 'expense' ? 'text-white' : 'text-slate-400'}`}
          >
            <Minus className="w-4 h-4" />
            つかった
          </button>
          <button
            type="button"
            onClick={() => setType('income')}
            className={`flex-1 flex items-center justify-center gap-2 py-2 rounded-xl z-10 font-bold transition-colors ${type === 'income' ? 'text-white' : 'text-slate-400'}`}
          >
            <Plus className="w-4 h-4" />
            もらった
          </button>
        </div>

        <div className="space-y-3">
          <input
            type="text"
            value={name}
            onChange={(e) => setName(e.target.value)}
            placeholder="なにに つかった？"
            className="w-full bg-slate-50 border-none rounded-2xl p-4 text-slate-700 font-bold placeholder:text-slate-300 transition-all outline-none focus:ring-2 focus:ring-slate-100"
          />
          <div className="relative">
            <span className="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-slate-400">¥</span>
            <input
              type="text"
              inputMode="numeric"
              pattern="[0-9]*"
              value={amount}
              onFocus={(e) => e.target.select()}
              onChange={(e) => {
                const val = e.target.value.replace(/[^0-9]/g, '');
                setAmount(val);
              }}
              placeholder="いくら？"
              className="w-full bg-slate-50 border-none rounded-2xl p-4 pl-8 text-slate-700 font-bold placeholder:text-slate-300 transition-all outline-none focus:ring-2 focus:ring-slate-100"
            />
          </div>
          <button
            type="submit"
            disabled={!name || !amount}
            className={`w-full py-4 text-white font-black rounded-2xl shadow-lg transition-all active:scale-95 disabled:opacity-50 disabled:scale-100 ${type === 'expense' ? 'bg-rose-500 shadow-rose-100' : 'bg-emerald-500 shadow-emerald-100'}`}
          >
            きろくする
          </button>
        </div>
      </form>

      {/* 取引一覧 */}
      <div className="space-y-3">
        <h3 className="text-xs font-bold text-slate-400 uppercase tracking-widest px-1">きょうの きろく</h3>
        <AnimatePresence mode="popLayout">
          {transactions.length === 0 ? (
            <div className="text-center py-12 bg-white rounded-3xl border border-dashed border-slate-200">
              <p className="text-slate-300 font-bold">まだ きろくが ありません</p>
            </div>
          ) : (
            transactions.map((t) => (
              <motion.div
                key={t.id}
                initial={{ opacity: 0, y: 10 }}
                animate={{ opacity: 1, y: 0 }}
                exit={{ opacity: 0, scale: 0.95 }}
                className="flex items-center justify-between p-4 bg-white border border-slate-100 rounded-2xl shadow-sm group"
              >
                <div className="flex items-center gap-4">
                  <div className={`p-2 rounded-xl ${t.type === 'income' ? 'bg-emerald-50 text-emerald-500' : 'bg-rose-50 text-rose-500'}`}>
                    {t.type === 'income' ? <Plus className="w-5 h-5" /> : <Minus className="w-5 h-5" />}
                  </div>
                  <div>
                    <div className="font-bold text-slate-700">{t.name}</div>
                    <div className={`text-xs font-bold ${t.type === 'income' ? 'text-emerald-500' : 'text-rose-500'}`}>
                      {t.type === 'income' ? '+' : '-'}¥{t.amount.toLocaleString()}
                    </div>
                  </div>
                </div>
                <button
                  onClick={() => onDeleteTransaction(t.id)}
                  className="p-2 text-slate-200 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-all"
                >
                  <Trash2 className="w-5 h-5" />
                </button>
              </motion.div>
            ))
          )}
        </AnimatePresence>
      </div>
    </div>
  );
};
