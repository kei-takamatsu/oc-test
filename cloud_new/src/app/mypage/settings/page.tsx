import { auth } from "@/auth";
import { prisma } from "@/lib/prisma";
import { redirect } from "next/navigation";
import { User, MapPin, Share2, Save } from "lucide-react";
import { updateProfileAction } from "@/lib/actions/profile-actions";

export default async function SettingsPage() {
  const session = await auth();
  if (!session?.user?.email) redirect("/login");

  const user = await prisma.user.findUnique({
    where: { email: session.user.email },
  });

  if (!user) redirect("/login");

  return (
    <div className="container mx-auto px-4 py-16 max-w-3xl">
      <div className="space-y-8">
        <div className="flex items-center gap-4 border-b border-white/5 pb-8">
          <div className="p-3 bg-primary/10 text-primary rounded-2xl">
            <User size={32} />
          </div>
          <div>
            <h1 className="text-3xl font-bold">プロフィール設定</h1>
            <p className="text-slate-500">あなたの公開情報を編集します。</p>
          </div>
        </div>

        <form action={updateProfileAction} className="space-y-12">
          {/* 基本情報 */}
          <section className="space-y-6">
            <h2 className="text-xl font-bold flex items-center gap-2">
              <User size={20} className="text-indigo-400" /> 基本情報
            </h2>
            <div className="grid grid-cols-1 gap-6">
              <div className="space-y-2">
                <label className="text-sm font-bold text-slate-400">ニックネーム</label>
                <input 
                  name="nickname"
                  defaultValue={user.nickname}
                  required
                  className="w-full px-6 py-4 rounded-2xl bg-white/5 border border-white/10 focus:ring-2 focus:ring-primary outline-none transition-all"
                />
              </div>
              <div className="space-y-2">
                <label className="text-sm font-bold text-slate-400">自己紹介</label>
                <textarea 
                  name="description"
                  defaultValue={user.selfDescription || ""}
                  rows={4}
                  className="w-full px-6 py-4 rounded-2xl bg-white/5 border border-white/10 focus:ring-2 focus:ring-primary outline-none transition-all resize-none"
                />
              </div>
            </div>
          </section>

          {/* 配送先設定 */}
          <section className="space-y-6">
            <h2 className="text-xl font-bold flex items-center gap-2">
              <MapPin size={20} className="text-emerald-400" /> 配送・連絡先
            </h2>
            <div className="space-y-2">
              <label className="text-sm font-bold text-slate-400">配送先住所（都道府県・市区町村・番地）</label>
              <input 
                name="address"
                defaultValue={user.receiveAddress || ""}
                placeholder="例: 東京都渋谷区..."
                className="w-full px-6 py-4 rounded-2xl bg-white/5 border border-white/10 focus:ring-2 focus:ring-primary outline-none transition-all"
              />
              <p className="text-[10px] text-slate-500">※リターンの郵送が必要なプロジェクトの支援時に使用されます。</p>
            </div>
          </section>

          {/* SNS連携 */}
          <section className="space-y-6">
            <h2 className="text-xl font-bold flex items-center gap-2">
              <Share2 size={20} className="text-blue-400" /> SNS連携
            </h2>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div className="space-y-2">
                <label className="text-sm font-bold text-slate-400">Twitter ID</label>
                <input 
                  name="twitterId"
                  defaultValue={user.twitterId || ""}
                  placeholder="@username"
                  className="w-full px-6 py-4 rounded-2xl bg-white/5 border border-white/10 focus:ring-2 focus:ring-primary outline-none transition-all"
                />
              </div>
              <div className="space-y-2">
                <label className="text-sm font-bold text-slate-400">Facebook ID</label>
                <input 
                  name="facebookId"
                  defaultValue={user.facebookId || ""}
                  placeholder="profile id"
                  className="w-full px-6 py-4 rounded-2xl bg-white/5 border border-white/10 focus:ring-2 focus:ring-primary outline-none transition-all"
                />
              </div>
            </div>
          </section>

          <button 
            type="submit"
            className="w-full py-5 bg-primary text-white font-bold rounded-2xl flex items-center justify-center gap-2 shadow-2xl shadow-indigo-500/20 hover:brightness-110 mb-20 transition-all"
          >
            <Save size={20} /> 設定を保存する
          </button>
        </form>
      </div>
    </div>
  );
}
