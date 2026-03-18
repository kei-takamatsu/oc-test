-- ユーザープロファイル（名前と履歴を管理）
CREATE TABLE IF NOT EXISTS public.user_profiles (
  id uuid PRIMARY KEY REFERENCES auth.users ON DELETE CASCADE,
  display_name text,
  history jsonb DEFAULT '{}'::jsonb,
  updated_at timestamp with time zone DEFAULT now()
);

-- 日ごとのタスクデータ
CREATE TABLE IF NOT EXISTS public.user_tasks (
  id uuid DEFAULT gen_random_uuid() PRIMARY KEY,
  user_id uuid REFERENCES auth.users ON DELETE CASCADE NOT NULL,
  date date NOT NULL,
  tasks jsonb NOT NULL, -- [{id, text, icon, time, done}]
  updated_at timestamp with time zone DEFAULT now(),
  UNIQUE(user_id, date)
);

-- RLS (Row Level Security) の有効化
ALTER TABLE public.user_profiles ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.user_tasks ENABLE ROW LEVEL SECURITY;

-- プロファイル用のポリシー
CREATE POLICY "Users can view own profile" ON public.user_profiles FOR SELECT USING (auth.uid() = id);
CREATE POLICY "Users can update own profile" ON public.user_profiles FOR UPDATE USING (auth.uid() = id);
CREATE POLICY "Users can insert own profile" ON public.user_profiles FOR INSERT WITH CHECK (auth.uid() = id);

-- タスク用のポリシー
CREATE POLICY "Users can view own tasks" ON public.user_tasks FOR SELECT USING (auth.uid() = user_id);
CREATE POLICY "Users can insert own tasks" ON public.user_tasks FOR INSERT WITH CHECK (auth.uid() = user_id);
CREATE POLICY "Users can update own tasks" ON public.user_tasks FOR UPDATE USING (auth.uid() = user_id);

-- 更新日時を自動で更新する関数とトリガー（オプション）
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = now();
    RETURN NEW;
END;
$$ language 'plpgsql';

CREATE TRIGGER update_user_profiles_updated_at BEFORE UPDATE ON public.user_profiles FOR EACH ROW EXECUTE PROCEDURE update_updated_at_column();
CREATE TRIGGER update_user_tasks_updated_at BEFORE UPDATE ON public.user_tasks FOR EACH ROW EXECUTE PROCEDURE update_updated_at_column();
