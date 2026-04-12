import { supabase } from './supabase'
import { Recipe } from './db'

// Helper to convert snake_case from DB to camelCase Recipe interface
function toCamel(row: any): Recipe {
  return {
    id: row.id,
    title: row.title,
    sourceUrl: row.source_url,
    imageUrl: row.image_url,
    imageLocalPath: row.image_local_path,
    ingredients: typeof row.ingredients === 'string' ? row.ingredients : JSON.stringify(row.ingredients || []),
    instructions: typeof row.instructions === 'string' ? row.instructions : JSON.stringify(row.instructions || []),
    description: row.description,
    prepTime: row.prep_time,
    cookTime: row.cook_time,
    servings: row.servings,
    rating: row.rating,
    notes: row.notes,
    sortOrder: row.sort_order ?? 0,
    createdAt: row.created_at
  }
}

// Helper to convert camelCase to snake_case for Supabase insertion
function toSnake(recipe: Partial<Recipe>, userId: string): any {
  return {
    user_id: userId,
    title: recipe.title,
    source_url: recipe.sourceUrl,
    image_url: recipe.imageUrl,
    image_local_path: recipe.imageLocalPath,
    ingredients: recipe.ingredients ? JSON.parse(recipe.ingredients) : [],
    instructions: recipe.instructions ? JSON.parse(recipe.instructions) : [],
    description: recipe.description,
    prep_time: recipe.prepTime,
    cook_time: recipe.cookTime,
    servings: recipe.servings,
    rating: recipe.rating || 0,
    notes: recipe.notes,
    sort_order: recipe.sortOrder ?? 0
  }
}

export const cloudDbService = {
  getAllRecipes: async (): Promise<Recipe[]> => {
    // sort_orderカラムが存在しない場合に備えてフォールバック
    let result = await supabase
      .from('recipes')
      .select('*')
      .order('sort_order', { ascending: true })
      .order('created_at', { ascending: false })
    
    if (result.error) {
      console.warn('sort_order query failed, falling back:', result.error.message)
      // sort_orderカラムが無い場合はcreated_atのみでソート
      result = await supabase
        .from('recipes')
        .select('*')
        .order('created_at', { ascending: false })
    }
    
    if (result.error) {
      console.error('Failed to fetch recipes from Supabase:', result.error)
      return []
    }
    return (result.data || []).map(toCamel)
  },

  getRecipeById: async (id: number): Promise<Recipe | undefined> => {
    const { data, error } = await supabase
      .from('recipes')
      .select('*')
      .eq('id', id)
      .single()
    
    if (error || !data) return undefined
    return toCamel(data)
  },

  addRecipe: async (recipe: Recipe): Promise<number | undefined> => {
    const { data: { session } } = await supabase.auth.getSession()
    if (!session) throw new Error('You must be logged in to add a recipe.')

    const insertData = toSnake(recipe, session.user.id)
    const { data, error } = await supabase
      .from('recipes')
      .insert(insertData)
      .select('id')
      .single()

    if (error) {
      console.error('Failed to insert recipe:', error)
      throw new Error(error.message)
    }
    return data?.id
  },

  updateRecipe: async (id: number, recipe: Partial<Recipe>): Promise<void> => {
    const { data: { session } } = await supabase.auth.getSession()
    if (!session) throw new Error('Not authenticated')

    const updateData = toSnake(recipe, session.user.id)
    // we don't want to accidentally change user_id or id
    delete updateData.user_id
    delete updateData.id

    const { error } = await supabase
      .from('recipes')
      .update(updateData)
      .eq('id', id)

    if (error) throw new Error(error.message)
  },

  deleteRecipe: async (id: number): Promise<void> => {
    const { error } = await supabase
      .from('recipes')
      .delete()
      .eq('id', id)

    if (error) throw new Error(error.message)
  },

  reorderRecipes: async (orderedIds: number[]): Promise<void> => {
    // 各レシピのsort_orderを配列のインデックスで一括更新
    const updates = orderedIds.map((id, index) =>
      supabase.from('recipes').update({ sort_order: index }).eq('id', id)
    )
    const results = await Promise.all(updates)
    for (const { error } of results) {
      if (error) throw new Error(error.message)
    }
  }
}
