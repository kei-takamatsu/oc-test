const { createClient } = require('@supabase/supabase-js');
// Read from .env.local
const fs = require('fs');
const envFile = fs.readFileSync('/Users/takamatsukei/oc-test/recipe-shelf-web/.env.local', 'utf8');
const lines = envFile.split('\n');
let url = '';
let key = '';
for (const line of lines) {
  if (line.startsWith('NEXT_PUBLIC_SUPABASE_URL=')) url = line.split('=')[1].replace(/"/g, '');
  if (line.startsWith('NEXT_PUBLIC_SUPABASE_ANON_KEY=')) key = line.split('=')[1].replace(/"/g, '');
}

console.log('URL:', url);
console.log('Key prefix:', key.substring(0, 15));

const supabase = createClient(url, key);

async function testFetch() {
  const { data, error } = await supabase
    .from('recipes')
    .select('*')
    .order('sort_order', { ascending: true })
    .order('created_at', { ascending: false });

  console.log('Error:', error);
  console.log('Data length:', data ? data.length : null);
}

testFetch();
