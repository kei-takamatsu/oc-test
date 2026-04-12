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
