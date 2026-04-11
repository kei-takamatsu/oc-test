const { app } = require('electron')
// We can't easily mock electron app for Node, so we'll just test the logic directly using better-sqlite3

const Database = require('better-sqlite3')
const db = new Database('/Users/takamatsukei/Library/Application Support/recipe-shelf/recipes.sqlite')
function getSetting(key) {
  const row = db.prepare('SELECT value FROM settings WHERE key = ?').get(key)
  return row?.value || null
}

const { createClient } = require('@supabase/supabase-js')
const supabase = createClient(
  process.env.SUPABASE_URL || 'https://dehievtwhwvxcqwyouhy.supabase.co',
  process.env.SUPABASE_ANON_KEY || 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImRlaGlldnR3aHd2eGNxd3lvdWh5Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzU5MjUxNzQsImV4cCI6MjA5MTUwMTE3NH0.j-pLzU_6oFfDukNLJKMDtBRqI8WUu7mMbQLDqQiZ9MA',
  {
    auth: {
      persistSession: true,
      autoRefreshToken: true,
      storageKey: 'recipe-shelf-auth',
      storage: {
        getItem: key => {
           console.log('getItem called for', key);
           return getSetting(key)
        },
        setItem: () => {},
        removeItem: () => {}
      }
    }
  }
)

async function test() {
  const { data, error } = await supabase.auth.getSession()
  console.log('Session user ID:', data?.session?.user?.id, 'Error:', error)
}
test()
