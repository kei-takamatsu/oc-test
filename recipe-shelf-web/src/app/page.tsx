import { login, signup } from './login/actions'
import { ChefHat } from 'lucide-react'

export default function LoginPage({
  searchParams,
}: {
  searchParams: { error: string }
}) {
  return (
    <div className="flex items-center justify-center min-h-screen bg-gray-50">
      <div className="w-full max-w-md p-8 space-y-8 bg-white rounded-2xl shadow-xl">
        <div className="flex flex-col items-center">
          <div className="p-3 bg-orange-100 rounded-full mb-4">
            <ChefHat size={40} className="text-orange-500" />
          </div>
          <h2 className="text-3xl font-bold text-gray-900 tracking-tight">RecipeShelf Web</h2>
          <p className="mt-2 text-sm text-gray-500">クラウド上のレシピをブラウザで管理</p>
        </div>

        {searchParams?.error && (
          <div className="p-4 text-sm text-red-600 bg-red-50 rounded-lg">
            {searchParams.error}
          </div>
        )}

        <form className="mt-8 space-y-6">
          <div className="space-y-4">
            <div>
              <label className="block text-sm font-medium text-gray-700" htmlFor="email">
                メールアドレス
              </label>
              <input
                id="email"
                name="email"
                type="email"
                required
                placeholder="you@email.com"
                className="w-full px-4 py-3 mt-1 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
              />
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700" htmlFor="password">
                パスワード
              </label>
              <input
                id="password"
                name="password"
                type="password"
                required
                placeholder="••••••••"
                className="w-full px-4 py-3 mt-1 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                minLength={6}
              />
            </div>
          </div>

          <div className="flex flex-col gap-3 pt-2">
            <button
              formAction={login}
              className="w-full px-4 py-3 text-white bg-orange-500 hover:bg-orange-600 rounded-xl font-medium transition-colors shadow-sm"
            >
              ログイン
            </button>
            <button
              formAction={signup}
              className="w-full px-4 py-3 text-orange-600 bg-orange-50 hover:bg-orange-100 border border-orange-200 rounded-xl font-medium transition-colors shadow-sm"
            >
              新規登録
            </button>
          </div>
        </form>
      </div>
    </div>
  )
}
