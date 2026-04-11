import { createClient } from '@supabase/supabase-js'
import * as dotenv from 'dotenv'
import { join } from 'path'
import { app } from 'electron'
import { dbService } from './db'

// Load .env variables
dotenv.config({ path: join(app.getAppPath(), '../../.env') })
// app.getAppPath() points to out/main usually, so we might need to fallback
const supabaseUrl = process.env.SUPABASE_URL || 'https://dehievtwhwvxcqwyouhy.supabase.co'
const supabaseKey = process.env.SUPABASE_ANON_KEY || 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImRlaGlldnR3aHd2eGNxd3lvdWh5Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzU5MjUxNzQsImV4cCI6MjA5MTUwMTE3NH0.j-pLzU_6oFfDukNLJKMDtBRqI8WUu7mMbQLDqQiZ9MA'

const customStorage = {
  getItem: (key: string) => {
    return dbService.getSetting(key) || null
  },
  setItem: (key: string, value: string) => {
    dbService.setSetting(key, value)
  },
  removeItem: (key: string) => {
    dbService.deleteSetting(key)
  }
}

export const supabase = createClient(supabaseUrl, supabaseKey, {
  auth: {
    persistSession: true,
    autoRefreshToken: true,
    storageKey: 'recipe-shelf-auth',
    storage: customStorage
  }
})

// Auth Helpers
export const authService = {
  signIn: async (email: string, password: string) => {
    const { data, error } = await supabase.auth.signInWithPassword({ email, password })
    if (error) throw error
    return data
  },
  signUp: async (email: string, password: string) => {
    const { data, error } = await supabase.auth.signUp({ email, password })
    if (error) throw error
    return data
  },
  signOut: async () => {
    const { error } = await supabase.auth.signOut()
    if (error) throw error
  },
  getSession: async () => {
    // まずストレージから復元されたセッションを取得
    const { data: { session } } = await supabase.auth.getSession()

    if (session) {
      // アクセストークンの有効期限を確認し、切れていたらリフレッシュ
      const expiresAt = session.expires_at // UNIX timestamp (seconds)
      const now = Math.floor(Date.now() / 1000)
      if (expiresAt && now >= expiresAt - 60) {
        // 期限切れ or 1分以内に切れる → リフレッシュ
        console.log('[Auth] Access token expired, refreshing...')
        const { data, error } = await supabase.auth.refreshSession({
          refresh_token: session.refresh_token
        })
        if (error) {
          console.error('[Auth] Refresh failed:', error.message)
          return null // リフレッシュ失敗 = 再ログインを促す
        }
        console.log('[Auth] Session refreshed successfully')
        return data.session
      }
      return session
    }

    return null
  }
}

// Storage Helpers
import { readFileSync } from 'fs'
export const storageService = {
  uploadImage: async (localFilePath: string, filename: string): Promise<string> => {
    const buffer = readFileSync(localFilePath)
    const { data: { session } } = await supabase.auth.getSession()
    if (!session) throw new Error("Must be logged in to upload images")
    
    const filePath = `${session.user.id}/${filename}`
    const { error } = await supabase.storage.from('recipe-images').upload(filePath, buffer, {
      contentType: 'image/jpeg',
      upsert: true
    })
    
    if (error) throw error
    const { data } = supabase.storage.from('recipe-images').getPublicUrl(filePath)
    return data.publicUrl
  }
}
