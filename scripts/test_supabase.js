import { createClient } from '@supabase/supabase-js';
import dotenv from 'dotenv';
import path from 'path';

dotenv.config({ path: '/Users/takamatsukei/oc-test/oshitaku-app/.env' });

const url = process.env.VITE_SUPABASE_URL;
const key = process.env.VITE_SUPABASE_ANON_KEY;

console.log('Testing with URL:', url);
// key's first 10 chars for safety
console.log('Key prefix:', key?.substring(0, 10));

if (!url || !key) {
    console.error('Missing URL or Key');
    process.exit(1);
}

const supabase = createClient(url, key);

try {
    const { data, error } = await supabase.auth.getSession();
    if (error) {
        console.error('Supabase error:', error.message);
    } else {
        console.log('Successfully connected to Supabase!');
    }
} catch (e) {
    console.error('Unexpected error:', e);
}
