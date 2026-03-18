import { createClient, type SupabaseClient } from '@supabase/supabase-js';

const supabaseUrl = import.meta.env.VITE_SUPABASE_URL || '';
const supabaseAnonKey = import.meta.env.VITE_SUPABASE_ANON_KEY || '';

// モックモード（URL未設定時）の判定
export const isMockMode = !supabaseUrl || !supabaseAnonKey;

// URLがある場合のみクライアントを初期化、ない場合は null またはモックを置く
export const supabase = !isMockMode 
  ? createClient(supabaseUrl, supabaseAnonKey) 
  : (null as SupabaseClient | null);
