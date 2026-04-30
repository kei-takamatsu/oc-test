import { createClient } from '@/utils/supabase/server'
import { redirect } from 'next/navigation'
import Link from 'next/link'
import { ChevronLeft, LogOut, User, Mail, Shield, Zap, Share2 } from 'lucide-react'
import { logout } from '../../login/actions'

export default async function ProfilePage() {
  const supabase = await createClient()
  const { data: { user } } = await supabase.auth.getUser()

  if (!user) {
    redirect('/login')
  }

  // レシピ数を取得
  const { count } = await supabase
    .from('recipes')
    .select('*', { count: 'exact', head: true })

  return (
    <div className="min-h-screen bg-gray-50 pb-20">
      {/* App Bar */}
      <header className="sticky top-0 z-10 bg-white border-b border-gray-200 px-4 py-3 flex items-center shadow-sm">
        <Link href="/recipes" className="p-2 -ml-2 text-gray-500 hover:text-gray-900 transition-all active:scale-95">
          <ChevronLeft size={24} />
        </Link>
        <h1 className="text-lg font-bold text-gray-900 ml-2">マイページ</h1>
      </header>

      <main className="p-4 max-w-2xl mx-auto space-y-6 mt-4">
        {/* Profile Card */}
        <div className="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
          <div className="h-24 bg-gradient-to-r from-orange-400 to-rose-400"></div>
          <div className="px-6 pb-6">
            <div className="relative -mt-12 mb-4">
              <div className="w-24 h-24 bg-white rounded-2xl shadow-md p-1">
                <div className="w-full h-full bg-gray-100 rounded-xl flex items-center justify-center text-gray-400">
                  <User size={48} />
                </div>
              </div>
            </div>
            <h2 className="text-xl font-bold text-gray-900">{user.email?.split('@')[0]}</h2>
            <div className="flex items-center gap-1.5 text-sm text-gray-500 mt-1">
              <Mail size={14} />
              <span>{user.email}</span>
            </div>
          </div>
        </div>

        {/* Stats */}
        <div className="grid grid-cols-2 gap-4">
          <div className="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 text-center">
            <div className="text-2xl font-black text-orange-500">{count || 0}</div>
            <div className="text-xs font-bold text-gray-400 uppercase tracking-wider mt-1">保存済みレシピ</div>
          </div>
          <div className="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 text-center">
            <div className="text-2xl font-black text-blue-500">Free</div>
            <div className="text-xs font-bold text-gray-400 uppercase tracking-wider mt-1">現在のプラン</div>
          </div>
        </div>

        {/* Menu Items */}
        <div className="bg-white rounded-2xl shadow-sm border border-gray-100 divide-y divide-gray-50 overflow-hidden">
          <div className="p-4 flex items-center justify-between hover:bg-gray-50 transition-colors cursor-not-allowed opacity-60">
            <div className="flex items-center gap-3">
              <div className="w-10 h-10 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center">
                <Zap size={20} />
              </div>
              <div>
                <div className="text-sm font-bold text-gray-900">AI解析設定 (準備中)</div>
                <div className="text-xs text-gray-500">独自のAPIキーを使用する</div>
              </div>
            </div>
          </div>

          <div className="p-4 flex items-center justify-between hover:bg-gray-50 transition-colors cursor-not-allowed opacity-60">
            <div className="flex items-center gap-3">
              <div className="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                <Share2 size={20} />
              </div>
              <div>
                <div className="text-sm font-bold text-gray-900">SNS連携 (準備中)</div>
                <div className="text-xs text-gray-500">Instagram / X と連携する</div>
              </div>
            </div>
          </div>

          <div className="p-4 flex items-center justify-between hover:bg-gray-50 transition-colors cursor-not-allowed opacity-60">
            <div className="flex items-center gap-3">
              <div className="w-10 h-10 bg-green-50 text-green-600 rounded-xl flex items-center justify-center">
                <Shield size={20} />
              </div>
              <div>
                <div className="text-sm font-bold text-gray-900">プライバシー設定 (準備中)</div>
                <div className="text-xs text-gray-500">レシピの公開・非公開など</div>
              </div>
            </div>
          </div>
        </div>

        {/* Logout */}
        <form action={logout}>
          <button className="w-full flex items-center justify-center gap-2 p-4 bg-white text-red-500 font-bold rounded-2xl shadow-sm border border-red-50 hover:bg-red-50 transition-colors active:scale-[0.98]">
            <LogOut size={20} />
            ログアウト
          </button>
        </form>

        <p className="text-center text-[10px] text-gray-300 font-medium tracking-widest uppercase">
          RecipeShelf v1.2.0
        </p>
      </main>
    </div>
  )
}
