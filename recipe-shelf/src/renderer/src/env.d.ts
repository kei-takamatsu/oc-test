interface Recipe {
  id?: number
  title: string
  sourceUrl?: string
  imageUrl?: string
  imageLocalPath?: string
  ingredients: string // JSON
  instructions: string // JSON
  description?: string
  prepTime?: string
  cookTime?: string
  servings?: string
  rating?: number
  notes?: string
  sortOrder?: number
  createdAt?: string
}

interface Window {
  api: {
    getAllRecipes: () => Promise<Recipe[]>
    getRecipe: (id: number) => Promise<Recipe>
    addRecipeFromUrl: (url: string) => Promise<Recipe>
    addRecipeManual: (recipe: any) => Promise<Recipe>
    updateRecipe: (id: number, recipe: any) => Promise<Recipe>
    deleteRecipe: (id: number) => Promise<void>
    reorderRecipes: (orderedIds: number[]) => Promise<void>
    getSetting: (key: string) => Promise<string | undefined>
    saveSetting: (key: string, value: string) => Promise<void>
    addRecipeFromText: (text: string, dataUrl?: string) => Promise<Recipe>
    openLoginWindow: (url: string) => Promise<void>
    supaLogin: (email: string, pw: string) => Promise<any>
    supaSignup: (email: string, pw: string) => Promise<any>
    supaLogout: () => Promise<void>
    supaGetSession: () => Promise<any>
    migrateRecipes: () => Promise<number | false>
  }
}
