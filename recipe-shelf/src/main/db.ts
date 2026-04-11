import Database from 'better-sqlite3'
import { app } from 'electron'
import { join } from 'path'
import { mkdirSync } from 'fs'

const userDataPath = app.getPath('userData')
const dbPath = join(userDataPath, 'recipes.sqlite')
const imagesPath = join(userDataPath, 'recipe_images')

// Ensure images directory exists
mkdirSync(imagesPath, { recursive: true })

const db = new Database(dbPath)

// Initialize schema
db.exec(`
  CREATE TABLE IF NOT EXISTS recipes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    sourceUrl TEXT,
    imageUrl TEXT,
    imageLocalPath TEXT,
    ingredients TEXT, -- JSON
    instructions TEXT, -- JSON
    description TEXT,
    prepTime TEXT,
    cookTime TEXT,
    servings TEXT,
    rating INTEGER DEFAULT 0,
    notes TEXT,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP
  );

  CREATE TABLE IF NOT EXISTS settings (
    key TEXT PRIMARY KEY,
    value TEXT
  );
`)

export interface Recipe {
  id?: number
  title: string
  sourceUrl?: string
  imageUrl?: string
  imageLocalPath?: string
  ingredients: string // stringified JSON
  instructions: string // stringified JSON
  description?: string
  prepTime?: string
  cookTime?: string
  servings?: string
  rating?: number
  notes?: string
  createdAt?: string
}

export const dbService = {
  getAllRecipes: (): Recipe[] => {
    return db.prepare('SELECT * FROM recipes ORDER BY createdAt DESC').all() as Recipe[]
  },

  getRecipeById: (id: number): Recipe | undefined => {
    return db.prepare('SELECT * FROM recipes WHERE id = ?').get(id) as Recipe | undefined
  },

  addRecipe: (recipe: Recipe): number | bigint => {
    const info = db.prepare(`
      INSERT INTO recipes (
        title, sourceUrl, imageUrl, imageLocalPath, ingredients, 
        instructions, description, prepTime, cookTime, servings, rating, notes
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    `).run(
      recipe.title,
      recipe.sourceUrl,
      recipe.imageUrl,
      recipe.imageLocalPath,
      recipe.ingredients,
      recipe.instructions,
      recipe.description,
      recipe.prepTime,
      recipe.cookTime,
      recipe.servings,
      recipe.rating || 0,
      recipe.notes
    )
    return info.lastInsertRowid
  },

  updateRecipe: (id: number, recipe: Partial<Recipe>): void => {
    const keys = Object.keys(recipe).filter(k => k !== 'id')
    const sets = keys.map(k => `${k} = ?`).join(', ')
    const values = keys.map(k => (recipe as any)[k])
    
    db.prepare(`UPDATE recipes SET ${sets} WHERE id = ?`).run(...values, id)
  },

  deleteRecipe: (id: number): void => {
    db.prepare('DELETE FROM recipes WHERE id = ?').run(id)
  },

  getSetting: (key: string): string | undefined => {
    const row = db.prepare('SELECT value FROM settings WHERE key = ?').get(key) as { value: string } | undefined
    return row?.value
  },

  setSetting: (key: string, value: string): void => {
    db.prepare(`
      INSERT INTO settings (key, value) VALUES (?, ?)
      ON CONFLICT(key) DO UPDATE SET value = excluded.value
    `).run(key, value)
  },

  deleteSetting: (key: string): void => {
    db.prepare('DELETE FROM settings WHERE key = ?').run(key)
  }
}

export { imagesPath }
