"use client";

import { useState } from "react";
import { signIn } from "next-auth/react";
import { useRouter } from "next/navigation";
import { Lock, Mail, ArrowRight, Github } from "lucide-react";

export default function LoginPage() {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");
  const router = useRouter();

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    setError("");

    try {
      const result = await signIn("credentials", {
        email,
        password,
        redirect: false,
      });

      if (result?.error) {
        setError("メールアドレスまたはパスワードが正しくありません。");
      } else {
        router.push("/");
        router.refresh();
      }
    } catch (err) {
      setError("エラーが発生しました。もう一度お試しください。");
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-[calc(100vh-64px)] flex items-center justify-center px-4 py-20 relative overflow-hidden">
      {/* 背景の装飾 */}
      <div className="absolute top-1/4 -left-20 w-80 h-80 bg-indigo-500/20 rounded-full blur-[100px] -z-10" />
      <div className="absolute bottom-1/4 -right-20 w-80 h-80 bg-purple-500/20 rounded-full blur-[100px] -z-10" />

      <div className="w-full max-w-md glass p-8 md:p-12 rounded-[2rem] shadow-2xl space-y-8">
        <div className="text-center space-y-2">
          <h1 className="text-3xl font-bold">おかえりなさい</h1>
          <p className="text-slate-500 text-sm">CloudNewであなたの夢の続きを。</p>
        </div>

        <form onSubmit={handleSubmit} className="space-y-6">
          <div className="space-y-2">
            <label className="text-sm font-medium ml-1">メールアドレス</label>
            <div className="relative group">
              <Mail className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors" size={20} />
              <input
                type="email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                placeholder="example@example.com"
                className="w-full bg-white/5 border border-white/10 rounded-2xl py-4 pl-12 pr-4 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/50 transition-all"
                required
              />
            </div>
          </div>

          <div className="space-y-2">
            <div className="flex justify-between items-center ml-1">
              <label className="text-sm font-medium">パスワード</label>
              <a href="#" className="text-xs text-indigo-400 hover:text-indigo-300 transition-colors">パスワードを忘れた場合</a>
            </div>
            <div className="relative group">
              <Lock className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors" size={20} />
              <input
                type="password"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                placeholder="••••••••"
                className="w-full bg-white/5 border border-white/10 rounded-2xl py-4 pl-12 pr-4 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/50 transition-all"
                required
              />
            </div>
          </div>

          {error && (
            <p className="text-red-500 text-xs text-center font-medium bg-red-500/10 py-2 rounded-lg border border-red-500/20">
              {error}
            </p>
          )}

          <button
            type="submit"
            disabled={loading}
            className="w-full py-4 bg-primary text-white rounded-2xl font-bold shadow-xl shadow-indigo-500/20 hover-subtle flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            {loading ? "ログイン中..." : "ログイン"} <ArrowRight size={20} />
          </button>
        </form>

        <div className="relative">
          <div className="absolute inset-0 flex items-center">
            <div className="w-full border-t border-white/10"></div>
          </div>
          <div className="relative flex justify-center text-xs uppercase">
            <span className="bg-[#0f172a] px-2 text-slate-500">または</span>
          </div>
        </div>

        <div className="grid grid-cols-2 gap-4">
          <button 
            type="button"
            className="flex items-center justify-center gap-2 py-3 glass rounded-2xl hover:bg-white/5 transition-colors text-sm font-medium"
          >
            <Github size={18} /> GitHub
          </button>
          <button 
            type="button"
            className="flex items-center justify-center gap-2 py-3 glass rounded-2xl hover:bg-white/5 transition-colors text-sm font-medium"
          >
            <Twitter size={18} /> Twitter
          </button>
        </div>

        <p className="text-center text-sm text-slate-500">
          アカウントをお持ちではありませんか？{" "}
          <a href="/register" className="text-indigo-400 font-bold hover:text-indigo-300 transition-colors">
            会員登録
          </a>
        </p>
      </div>
    </div>
  );
}

const Twitter = ({ size }: { size: number }) => (
  <svg width={size} height={size} viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
    <path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z" />
  </svg>
);
