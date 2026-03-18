import React, { useState, useEffect } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { Sun, Moon, Calendar as CalendarIcon, Settings, User as UserIcon, ChevronLeft, CheckCircle2, Trash2, Lock } from 'lucide-react';
import { AuthProvider, useAuth } from './context/AuthContext';
import { TaskCard } from './components/TaskCard';
import { AuthForm } from './components/AuthForm';
import { Calendar } from './components/Calendar';
import { INITIAL_TASKS, getDisplayDate, getTodayKey, getInitialTab } from './lib/constants';
import { supabase, isMockMode } from './lib/supabase';
import type { Task, TaskTime } from './lib/constants';
import confetti from 'canvas-confetti';

const EMOJI_LIST = ['✨', '💦', '🪥', '👕', '🚽', '🍙', '🧼', '🛁', '🛏️', '📚', '🎒', '👟', '✏️', '🐶', '⚽'];

const AppContent: React.FC = () => {
  const { user, signOut, loading: authLoading } = useAuth();
  const [tasks, setTasks] = useState<Task[]>([]);
  const [history, setHistory] = useState<Record<string, { morning: boolean; evening: boolean }>>({});
  const [userName, setUserName] = useState<string>('ゲスト');
  const [activeTab, setActiveTab] = useState<TaskTime>(getInitialTab());
  const [showSettings, setShowSettings] = useState(false);
  const [showHistory, setShowHistory] = useState(false);
  const [newTaskText, setNewTaskText] = useState('');
  const [selectedIcon, setSelectedIcon] = useState('✨');
  const [selectedDate, setSelectedDate] = useState<string>(getTodayKey());
  
  const [newPassword, setNewPassword] = useState('');
  const [confirmPassword, setConfirmPassword] = useState('');
  const [passLoading, setPassLoading] = useState(false);
  const [passMessage, setPassMessage] = useState<{ text: string; type: 'success' | 'error' } | null>(null);

  // 時間経過やアプリ復帰時の自動画面（日付・タブ）更新
  useEffect(() => {
    const handleTimeChange = () => {
      const currentToday = getTodayKey();
      const expectedTab = getInitialTab();
      
      setSelectedDate(prev => prev !== currentToday ? currentToday : prev);
      setActiveTab(prev => prev !== expectedTab ? expectedTab : prev);
    };

    const onVisibilityChange = () => {
      if (document.visibilityState === 'visible') {
        handleTimeChange();
      }
    };

    document.addEventListener('visibilitychange', onVisibilityChange);
    
    // 1分ごとに現在時刻とタブが一致しているかチェック（開いたまま放置対策）
    const intervalId = setInterval(() => {
      if (document.visibilityState === 'visible') {
        handleTimeChange();
      }
    }, 60000);

    return () => {
      document.removeEventListener('visibilitychange', onVisibilityChange);
      clearInterval(intervalId);
    };
  }, []);

  // 共通データ（履歴・名前）の読み込み
  useEffect(() => {
    const loadCommonData = async () => {
      if (authLoading) return;
      
      const userKey = user?.email || 'guest';
      
      // 履歴の読み込み
      const savedHistory = localStorage.getItem(`oshitaku_history_${userKey}`);
      if (savedHistory) {
        setHistory(JSON.parse(savedHistory));
      }

      // 名前の読み込み
      const savedName = localStorage.getItem(`oshitaku_user_name_${userKey}`);
      if (savedName) {
        setUserName(savedName);
      } else if (user?.user_metadata?.full_name) {
        setUserName(user.user_metadata.full_name);
      }
    };
    loadCommonData();
  }, [user, authLoading]);

  // 選択された日付のタスク読み込み
  useEffect(() => {
    const loadTasksData = () => {
      if (authLoading) return;
      
      const userKey = user?.email || 'guest';
      const targetDate = selectedDate;

      if (isMockMode || !user) {
        const savedTasks = localStorage.getItem(`tasks_${userKey}_${targetDate}`);
        if (savedTasks) {
          setTasks(JSON.parse(savedTasks));
        } else {
          setTasks(INITIAL_TASKS);
        }
      } else {
        // TODO: Supabaseからの読み込み実装はフェーズが進んだら行う
        const savedTasks = localStorage.getItem(`tasks_${userKey}_${targetDate}`);
        setTasks(savedTasks ? JSON.parse(savedTasks) : INITIAL_TASKS);
      }
    };

    loadTasksData();
  }, [user, authLoading, selectedDate]);

  const handleToggle = (id: string) => {
    const updatedTasks = tasks.map(t => 
      t.id === id ? { ...t, done: !t.done } : t
    );
    setTasks(updatedTasks);
    
    const targetDate = selectedDate;
    const userKey = user?.email || 'guest';
    localStorage.setItem(`tasks_${userKey}_${targetDate}`, JSON.stringify(updatedTasks));

    // 全て完了時の判定
    const currentTabTasks = updatedTasks.filter(t => t.time === activeTab);
    const isNowAllDone = currentTabTasks.length > 0 && currentTabTasks.every(t => t.done);
    const wasAlreadyAllDone = tasks.filter(t => t.time === activeTab).length > 0 && tasks.filter(t => t.time === activeTab).every(t => t.done);

    if (isNowAllDone && !wasAlreadyAllDone) {
      setTimeout(() => {
        confetti({
          particleCount: 150,
          spread: 70,
          origin: { y: 0.6 },
          colors: activeTab === 'morning' ? ['#fbbf24', '#f59e0b'] : ['#a78bfa', '#8b5cf6']
        });
      }, 300);
    }
    
    // 履歴を常に最新の完了状態で更新する
    const newHistory = {
      ...history,
      [targetDate]: {
        ...history[targetDate] || { morning: false, evening: false },
        [activeTab]: isNowAllDone
      }
    };
    
    setHistory(newHistory);
    localStorage.setItem(`oshitaku_history_${userKey}`, JSON.stringify(newHistory));
  };

  const handleCalendarDateClick = (dateKey: string) => {
    setSelectedDate(dateKey);
    setShowHistory(false);
  };

  const addTask = (time: TaskTime) => {
    if (!newTaskText) return;

    const newTask: Task = {
      id: Math.random().toString(36).substr(2, 9),
      text: newTaskText,
      icon: selectedIcon,
      time,
      done: false
    };

    const updatedTasks = [...tasks, newTask];
    setTasks(updatedTasks);
    const targetDate = selectedDate;
    const userKey = user?.email || 'guest';
    localStorage.setItem(`tasks_${userKey}_${targetDate}`, JSON.stringify(updatedTasks));
    setNewTaskText('');
  };

  const deleteTask = (id: string) => {
    if (!window.confirm('この おしたく を けしても いいかな？')) return;
    const updatedTasks = tasks.filter(t => t.id !== id);
    setTasks(updatedTasks);
    const targetDate = selectedDate;
    const userKey = user?.email || 'guest';
    localStorage.setItem(`tasks_${userKey}_${targetDate}`, JSON.stringify(updatedTasks));
  };

  const handlePasswordChange = async () => {
    setPassMessage(null);
    if (!newPassword || newPassword !== confirmPassword) {
      setPassMessage({ text: 'パスワードが一致しません', type: 'error' });
      return;
    }
    if (newPassword.length < 6) {
      setPassMessage({ text: 'パスワードは6文字以上で入力してください', type: 'error' });
      return;
    }
    setPassLoading(true);
    
    if (isMockMode) {
      setTimeout(() => {
        setPassMessage({ text: 'パスワードを変更しました（デモ環境）', type: 'success' });
        setNewPassword('');
        setConfirmPassword('');
        setPassLoading(false);
      }, 1000);
      return;
    }

    try {
      const { error } = await supabase.auth.updateUser({ password: newPassword });
      if (error) throw error;
      setPassMessage({ text: 'パスワードを更新しました', type: 'success' });
      setNewPassword('');
      setConfirmPassword('');
    } catch (err: unknown) {
      setPassMessage({ text: err instanceof Error ? err.message : 'An error occurred', type: 'error' });
    } finally {
      setPassLoading(false);
    }
  };

  const filteredTasks = tasks.filter(t => t.time === activeTab);
  const doneCount = filteredTasks.filter(t => t.done).length;
  const progress = filteredTasks.length > 0 ? (doneCount / filteredTasks.length) * 100 : 0;

  if (authLoading) return null;

  if (!user) {
    return (
      <div className="min-h-screen bg-slate-50 flex flex-col justify-center p-6">
        <AuthForm />
      </div>
    );
  }

  return (
    <div className="min-h-screen max-w-md mx-auto bg-slate-50 relative pb-24">
      <header className="p-6 bg-white/40 backdrop-blur-md sticky top-0 z-10 border-b border-slate-100">
        <div className="flex justify-between items-start mb-6">
          <div className="flex-1 min-w-0 pr-4">
            <h1 className="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight flex flex-wrap items-center gap-x-2">
              <span className="truncate max-w-[150px] sm:max-w-[250px] inline-block">{userName}</span>
              <span className="whitespace-nowrap">
                の <span className="text-indigo-500">おしたく</span> <span className="text-indigo-500">✨</span>
              </span>
            </h1>
            <p className="text-slate-500 font-medium mt-1">
              {selectedDate === getTodayKey() ? getDisplayDate() : (
                <span className="flex items-center gap-2">
                  <span className="text-purple-600 font-bold bg-purple-100 px-3 py-1 rounded-full text-xs">かこ</span>
                  {(() => {
                    const [y, m, d] = selectedDate.split('-');
                    const dateObj = new Date(parseInt(y), parseInt(m)-1, parseInt(d));
                    const days = ['日', '月', '火', '水', '木', '金', '土'];
                    return `${parseInt(m)}月${parseInt(d)}日(${days[dateObj.getDay()]})`;
                  })()}
                  <button 
                    onClick={() => setSelectedDate(getTodayKey())} 
                    className="text-sm text-sky-500 hover:text-sky-600 underline font-bold transition-colors ml-1"
                  >
                    きょうにもどる
                  </button>
                </span>
              )}
            </p>
          </div>
          <div className="flex gap-2">
            <button 
              onClick={() => setShowHistory(true)}
              className="p-3 bg-white shadow-sm hover:bg-slate-100 rounded-2xl transition-all border border-slate-100 active:scale-95"
            >
              <CalendarIcon className="w-6 h-6 text-purple-600" />
            </button>
            <button 
              onClick={() => setShowSettings(true)}
              className="p-3 bg-white shadow-sm hover:bg-slate-100 rounded-2xl transition-all border border-slate-100 active:scale-95"
            >
              <Settings className="w-6 h-6 text-slate-400" />
            </button>
          </div>
        </div>

        <div className="flex p-1 bg-slate-100 rounded-2xl relative">
          <motion.div
            layoutId="tab-bg"
            className={`absolute inset-1 rounded-xl shadow-sm ${activeTab === 'morning' ? 'bg-morning' : 'bg-evening'}`}
            transition={{ type: "spring", bounce: 0.2, duration: 0.6 }}
          />
          <button
            onClick={() => setActiveTab('morning')}
            className={`flex-1 flex items-center justify-center gap-2 py-3 rounded-xl z-10 font-bold transition-colors ${activeTab === 'morning' ? 'text-morning-foreground' : 'text-slate-400'}`}
          >
            <Sun className={`w-5 h-5 ${activeTab === 'morning' ? 'fill-current' : ''}`} />
            あさ
          </button>
          <button
            onClick={() => setActiveTab('evening')}
            className={`flex-1 flex items-center justify-center gap-2 py-3 rounded-xl z-10 font-bold transition-colors ${activeTab === 'evening' ? 'text-evening-foreground' : 'text-slate-400'}`}
          >
            <Moon className={`w-5 h-5 ${activeTab === 'evening' ? 'fill-current' : ''}`} />
            よる
          </button>
        </div>

        <div className="mt-6">
          <div className="flex justify-between text-sm font-bold text-slate-400 mb-2 uppercase tracking-wider">
            <span>どれくらい できたかな？</span>
            <span>{Math.round(progress)}%</span>
          </div>
          <div className="h-3 w-full bg-slate-100 rounded-full overflow-hidden">
            <motion.div
              initial={{ width: 0 }}
              animate={{ width: `${progress}%` }}
              className={`h-full ${activeTab === 'morning' ? 'bg-morning' : 'bg-evening'}`}
            />
          </div>
        </div>
      </header>

      <main className="p-6">
        <div className="grid gap-4">
          <AnimatePresence mode="popLayout">
            {filteredTasks.map(task => (
              <TaskCard 
                key={task.id} 
                task={task} 
                onToggle={handleToggle} 
                isMorning={activeTab === 'morning'} 
              />
            ))}
          </AnimatePresence>
        </div>

      </main>

      <AnimatePresence>
        {showHistory && (
          <motion.div
            initial={{ y: '100%' }}
            animate={{ y: 0 }}
            exit={{ y: '100%' }}
            transition={{ type: 'spring', damping: 25, stiffness: 200 }}
            className="fixed inset-0 z-50 bg-slate-50 overflow-y-auto"
          >
            <div className="max-w-md mx-auto min-h-screen bg-slate-50 shadow-xl pb-32">
              <header className="p-4 flex items-center justify-between sticky top-0 bg-slate-50/80 backdrop-blur-md z-20 shadow-sm border-b border-white/40">
                <button 
                  onClick={() => setShowHistory(false)}
                  className="p-3 bg-white hover:bg-slate-50 rounded-full shadow-sm transition-all"
                >
                  <ChevronLeft className="w-6 h-6 text-slate-900" />
                </button>
                <div className="w-12 h-12" /> {/* Layout balancer */}
              </header>
              <div className="p-4 -mt-16">
                <Calendar history={history} onDateClick={handleCalendarDateClick} />
              </div>
            </div>
          </motion.div>
        )}
      </AnimatePresence>

      <AnimatePresence>
        {showSettings && (
          <motion.div
            initial={{ y: '100%' }}
            animate={{ y: 0 }}
            exit={{ y: '100%' }}
            transition={{ type: 'spring', damping: 25, stiffness: 200 }}
            className="fixed inset-0 z-50 bg-slate-50 overflow-y-auto"
          >
            <div className="max-w-md mx-auto min-h-screen bg-white shadow-xl">
              <header className="p-6 flex items-center justify-between sticky top-0 bg-white/80 backdrop-blur-md z-10 border-b border-slate-100">
                <button 
                  onClick={() => setShowSettings(false)}
                  className="p-2 hover:bg-slate-100 rounded-full transition-colors"
                >
                  <ChevronLeft className="w-6 h-6 text-slate-900" />
                </button>
                <h2 className="text-xl font-black text-slate-900">せってい</h2>
                <div className="w-10" /> {/* Spacer */}
              </header>

              <div className="p-6 space-y-8 pb-32">
                {/* おなまえ */}
                <div className="space-y-4">
                  <div className="flex items-center gap-2 text-slate-400">
                    <UserIcon className="w-4 h-4" />
                    <span className="text-xs font-bold uppercase tracking-widest">おなまえ</span>
                  </div>
                  <input 
                    type="text" 
                    value={userName}
                    onChange={(e) => {
                      setUserName(e.target.value);
                      const userKey = user?.email || 'guest';
                      localStorage.setItem(`oshitaku_user_name_${userKey}`, e.target.value);
                    }}
                    className="w-full bg-slate-50 border-none rounded-2xl p-4 text-slate-700 font-bold placeholder:text-slate-300 focus:ring-2 focus:ring-primary/20 transition-all outline-none"
                    placeholder="おなまえを おしえてね"
                  />
                  {user && (
                    <div className="px-1 text-xs text-slate-400">
                      登録アドレス: {user.email}
                    </div>
                  )}
                </div>

                {/* あたらしい おしたく */}
                <div className="space-y-4 p-6 bg-slate-50/50 rounded-3xl border border-slate-100">
                  <div className="flex items-center gap-2 text-slate-400">
                    <CheckCircle2 className="w-4 h-4" />
                    <span className="text-xs font-bold uppercase tracking-widest">あたらしい おしたく</span>
                  </div>
                  
                  <input 
                    type="text"
                    value={newTaskText}
                    onChange={(e) => setNewTaskText(e.target.value)}
                    placeholder="なにをする？"
                    className="w-full bg-white border-none rounded-2xl p-4 text-slate-700 font-bold placeholder:text-slate-300 transition-all outline-none shadow-sm"
                  />

                  <div className="flex flex-wrap gap-2 py-2">
                    {EMOJI_LIST.map(emoji => (
                      <button
                        key={emoji}
                        onClick={() => setSelectedIcon(emoji)}
                        className={`text-2xl p-2 rounded-xl transition-all ${selectedIcon === emoji ? 'bg-primary/10 scale-110 shadow-sm border border-primary/20' : 'hover:bg-white hover:scale-105'}`}
                      >
                        {emoji}
                      </button>
                    ))}
                  </div>

                  <div className="flex gap-4">
                    <button
                      onClick={() => addTask('morning')}
                      disabled={!newTaskText}
                      className="flex-1 py-4 bg-[#0ea5e9] text-white font-black rounded-2xl shadow-lg shadow-sky-200 active:scale-95 disabled:opacity-50 disabled:scale-100 transition-all"
                    >
                      あさについか
                    </button>
                    <button
                      onClick={() => addTask('evening')}
                      disabled={!newTaskText}
                      className="flex-1 py-4 bg-[#a855f7] text-white font-black rounded-2xl shadow-lg shadow-purple-200 active:scale-95 disabled:opacity-50 disabled:scale-100 transition-all"
                    >
                      よるについか
                    </button>
                  </div>
                </div>

                {/* おしたくリスト */}
                <div className="space-y-4 pt-4 border-t border-slate-200">
                  {tasks.map(task => (
                    <div key={task.id} className="flex items-center justify-between p-4 bg-white border border-slate-100 rounded-2xl shadow-sm hover:shadow-md transition-all group">
                      <div className="flex items-center gap-4">
                        <span className="text-2xl">{task.icon}</span>
                        <div>
                          <div className="font-bold text-slate-700">{task.text}</div>
                          <span className={`text-[10px] font-bold px-2 py-0.5 rounded-full ${task.time === 'morning' ? 'bg-sky-100 text-sky-600' : 'bg-purple-100 text-purple-600'}`}>
                            {task.time === 'morning' ? 'あさ' : 'よる'}
                          </span>
                        </div>
                      </div>
                      <button 
                        onClick={() => deleteTask(task.id)}
                        className="p-2 text-slate-200 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all"
                      >
                        <Trash2 className="w-5 h-5" />
                      </button>
                    </div>
                  ))}
                </div>

                {/* パスワード変更 */}
                {user && (
                <div className="space-y-4 pt-8 border-t border-slate-100">
                  <div className="flex items-center gap-2 text-slate-400">
                    <Lock className="w-4 h-4" />
                    <span className="text-xs font-bold uppercase tracking-widest">パスワード変更</span>
                  </div>
                  <input 
                    type="password" 
                    value={newPassword}
                    onChange={(e) => setNewPassword(e.target.value)}
                    className="w-full bg-slate-50 border-none rounded-2xl p-4 text-slate-700 font-bold placeholder:text-slate-300 focus:ring-2 focus:ring-primary/20 transition-all outline-none"
                    placeholder="新しいパスワード (6文字以上)"
                  />
                  <input 
                    type="password" 
                    value={confirmPassword}
                    onChange={(e) => setConfirmPassword(e.target.value)}
                    className="w-full bg-slate-50 border-none rounded-2xl p-4 text-slate-700 font-bold placeholder:text-slate-300 focus:ring-2 focus:ring-primary/20 transition-all outline-none"
                    placeholder="パスワード（かくにん用）"
                  />
                  {passMessage && (
                    <p className={`text-sm font-bold text-center p-2 rounded-lg ${passMessage.type === 'error' ? 'text-red-500 bg-red-50' : 'text-emerald-500 bg-emerald-50'}`}>
                      {passMessage.text}
                    </p>
                  )}
                  <button 
                    onClick={handlePasswordChange}
                    disabled={passLoading || !newPassword}
                    className="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 rounded-2xl transition-all disabled:opacity-50 active:scale-95"
                  >
                    {passLoading ? 'こうしん中...' : 'パスワードをこうしん'}
                  </button>
                </div>
                )}

                {user && (
                  <button 
                    onClick={() => signOut()}
                    className="w-full py-4 text-red-500 font-bold border-2 border-red-50 rounded-2xl hover:bg-red-50 transition-colors"
                  >
                    ログアウト
                  </button>
                )}
              </div>
            </div>
          </motion.div>
        )}
      </AnimatePresence>
    </div>
  );
};

const App: React.FC = () => {
  return (
    <AuthProvider>
      <AppContent />
    </AuthProvider>
  );
};

export default App;
