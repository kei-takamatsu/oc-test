'use server'

import { createClient } from '@/utils/supabase/server'
import { revalidatePath } from 'next/cache'
import { redirect } from 'next/navigation'

export async function addRecipe(formData: FormData) {
  const supabase = await createClient()

  // セッションの確認
  const { data: { user } } = await supabase.auth.getUser()
  if (!user) {
    redirect('/?error=You must be logged in')
  }

  const title = formData.get('title') as string
  const imageUrl = formData.get('imageUrl') as string
  const prepTime = formData.get('prepTime') as string
  const cookTime = formData.get('cookTime') as string
  const servings = formData.get('servings') as string
  
  // テキストエリアから改行で分割し、空行を除去して配列化
  const ingredientsRaw = formData.get('ingredients') as string
  const ingredients = ingredientsRaw
    .split('\n')
    .map(s => s.trim())
    .filter(s => s.length > 0)
    
  const instructionsRaw = formData.get('instructions') as string
  const instructions = instructionsRaw
    .split('\n')
    .map(s => s.trim())
    .filter(s => s.length > 0)

  // DB挿入用データ作成
  const recipeData = {
    user_id: user.id,
    title: title,
    image_local_path: imageUrl || null,
    prep_time: prepTime || null,
    cook_time: cookTime || null,
    servings: servings || null,
    ingredients: JSON.stringify(ingredients), // 文字列配列のJSON化
    instructions: JSON.stringify(instructions), // 文字列配列のJSON化
    source_url: null,
    notes: null
  }

  const { error } = await supabase
    .from('recipes')
    .insert(recipeData)

  if (error) {
    console.error('Error adding recipe:', error)
    redirect('/recipes/new?error=レシピの保存に失敗しました')
  }

  revalidatePath('/recipes')
  revalidatePath('/recipes')
  redirect('/recipes')
}

export async function addRecipeFromUrl(formData: FormData) {
  const supabase = await createClient()

  const { data: { user } } = await supabase.auth.getUser()
  if (!user) {
    redirect('/?error=You must be logged in')
  }

  const url = formData.get('url') as string
  if (!url) return

  const scraperApiUrl = process.env.SCRAPER_API_URL
  const scraperApiSecret = process.env.SCRAPER_API_SECRET

  // スクレイピングAPIサーバーが設定されていれば直接呼ぶ
  if (scraperApiUrl && scraperApiSecret) {
    try {
      const res = await fetch(`${scraperApiUrl}/api/scrape`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${scraperApiSecret}`
        },
        body: JSON.stringify({ url, userId: user.id })
      })

      if (!res.ok) {
        const err = await res.json().catch(() => ({ error: 'Unknown error' }))
        throw new Error(err.error || `API returned ${res.status}`)
      }

      // 成功 → レシピ一覧にリダイレクト
      revalidatePath('/recipes')
      redirect('/recipes')
    } catch (e) {
      console.error('[Scraper API] Error, falling back to PENDING_SCRAPE:', e)
      // フォールスルー: APIが使えない場合はPCアプリに任せる
    }
  }

  // フォールバック: PCアプリに任せる保留レコード
  const recipeData = {
    user_id: user.id,
    title: '🌐自動取得を待機中...',
    source_url: url,
    description: 'PCアプリを起動すると、裏側で自動的にレシピ内容と画像が抽出されます！',
    notes: '[PENDING_SCRAPE]',
    image_local_path: null,
    ingredients: JSON.stringify(['(自動取得されます)']),
    instructions: JSON.stringify(['(自動取得されます)'])
  }

  const { error } = await supabase
    .from('recipes')
    .insert(recipeData)

  if (error) {
    console.error('Error adding pending url recipe:', error)
    redirect('/recipes/new?error=URLの登録に失敗しました')
  }

  revalidatePath('/recipes')
  redirect('/recipes')
}

