import Link from 'next/link'
import { ChevronLeft, Save } from 'lucide-react'
import { addRecipe } from './actions'

export default async function NewRecipePage({ searchParams }: { searchParams: Promise<{ error?: string }> }) {
  const resolvedParams = await searchParams;

  return (
    <div className="min-h-screen bg-gray-50 pb-20">
      {/* App Bar */}
      <header className="sticky top-0 z-10 bg-white border-b border-gray-200 px-4 py-3 flex items-center shadow-sm">
        <Link href="/recipes" className="p-2 -ml-2 text-gray-500 hover:text-gray-900 transition-colors">
          <ChevronLeft size={24} />
        </Link>
        <h1 className="text-xl font-bold text-gray-900 ml-2">レシピを手動追加</h1>
      </header>

      <main className="p-4 max-w-2xl mx-auto mt-4">
        {resolvedParams?.error && (
          <div className="mb-6 p-4 text-sm text-red-600 bg-red-50 rounded-lg border border-red-100">
            {resolvedParams.error}
          </div>
        )}

        <form action={addRecipe} className="space-y-6">
          <div className="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 space-y-6">
            
            {/* Title */}
            <div>
              <label htmlFor="title" className="block text-sm font-bold text-gray-700 mb-1">
                レシピのタイトル <span className="text-red-500">*</span>
              </label>
              <input
                type="text"
                id="title"
                name="title"
                required
                placeholder="例: とろとろ豚の角煮"
                className="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
              />
            </div>

            {/* Image URL */}
            <div>
              <label htmlFor="imageUrl" className="block text-sm font-bold text-gray-700 mb-1">
                画像のURL (任意)
              </label>
              <input
                type="url"
                id="imageUrl"
                name="imageUrl"
                placeholder="https://..."
                className="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
              />
              <p className="text-xs text-gray-500 mt-1">※画像がない場合は自動的にダミー画像が適用されます。</p>
            </div>

            {/* Metas: Grid */}
            <div className="grid grid-cols-2 sm:grid-cols-3 gap-4">
              <div>
                <label htmlFor="prepTime" className="block text-sm font-bold text-gray-700 mb-1">準備時間</label>
                <input
                  type="text"
                  id="prepTime"
                  name="prepTime"
                  placeholder="例: 10分"
                  className="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 transition-colors"
                />
              </div>
              <div>
                <label htmlFor="cookTime" className="block text-sm font-bold text-gray-700 mb-1">調理時間</label>
                <input
                  type="text"
                  id="cookTime"
                  name="cookTime"
                  placeholder="例: 45分"
                  className="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 transition-colors"
                />
              </div>
              <div>
                <label htmlFor="servings" className="block text-sm font-bold text-gray-700 mb-1">何人前</label>
                <input
                  type="text"
                  id="servings"
                  name="servings"
                  placeholder="例: 2人分"
                  className="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 transition-colors"
                />
              </div>
            </div>

          </div>

          <div className="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 space-y-6">
            {/* Ingredients */}
            <div>
              <label htmlFor="ingredients" className="block text-sm font-bold text-gray-700 mb-1">
                材料 <span className="text-red-500">*</span>
              </label>
              <p className="text-xs text-gray-500 mb-2">1行に1つの材料を改行して入力してください。</p>
              <textarea
                id="ingredients"
                name="ingredients"
                required
                rows={6}
                placeholder="豚バラ肉 500g&#10;ネギの青い部分 1本分&#10;しょうが 1片"
                className="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors placeholder:text-gray-400"
              ></textarea>
            </div>

            {/* Instructions */}
            <div>
              <label htmlFor="instructions" className="block text-sm font-bold text-gray-700 mb-1">
                作り方 <span className="text-red-500">*</span>
              </label>
              <p className="text-xs text-gray-500 mb-2">1行に1ステップずつ改行して入力してください。</p>
              <textarea
                id="instructions"
                name="instructions"
                required
                rows={8}
                placeholder="豚肉を5cm角に切る。&#10;フライパンで表面に焼き色をつける。&#10;鍋に移し、ネギと生姜を入れて2時間煮込む。"
                className="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors placeholder:text-gray-400"
              ></textarea>
            </div>
          </div>

          {/* Submit */}
          <div className="pt-4 pb-10">
            <button
              type="submit"
              className="w-full flex items-center justify-center gap-2 px-4 py-4 text-white bg-orange-500 hover:bg-orange-600 rounded-xl font-bold transition-colors shadow-md text-lg"
            >
              <Save size={20} />
              レシピを保存する
            </button>
          </div>
        </form>
      </main>
    </div>
  )
}
