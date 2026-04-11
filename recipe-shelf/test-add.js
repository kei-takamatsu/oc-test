require('dotenv').config()
const { createClient } = require('@supabase/supabase-js')

const supabaseUrl = process.env.SUPABASE_URL || 'https://dehievtwhwvxcqwyouhy.supabase.co'
const supabaseKey = process.env.SUPABASE_ANON_KEY || 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImRlaGlldnR3aHd2eGNxd3lvdWh5Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzU5MjUxNzQsImV4cCI6MjA5MTUwMTE3NH0.j-pLzU_6oFfDukNLJKMDtBRqI8WUu7mMbQLDqQiZ9MA'

const supabase = createClient(supabaseUrl, supabaseKey)

async function run() {
  // Try authenticating
  const { data: { session }, error: loginErr } = await supabase.auth.signInWithPassword({
    email: 'test@example.com', // user's dummy or let's just use some random or sign up a new one
    password: 'password123'
  })
  if (loginErr) {
    console.log('Login failed:', loginErr.message)
    // Try sign up
    const { data: d2, error: e2 } = await supabase.auth.signUp({ email: 'test2@test.com', password: 'password123' })
    if (e2) return console.error('SignUp failed:', e2)
    console.log('Signed up test2@test.com')
  }

  const { data: session2 } = await supabase.auth.getSession()
  const uid = session?.user?.id || session2?.session?.user?.id
  console.log('UID:', uid)

  const insertData = {
    user_id: uid,
    title: 'Test Recipe',
    ingredients: [],
    instructions: []
  }

  const { data, error } = await supabase.from('recipes').insert(insertData).select('id').single()
  if (error) {
    console.error('Insert Error:', error)
  } else {
    console.log('Inserted ID:', data.id, typeof data.id)
  }
}

run()
