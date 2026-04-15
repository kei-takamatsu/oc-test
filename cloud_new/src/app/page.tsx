import { ArrowRight, Globe, Lock, Rocket, TrendingUp, Zap, Shield } from "lucide-react";
import { prisma } from "@/lib/prisma";
import { auth } from "@/auth";
import ProjectCard from "@/components/ProjectCard";

export default async function Home({
  searchParams,
}: {
  searchParams: Promise<{ query?: string; categoryId?: string; areaId?: string; sort?: string }>;
}) {
  const { query, categoryId, areaId, sort } = await searchParams;

  const session = await auth();
  const currentUserId = session?.user?.id ? parseInt(session.user.id) : null;

  // カテゴリとエリアのマスターデータを取得
  const [categories, areas, projects] = await Promise.all([
    prisma.category.findMany(),
    prisma.area.findMany(),
    prisma.project.findMany({
      where: {
        opened: "yes",
        AND: [
          query ? { projectName: { contains: query } } : {},
          categoryId ? { categoryId: parseInt(categoryId) } : {},
          areaId ? { areaId: parseInt(areaId) } : {},
        ],
      },
      include: {
        category: true,
        area: true,
        favorites: currentUserId ? { where: { userId: currentUserId } } : false,
      },
      orderBy: sort === 'funded' ? { collectedAmount: 'desc' as const }
             : sort === 'ending' ? { collectionEndDate: 'asc' as const }
             : sort === 'popular' ? { backers: 'desc' as const }
             : { createdAt: 'desc' as const },
      take: 12,
    }),
  ]);

  return (
    <div className="flex flex-col gap-12 pb-20">
      {/* ヒーローセクション */}
      <section className="relative pt-24 pb-16 px-4 overflow-hidden">
        <div className="absolute top-0 right-0 w-2/3 h-full opacity-20 dark:opacity-40 -z-10 blur-3xl">
          <img src="/hero.png" alt="" className="w-full h-full object-cover rounded-full" />
        </div>
        
        <div className="container mx-auto text-center space-y-8 relative z-10">
          <h1 className="text-5xl md:text-7xl font-extrabold tracking-tight leading-tight">
            あなたの夢に、<br />
            <span className="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 bg-clip-text text-transparent">
              新しい重力を。
            </span>
          </h1>
          <p className="max-w-2xl mx-auto text-lg text-slate-500 dark:text-slate-400 leading-relaxed">
            CloudNewは、革新的なアイデアと熱狂的な支援者を結びつける、次世代のクラウドファンディング・プラットフォームです。
          </p>

          {/* 検索・絞り込みバー */}
          <div className="max-w-4xl mx-auto glass p-2 rounded-[2rem] shadow-2xl flex flex-col md:flex-row gap-2 mt-12">
            <form action="/" className="flex-grow flex flex-col md:flex-row gap-2 w-full">
              <input 
                name="query"
                defaultValue={query}
                placeholder="プロジェクト名で検索..."
                className="flex-grow bg-white/5 border-none px-6 py-4 rounded-2xl focus:ring-2 focus:ring-primary outline-none"
              />
              <div className="flex gap-2">
                <select 
                  name="categoryId" 
                  defaultValue={categoryId}
                  className="bg-white/5 border-none px-4 py-4 rounded-2xl focus:ring-2 focus:ring-primary outline-none text-sm min-w-[120px]"
                >
                  <option value="">すべてのカテゴリ</option>
                  {categories.map(c => <option key={c.id} value={c.id}>{c.name}</option>)}
                </select>
                <select 
                  name="areaId" 
                  defaultValue={areaId}
                  className="bg-white/5 border-none px-4 py-4 rounded-2xl focus:ring-2 focus:ring-primary outline-none text-sm min-w-[120px]"
                >
                  <option value="">すべてのエリア</option>
                  {areas.map(a => <option key={a.id} value={a.id}>{a.name}</option>)}
                </select>
              </div>
              <button className="bg-primary text-white px-8 py-4 rounded-2xl font-bold shadow-lg shadow-indigo-500/20 hover-subtle">
                検索
              </button>
            </form>
          </div>
        </div>
      </section>

      {/* プロジェクト一覧セクション */}
      <section className="container mx-auto px-4 space-y-12">
        <div className="flex items-end justify-between">
          <div className="space-y-2">
            <h2 className="text-3xl font-bold">
              {query || categoryId || areaId ? "検索結果" : "注目プロジェクト"}
            </h2>
            <p className="text-slate-500">
              {projects.length} 件のプロジェクトが見つかりました
            </p>
          </div>
          <div className="flex gap-2">
            {[
              { key: '', label: '新着順' },
              { key: 'funded', label: '支援額順' },
              { key: 'popular', label: '人気順' },
              { key: 'ending', label: '終了間近' },
            ].map(s => (
              <a
                key={s.key}
                href={`/?${new URLSearchParams({ ...(query ? {query} : {}), ...(categoryId ? {categoryId} : {}), ...(areaId ? {areaId} : {}), ...(s.key ? {sort: s.key} : {}) }).toString()}`}
                className={`px-4 py-2 rounded-xl text-xs font-bold transition-all ${
                  (sort || '') === s.key
                    ? 'bg-primary text-white shadow-lg shadow-indigo-500/20'
                    : 'bg-white/5 text-slate-400 hover:bg-white/10'
                }`}
              >
                {s.label}
              </a>
            ))}
          </div>
        </div>

        {projects.length > 0 ? (
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            {projects.map((project) => (
              <ProjectCard 
                key={project.id} 
                {...project} 
                initialIsFavorite={project.favorites?.length > 0}
              />
            ))}
          </div>
        ) : (
          <div className="glass p-20 rounded-[3rem] text-center space-y-4">
            <p className="text-xl text-slate-500">該当するプロジェクトが見つかりませんでした。</p>
            <a href="/" className="inline-block text-primary font-bold hover:underline">条件をクリアする</a>
          </div>
        )}
      </section>

      {/* 価値提案セクション */}
      <section className="bg-slate-900/10 py-20">
        <div className="container mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-12 text-center">
          <div className="space-y-4">
            <div className="w-12 h-12 bg-indigo-500/10 text-indigo-500 rounded-2xl flex items-center justify-center mx-auto">
              <TrendingUp />
            </div>
            <h4 className="font-bold">高い成功率</h4>
            <p className="text-sm text-slate-500">専門家による丁寧なアドバイスでプロジェクトを成功へ導きます。</p>
          </div>
          <div className="space-y-4">
            <div className="w-12 h-12 bg-purple-500/10 text-purple-500 rounded-2xl flex items-center justify-center mx-auto">
              <Lock />
            </div>
            <h4 className="font-bold">セキュアな決済</h4>
            <p className="text-sm text-slate-500">Stripe公式採用により、世界最高水準のセキュリティで決済可能です。</p>
          </div>
          <div className="space-y-4">
            <div className="w-12 h-12 bg-pink-500/10 text-pink-500 rounded-2xl flex items-center justify-center mx-auto">
              <Globe />
            </div>
            <h4 className="font-bold">グローバル対応</h4>
            <p className="text-sm text-slate-500">海外からの支援もスムーズに受け付けることができます。</p>
          </div>
          <div className="space-y-4">
            <div className="w-12 h-12 bg-indigo-500/10 text-indigo-500 rounded-2xl flex items-center justify-center mx-auto">
              <Rocket />
            </div>
            <h4 className="font-bold">最速入金</h4>
            <p className="text-sm text-slate-500">プロジェクト終了後、業界最速水準での入金振込を実現しました。</p>
          </div>
        </div>
      </section>
      {/* 特長セクション */}
      <section className="container mx-auto px-4 py-20">
        <div className="text-center space-y-4 mb-16">
          <h2 className="text-3xl font-bold">CloudNew が選ばれる理由</h2>
          <p className="text-slate-500">最高峰の体験を、すべてのクリエイターと支援者に。</p>
        </div>
        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
          {[
            { icon: <Zap className="text-yellow-400" />, title: "圧倒的なスピード", desc: "アイデアの公開から支援の受け取りまで、ストレスのない迅速なプロセスを提供。" },
            { icon: <Shield className="text-emerald-400" />, title: "信頼のセキュリティ", desc: "すべての取引は高度に暗号化され、厳正な審査を通過したプロジェクトのみを掲載。" },
            { icon: <Globe className="text-blue-400" />, title: "グローバルな繋がり", desc: "場所を問わず、世界中の熱狂的な支援者とあなたのアイデアを共有可能。" },
          ].map((feature, i) => (
            <div key={i} className="glass p-8 rounded-[2rem] space-y-4 hover:bg-white/5 transition-colors">
              <div className="w-12 h-12 rounded-2xl bg-white/5 flex items-center justify-center">
                {feature.icon}
              </div>
              <h3 className="text-xl font-bold">{feature.title}</h3>
              <p className="text-sm text-slate-500 leading-relaxed">{feature.desc}</p>
            </div>
          ))}
        </div>
      </section>

      {/* ご利用の流れ */}
      <section className="bg-white/5 py-24">
        <div className="container mx-auto px-4">
          <div className="text-center space-y-4 mb-20">
            <h2 className="text-3xl font-bold">ご利用の流れ</h2>
            <p className="text-slate-500">わずか3つのステップで、あなたの夢をカタチに。</p>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-12 relative">
            <div className="hidden md:block absolute top-10 left-1/4 right-1/4 h-0.5 border-t-2 border-dashed border-white/10 -z-10" />
            {[
              { step: "01", title: "プロジェクト作成", desc: "アイデアを入力し、魅力的なリターンを設定して公開します。" },
              { step: "02", title: "支援を集める", desc: "SNSなどでシェアし、共感してくれる支援者を募ります。" },
              { step: "03", title: "夢を実現する", desc: "目標達成後、集まった資金でプロジェクトを実行に移します。" },
            ].map((item, i) => (
              <div key={i} className="text-center space-y-6">
                <div className="w-20 h-20 rounded-full bg-primary mx-auto flex items-center justify-center text-2xl font-black shadow-2xl shadow-indigo-500/40">
                  {item.step}
                </div>
                <h3 className="text-xl font-bold">{item.title}</h3>
                <p className="text-sm text-slate-500 max-w-[250px] mx-auto">{item.desc}</p>
              </div>
            ))}
          </div>
        </div>
      </section>
    </div>
  );
}
