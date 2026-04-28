import { createClient } from '@/utils/supabase/server'
import { notFound } from 'next/navigation'
import Link from 'next/link'
import { ChevronLeft, Clock, Users, Link as LinkIcon, ChefHat } from 'lucide-react'
import DeleteButton from './DeleteButton'

export default async function RecipeDetailPage({ params }: { params: Promise<{ id: string }> }) {
  const resolvedParams = await params
  const supabase = await createClient()

  const { data: recipe, error } = await supabase
    .from('recipes')
    .select('*')
    .eq('id', resolvedParams.id)
    .single()
    
  if (error) {
    console.error('Fetch recipe error:', error)
  }

  if (!recipe) {
    notFound()
  }

  // Handle JSON fields
  let ingredients = []
  let instructions = []
  try {
    ingredients = typeof recipe.ingredients === 'string' ? JSON.parse(recipe.ingredients) : recipe.ingredients || []
    instructions = typeof recipe.instructions === 'string' ? JSON.parse(recipe.instructions) : recipe.instructions || []
  } catch (e) {
    console.error('Failed to parse JSON', e)
  }

  return (
    <div className="min-h-screen bg-white pb-24">
      {/* Top Nav (Floating) */}
      <div className="absolute top-0 w-full z-10 p-4 flex items-center justify-between">
        <Link href="/recipes" className="inline-flex items-center justify-center w-10 h-10 bg-black/30 backdrop-blur-md rounded-full text-white hover:bg-black/50 transition-colors">
          <ChevronLeft size={24} />
        </Link>
        <DeleteButton recipeId={recipe.id} />
      </div>

      {/* Hero Image */}
      <div className="w-full aspect-[4/3] md:aspect-[21/9] bg-gray-100 relative">
        <img
          src={recipe.image_local_path || 'https://images.unsplash.com/photo-1495521821757-a1efb6729352?auto=format&fit=crop&w=800&q=80'}
          className="w-full h-full object-cover"
          alt={recipe.title}
        />
        <div className="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
      </div>

      <main className="max-w-3xl mx-auto px-4 -mt-8 relative z-10">
        <div className="bg-white rounded-2xl shadow-lg p-6 md:p-8">
          <h1 className="text-2xl md:text-3xl font-bold text-gray-900 leading-tight">{recipe.title}</h1>
          
          <div className="flex flex-wrap items-center gap-4 mt-4 text-sm font-medium text-gray-600">
            {recipe.prep_time && (
              <div className="flex items-center gap-1.5 bg-orange-50 text-orange-700 px-3 py-1.5 rounded-full">
                <Clock size={16} />
                <span>準備: {recipe.prep_time}</span>
              </div>
            )}
            {recipe.cook_time && (
              <div className="flex items-center gap-1.5 bg-orange-50 text-orange-700 px-3 py-1.5 rounded-full">
                <ChefHat size={16} />
                <span>調理: {recipe.cook_time}</span>
              </div>
            )}
            {recipe.servings && (
              <div className="flex items-center gap-1.5 bg-blue-50 text-blue-700 px-3 py-1.5 rounded-full">
                <Users size={16} />
                <span>{recipe.servings}</span>
              </div>
            )}
          </div>

          {recipe.description && (
            <p className="mt-6 text-gray-600 leading-relaxed text-sm md:text-base">
              {recipe.description}
            </p>
          )}

          {recipe.source_url && (
            <a href={recipe.source_url} target="_blank" rel="noopener noreferrer" className="mt-4 inline-flex items-center gap-1.5 text-sm font-medium text-orange-500 hover:text-orange-600">
              <LinkIcon size={16} />
              元のレシピを見る
            </a>
          )}
        </div>

        <div className="mt-8 space-y-12">
          {/* Ingredients */}
          <section>
            <h2 className="text-xl font-bold text-gray-900 flex items-center gap-2 border-b-2 border-orange-100 pb-2 mb-4">
              材料
            </h2>
            <ul className="space-y-3">
              {ingredients.map((item: any, i: number) => (
                <li key={i} className="flex justify-between items-center py-2 border-b border-gray-100 last:border-0 hover:bg-gray-50 px-2 rounded transition-colors">
                  {typeof item === 'object' && item !== null ? (
                    <>
                      <span className="text-gray-800">{item.name}</span>
                      <span className="font-medium text-gray-600">{item.amount}</span>
                    </>
                  ) : (
                    <span className="text-gray-800">{item}</span>
                  )}
                </li>
              ))}
            </ul>
          </section>

          {/* Instructions */}
          <section>
            <h2 className="text-xl font-bold text-gray-900 flex items-center gap-2 border-b-2 border-orange-100 pb-2 mb-4">
              作り方
            </h2>
            <div className="space-y-6">
              {instructions.map((step: string, i: number) => (
                <div key={i} className="flex gap-4">
                  <div className="flex-shrink-0 w-8 h-8 flex items-center justify-center bg-orange-100 text-orange-600 font-bold rounded-full text-sm">
                    {i + 1}
                  </div>
                  <p className="text-gray-700 leading-relaxed pt-1 flex-1">
                    {step}
                  </p>
                </div>
              ))}
            </div>
          </section>

          {/* Notes */}
          {recipe.notes && (
            <section className="bg-yellow-50 rounded-xl p-5 border border-yellow-100">
              <h2 className="font-bold text-yellow-800 mb-2">メモ・コツ</h2>
              <p className="text-yellow-900 text-sm leading-relaxed whitespace-pre-wrap">
                {recipe.notes}
              </p>
            </section>
          )}
        </div>
      </main>
    </div>
  )
}
