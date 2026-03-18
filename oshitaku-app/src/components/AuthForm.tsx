import React, { useState } from 'react';
import { motion } from 'framer-motion';
import { supabase, isMockMode } from '../lib/supabase';
import { Mail, Lock, User, ArrowRight, Github } from 'lucide-react';

export const AuthForm: React.FC = () => {
  const [isLogin, setIsLogin] = useState(true);
  const [isResetMode, setIsResetMode] = useState(false);
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [fullName, setFullName] = useState('');
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    setError(null);

    if (isMockMode) {
      // モックモードでのシミュレーション
      setTimeout(() => {
        localStorage.setItem('mock_user', JSON.stringify({ 
          email, 
          user_metadata: { full_name: fullName || 'ゲストユーザー' } 
        }));
        window.location.reload();
      }, 1000);
      return;
    }

    try {
      if (isResetMode) {
        const { error } = await supabase.auth.resetPasswordForEmail(email, {
          redirectTo: window.location.origin
        });
        if (error) throw error;
        setError('パスワード再設定用のメールを送信しました。受信トレイをご確認ください。');
      } else if (isLogin) {
        const { error } = await supabase.auth.signInWithPassword({ email, password });
        if (error) throw error;
      } else {
        const { error } = await supabase.auth.signUp({
          email,
          password,
          options: {
            data: { full_name: fullName }
          }
        });
        if (error) throw error;
        setError('確認メールを送信しました。メールをチェックしてください。');
      }
    } catch (err: unknown) {
      setError(err instanceof Error ? err.message : 'An error occurred');
    } finally {
      setLoading(false);
    }
  };

  return (
    <motion.div
      initial={{ opacity: 0, scale: 0.95 }}
      animate={{ opacity: 1, scale: 1 }}
      className="p-8 md:p-10 bg-white/70 backdrop-blur-xl border border-white/40 shadow-2xl rounded-[2.5rem] w-full max-w-sm md:max-w-md mx-auto"
    >
      <div className="text-center mb-8">
        <h2 className="text-3xl font-extrabold text-slate-900 tracking-tight">
          {isResetMode ? 'パスワードの再設定' : isLogin ? 'おかえりなさい！' : 'はじめよう！'}
        </h2>
        <p className="text-slate-500 mt-2 font-medium">
          {isResetMode
            ? '登録したメールアドレスを入力してね'
            : isLogin
              ? 'ログインしておしたくを始めよう'
              : 'アカウントを作ってデータを同期'}
        </p>
      </div>

      <form onSubmit={handleSubmit} className="space-y-4">
        {!isLogin && !isResetMode && (
          <div className="relative">
            <User className="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" />
            <input
              type="text"
              placeholder="なまえ"
              required
              value={fullName}
              onChange={(e) => setFullName(e.target.value)}
              className="w-full pl-12 pr-4 py-4 bg-slate-100/50 border-none rounded-2xl focus:ring-2 focus:ring-primary/50 transition-all font-medium"
            />
          </div>
        )}
        
        <div className="relative">
          <Mail className="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" />
          <input
            type="email"
            placeholder="メールアドレス"
            required
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            className="w-full pl-12 pr-4 py-4 bg-slate-100/50 border-none rounded-2xl focus:ring-2 focus:ring-primary/50 transition-all font-medium"
          />
        </div>

        {!isResetMode && (
          <div className="relative">
            <Lock className="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" />
            <input
              type="password"
              placeholder="パスワード"
              required
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              className="w-full pl-12 pr-4 py-4 bg-slate-100/50 border-none rounded-2xl focus:ring-2 focus:ring-primary/50 transition-all font-medium"
            />
          </div>
        )}

        {error && (
          <p className="text-red-500 text-sm font-bold text-center bg-red-50 py-2 rounded-lg">
            {error}
          </p>
        )}

        <button
          type="submit"
          disabled={loading}
          className="w-full bg-primary text-white font-bold py-4 rounded-2xl shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-2 group disabled:opacity-50"
        >
          {loading ? '処理中...' : isResetMode ? 'リセットリンクを送信' : isLogin ? 'ログイン' : '登録する'}
          <ArrowRight className="w-5 h-5 group-hover:translate-x-1 transition-transform" />
        </button>
      </form>

      <div className="mt-8 flex flex-col gap-4 items-center">
        {isResetMode ? (
          <button
            onClick={() => { setIsResetMode(false); setError(null); }}
            className="text-slate-500 font-bold hover:text-primary transition-colors text-sm"
          >
            ログイン画面にもどる
          </button>
        ) : (
          <>
            {isLogin && (
              <button
                onClick={() => { setIsResetMode(true); setError(null); }}
                className="text-primary font-bold hover:text-blue-600 transition-colors text-sm mb-2"
              >
                パスワードを忘れた場合
              </button>
            )}
            <button
              onClick={() => { setIsLogin(!isLogin); setError(null); }}
              className="text-slate-500 font-bold hover:text-primary transition-colors text-sm"
            >
              {isLogin ? '新しくアカウントを作る' : 'すでにアカウントを持っている'}
            </button>
          </>
        )}
        
        <div className="flex items-center gap-4 w-full">
          <div className="h-px bg-slate-200 flex-1"></div>
          <span className="text-slate-300 text-xs font-bold uppercase tracking-widest">or</span>
          <div className="h-px bg-slate-200 flex-1"></div>
        </div>

        <button className="flex items-center justify-center gap-3 w-full py-4 bg-white border border-slate-200 rounded-2xl font-bold text-slate-700 hover:bg-slate-50 transition-all shadow-sm">
          <Github className="w-5 h-5" />
          Github でログイン
        </button>
      </div>
    </motion.div>
  );
};
