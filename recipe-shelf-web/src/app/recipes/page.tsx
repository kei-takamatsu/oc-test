import { createClient } from '@/utils/supabase/server'
import Link from 'next/link'
import { LogOut, Clock } from 'lucide-react'
import { logout } from '../login/actions'
import SearchInput from './SearchInput'
import SortableRecipeGrid from './SortableRecipeGrid'

export default async function RecipesPage({ searchParams }: { searchParams: Promise<{ [key: string]: string | string[] | undefined }> }) {
  const supabase = await createClient()

  const { data: { user } } = await supabase.auth.getUser()

  const params = await searchParams
  const q = typeof params.q === 'string' ? params.q : ''

  // ベースクエリの構築
  let baseQuery = supabase.from('recipes').select('*')
  if (q) {
    baseQuery = baseQuery.ilike('title', `%${q}%`) // タイトルで部分一致検索
  }

  let { data: recipes, error } = await baseQuery
    .order('sort_order', { ascending: true })
    .order('created_at', { ascending: false })
  
  if (error && error.code === '42703') {
    // sort_order column does not exist yet!
    let fallbackQuery = supabase.from('recipes').select('*')
    if (q) {
      fallbackQuery = fallbackQuery.ilike('title', `%${q}%`)
    }
    const fallback = await fallbackQuery.order('created_at', { ascending: false })
    recipes = fallback.data
    error = fallback.error
  }

  return (
    <div className="min-h-screen bg-gray-50 pb-20">
      {/* App Bar */}
      <header className="sticky top-0 z-10 bg-white border-b border-gray-200 px-4 py-3 flex items-center justify-between shadow-sm">
        <h1 className="text-xl font-bold text-gray-900">RecipeShelf</h1>
        <div className="flex items-center gap-2">
          <Link href="/recipes/new" className="px-3 py-1.5 text-sm font-medium text-white bg-orange-500 hover:bg-orange-600 rounded-lg transition-colors">
            + 追加
          </Link>
          <form action={logout}>
            <button className="p-2 text-gray-500 hover:text-gray-900 transition-colors" title="ログアウト">
              <LogOut size={20} />
            </button>
          </form>
        </div>
      </header>

      {/* Main Content */}
      <main className="p-4 max-w-3xl mx-auto space-y-6 mt-4">
        <SearchInput />

        {/* Recipe Grid (Sortable) */}
        <SortableRecipeGrid initialRecipes={recipes || []} />
      </main>
    </div>
  )
}
