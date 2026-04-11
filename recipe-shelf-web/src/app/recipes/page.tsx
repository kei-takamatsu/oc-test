import { createClient } from '@/utils/supabase/server'
import Link from 'next/link'
import { LogOut, Search, Clock, Users } from 'lucide-react'
import { logout } from '../login/actions'

export default async function RecipesPage() {
  const supabase = await createClient()

  const { data: recipes } = await supabase
    .from('recipes')
    .select('id, title, image_local_path, prep_time, cook_time, servings')
    .order('created_at', { ascending: false })

  return (
    <div className="min-h-screen bg-gray-50 pb-20">
      {/* App Bar */}
      <header className="sticky top-0 z-10 bg-white border-b border-gray-200 px-4 py-3 flex items-center justify-between shadow-sm">
        <h1 className="text-xl font-bold text-gray-900">RecipeShelf</h1>
        <form action={logout}>
          <button className="p-2 text-gray-500 hover:text-gray-900 transition-colors">
            <LogOut size={20} />
          </button>
        </form>
      </header>

      {/* Main Content */}
      <main className="p-4 max-w-3xl mx-auto space-y-6 mt-4">
        {/* Search Bar - Visual only for now */}
        <div className="relative">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" size={20} />
          <input 
            type="text" 
            placeholder="レシピを探す..." 
            className="w-full pl-10 pr-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all shadow-sm"
          />
        </div>

        {/* Recipe Grid */}
        <div className="grid grid-cols-2 sm:grid-cols-3 gap-4">
          {recipes?.map((recipe) => (
            <Link key={recipe.id} href={`/recipes/${recipe.id}`} className="group block">
              <div className="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div className="aspect-square relative overflow-hidden bg-gray-100">
                  <img
                    src={recipe.image_local_path || 'https://images.unsplash.com/photo-1495521821757-a1efb6729352?auto=format&fit=crop&w=400&q=80'}
                    alt={recipe.title}
                    className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                  />
                </div>
                <div className="p-3">
                  <h3 className="font-semibold text-gray-900 leading-tight line-clamp-2 text-sm">{recipe.title}</h3>
                  <div className="mt-2 flex items-center gap-3 text-xs text-gray-500">
                    {(recipe.prep_time || recipe.cook_time) && (
                      <div className="flex items-center gap-1">
                        <Clock size={12} />
                        <span>{recipe.prep_time || recipe.cook_time}</span>
                      </div>
                    )}
                  </div>
                </div>
              </div>
            </Link>
          ))}
          {(!recipes || recipes.length === 0) && (
            <div className="col-span-full py-12 text-center text-gray-500">
              <p>レシピがありません。</p>
              <p className="text-sm mt-1">PCアプリからレシピをスクレイピングして追加してください。</p>
            </div>
          )}
        </div>
      </main>
    </div>
  )
}
