// Trigger redeploy with new secrets
import { createClient, type SupabaseClient } from '@supabase/supabase-js';

// Supabase設定（GitHub Pagesなどのデプロイ環境用にハードコード）
const supabaseUrl = import.meta.env.VITE_SUPABASE_URL || 'https://aeiksrzfcbozwfxsnwkq.supabase.co';
const supabaseAnonKey = import.meta.env.VITE_SUPABASE_ANON_KEY || 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImFlaWtzcnpmY2JvendmeHNud2txIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzM4MDM4MjQsImV4cCI6MjA4OTM3OTgyNH0.HYwpoT6fr-nR8xpsphECGymMdHxGjGZe4gyqCC5PHhA';

// モックモード（URL未設定時）の判定
export const isMockMode = !supabaseUrl || !supabaseAnonKey;

// URLがある場合のみクライアントを初期化、ない場合は null またはモックを置く
export const supabase: SupabaseClient = createClient(supabaseUrl, supabaseAnonKey);
