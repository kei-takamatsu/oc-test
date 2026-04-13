import { ArrowRight, Globe, Lock, Rocket, TrendingUp } from "lucide-react";

export default function Home() {
  return (
    <div className="flex flex-col gap-20 pb-20">
      {/* ヒーローセクション */}
      <section className="relative pt-32 pb-20 px-4 overflow-hidden">
        {/* 背景の装飾画像 */}
        <div className="absolute top-0 right-0 w-2/3 h-full opacity-20 dark:opacity-40 -z-10 blur-3xl">
          <img src="/hero.png" alt="" className="w-full h-full object-cover rounded-full" />
        </div>
        
        <div className="container mx-auto text-center space-y-8 relative z-10">
          <div className="flex justify-center mb-8">
            <img 
              src="/hero.png" 
              alt="CloudNew Vision" 
              className="w-full max-w-2xl rounded-3xl shadow-2xl shadow-indigo-500/20 glass p-2 hover:scale-[1.02] transition-transform duration-500"
            />
          </div>
          <div className="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-xs font-semibold text-indigo-400">
            <span className="relative flex h-2 w-2">
              <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
              <span className="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
            </span>
            Next Generation Platform
          </div>
          <h1 className="text-5xl md:text-7xl font-extrabold tracking-tight leading-tight">
            あなたの夢に、<br />
            <span className="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 bg-clip-text text-transparent">
              新しい重力を。
            </span>
          </h1>
          <p className="max-w-2xl mx-auto text-lg text-slate-500 dark:text-slate-400 leading-relaxed">
            CloudNewは、革新的なアイデアと熱狂的な支援者を結びつける、次世代のクラウドファンディング・プラットフォームです。
          </p>
          <div className="flex flex-col sm:flex-row gap-4 justify-center">
            <button className="px-8 py-4 bg-primary text-white rounded-full font-bold shadow-xl shadow-indigo-500/20 hover-subtle flex items-center justify-center gap-2">
              プロジェクトを探す <ArrowRight size={20} />
            </button>
            <button className="px-8 py-4 glass rounded-full font-bold hover:bg-white/5 transition-colors">
              詳細を見る
            </button>
          </div>
        </div>
      </section>

      {/* 注目プロジェクトセクション */}
      <section className="container mx-auto px-4 space-y-12">
        <div className="flex items-end justify-between">
          <div className="space-y-2">
            <h2 className="text-3xl font-bold">注目プロジェクト</h2>
            <p className="text-slate-500">今、最も熱い注目を集めているプロジェクトをご紹介します。</p>
          </div>
          <button className="text-primary font-semibold flex items-center gap-1 hover:underline">
            すべて見る <ArrowRight size={16} />
          </button>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
          {[1, 2, 3].map((i) => (
            <div key={i} className="group glass rounded-3xl overflow-hidden hover-subtle flex flex-col">
              <div className="h-48 bg-slate-800 relative">
                <div className="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent" />
                <div className="absolute bottom-4 left-4">
                  <span className="px-2 py-1 rounded-md bg-indigo-500 text-[10px] font-bold text-white uppercase tracking-wider">
                    Technology
                  </span>
                </div>
              </div>
              <div className="p-6 space-y-4 flex-grow flex flex-col">
                <h3 className="text-xl font-bold group-hover:text-primary transition-colors">
                  革新的なスマート・クリスタルデバイスの開発
                </h3>
                <p className="text-sm text-slate-500 line-clamp-2">
                  人々の生活をより豊かに、よりスマートにする次世代のデバイスを目指しています。
                </p>
                <div className="space-y-2 pt-4 mt-auto">
                  <div className="flex justify-between text-xs font-medium">
                    <span>達成率 75%</span>
                    <span>残り 12日</span>
                  </div>
                  <div className="h-1.5 w-full bg-slate-200 dark:bg-slate-800 rounded-full overflow-hidden">
                    <div className="h-full bg-gradient-to-r from-indigo-500 to-purple-500 w-[75%]" />
                  </div>
                  <div className="flex justify-between items-center pt-2">
                    <span className="font-bold">¥1,500,000</span>
                    <span className="text-xs text-slate-400">234人の支援者が応援中</span>
                  </div>
                </div>
              </div>
            </div>
          ))}
        </div>
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
    </div>
  );
}
