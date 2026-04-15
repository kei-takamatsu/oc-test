"use client";

import Link from "next/link";
import { useSession, signOut } from "next-auth/react";
import { User as UserIcon, LogOut, ChevronDown, Bell } from "lucide-react";
import { useState, useEffect } from "react";

export default function Header() {
  const { data: session, status } = useSession();
  const [dropdownOpen, setDropdownOpen] = useState(false);
  const [unreadCount, setUnreadCount] = useState(0);

  useEffect(() => {
    if (session) {
      const fetchCount = () => {
        fetch("/api/notifications/count")
          .then(r => r.json())
          .then(d => setUnreadCount(d.count || 0))
          .catch(() => {});
      };
      fetchCount();
      const interval = setInterval(fetchCount, 30000);
      return () => clearInterval(interval);
    }
  }, [session]);

  return (
    <header className="sticky top-0 z-50 w-full glass">
      <div className="container mx-auto px-4 h-16 flex items-center justify-between">
        <Link href="/" className="text-xl font-bold bg-gradient-to-r from-indigo-500 to-purple-500 bg-clip-text text-transparent">
          CloudNew
        </Link>
        <nav className="hidden md:flex gap-8 text-sm font-medium">
          <Link href="/" className="hover:text-primary transition-colors">プロジェクトを探す</Link>
          <Link href="/projects/create" className="hover:text-primary transition-colors">プロジェクトを始める</Link>
        </nav>
        <div className="flex gap-4 items-center">
          {status === "loading" ? (
            <div className="h-8 w-20 bg-slate-200 dark:bg-slate-800 animate-pulse rounded-full" />
          ) : session ? (
            <div className="flex items-center gap-3">
              {/* 通知ベル */}
              <Link
                href="/mypage/notifications"
                className="relative p-2 rounded-xl hover:bg-white/5 transition-colors"
              >
                <Bell size={20} className="text-slate-400" />
                {unreadCount > 0 && (
                  <span className="absolute -top-0.5 -right-0.5 w-4 h-4 bg-red-500 text-white text-[9px] font-black rounded-full flex items-center justify-center shadow-lg">
                    {unreadCount > 9 ? '9+' : unreadCount}
                  </span>
                )}
              </Link>

              <div className="relative">
              <button 
                onClick={() => setDropdownOpen(!dropdownOpen)}
                className="flex items-center gap-2 pl-2 pr-1 py-1 rounded-full hover:bg-white/5 transition-colors border border-white/10"
              >
                <div className="w-8 h-8 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center text-white text-xs font-bold uppercase">
                  {session.user?.name?.[0] || 'U'}
                </div>
                <span className="text-sm font-medium pr-1">{session.user?.name}</span>
                <ChevronDown size={14} className={`text-slate-400 transition-transform ${dropdownOpen ? 'rotate-180' : ''}`} />
              </button>

              {dropdownOpen && (
                <>
                  <div 
                    className="fixed inset-0 z-10" 
                    onClick={() => setDropdownOpen(false)}
                  />
                  <div className="absolute right-0 mt-2 w-48 glass rounded-2xl shadow-xl border border-white/10 py-2 z-20 overflow-hidden">
                    <Link 
                      href="/mypage" 
                      className="flex items-center gap-2 px-4 py-2 text-sm hover:bg-white/5 transition-colors"
                      onClick={() => setDropdownOpen(false)}
                    >
                      <UserIcon size={16} /> マイページ
                    </Link>
                    <button 
                      onClick={() => {
                        signOut();
                        setDropdownOpen(false);
                      }}
                      className="w-full flex items-center gap-2 px-4 py-2 text-sm hover:bg-white/5 transition-colors text-red-500"
                    >
                      <LogOut size={16} /> ログアウト
                    </button>
                  </div>
                </>
              )}
              </div>
            </div>
          ) : (
            <>
              <Link href="/login" className="px-4 py-2 text-sm font-medium hover:text-primary transition-colors">ログイン</Link>
              <Link href="/register" className="px-4 py-2 text-sm font-medium bg-primary text-white rounded-full shadow-lg shadow-indigo-500/20 hover-subtle">
                会員登録
              </Link>
            </>
          )}
        </div>
      </div>
    </header>
  );
}
