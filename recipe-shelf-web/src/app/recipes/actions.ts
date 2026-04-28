'use server'

import { createClient } from '@/utils/supabase/server'
import { revalidatePath } from 'next/cache'

export async function updateRecipeOrder(orderedIds: number[]) {
  const supabase = await createClient()

  // ログイン確認
  const { data: { user } } = await supabase.auth.getUser()
  if (!user) {
    throw new Error('Unauthorized')
  }

  // update all items with their new index
  const updates = orderedIds.map((id, index) =>
    supabase.from('recipes').update({ sort_order: index }).eq('id', id).eq('user_id', user.id)
  )

  const results = await Promise.all(updates)
  for (const { error } of results) {
    if (error) {
      console.error('Failed to update sort order on Web', error)
      throw new Error(error.message)
    }
  }

  // Next.js キャッシュのパージ
  revalidatePath('/recipes')
}

export async function deleteRecipe(recipeId: number) {
  const supabase = await createClient()

  const { data: { user } } = await supabase.auth.getUser()
  if (!user) {
    throw new Error('Unauthorized')
  }

  // 画像のパスを先に取得してStorageからも削除
  const { data: recipe } = await supabase
    .from('recipes')
    .select('image_local_path')
    .eq('id', recipeId)
    .eq('user_id', user.id)
    .single()

  if (recipe?.image_local_path) {
    try {
      // image_local_path がSupabase StorageのURL形式の場合パスを抽出
      const url = new URL(recipe.image_local_path)
      const storagePath = url.pathname.split('/recipe-images/').pop()
      if (storagePath) {
        await supabase.storage.from('recipe-images').remove([storagePath])
      }
    } catch {
      // URL解析失敗は無視
    }
  }

  const { error } = await supabase
    .from('recipes')
    .delete()
    .eq('id', recipeId)
    .eq('user_id', user.id)

  if (error) {
    console.error('Failed to delete recipe:', error)
    throw new Error(error.message)
  }

  revalidatePath('/recipes')
}
