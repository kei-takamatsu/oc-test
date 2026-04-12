require('dotenv').config({ path: '../.env' });
const { createClient } = require('@supabase/supabase-js');

const supabaseUrl = process.env.SUPABASE_URL || 'https://dehievtwhwvxcqwyouhy.supabase.co';
const supabaseKey = process.env.SUPABASE_ANON_KEY || 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImRlaGlldnR3aHd2eGNxd3lvdWh5Iiwicm9sZSI6ImYubm9uIiwiaWF0IjoxNzc1OTI1MTc0LCJleHAiOjIwOTE1MDExNzR9.j-pLzU_6oFfDukNLJKMDtBRqI8WUu7mMbQLDqQiZ9MA';

// Using Service Role Key if available to bypass RLS for debugging, 
// but I only have Anon Key in .env probably.
const supabase = createClient(supabaseUrl, supabaseKey);

async function checkData() {
  console.log('Checking recipes table...');
  const { data, error } = await supabase
    .from('recipes')
    .select('id, title, user_id, sort_order')
    .limit(5);

  if (error) {
    console.error('Error fetching data:', error);
    return;
  }

  console.log('Fetched rows:', data.length);
  data.forEach(row => {
    console.log(`ID: ${row.id}, Title: ${row.title}, UserID: ${row.user_id}, SortOrder: ${row.sort_order}`);
  });
}

checkData();
