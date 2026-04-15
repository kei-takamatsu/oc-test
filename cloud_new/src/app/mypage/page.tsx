import { auth } from "@/auth";
import { prisma } from "@/lib/prisma";
import { redirect } from "next/navigation";
import ProjectCard from "@/components/ProjectCard";
import { User, Wallet, Rocket, Settings, Edit3, Heart } from "lucide-react";
import Link from "next/link";

export default async function MyPage() {
  const session = await auth();
  if (!session?.user?.email) {
    redirect("/login");
  }

  const user = await prisma.user.findUnique({
    where: { email: session.user.email },
    include: {
      projects: {
        include: {
          category: true,
          area: true,
          favorites: { where: { userId: parseInt(session.user.id!) } }
        },
        orderBy: { createdAt: "desc" },
      },
      backedProjects: {
        include: {
          project: {
            include: {
              category: true,
              area: true,
              favorites: { where: { userId: parseInt(session.user.id!) } }
            },
          },
        },
        orderBy: { createdAt: "desc" },
      },
      favorites: {
        include: {
          project: {
            include: {
              category: true,
              area: true,
              favorites: { where: { userId: parseInt(session.user.id!) } }
            }
          }
        },
        orderBy: { createdAt: "desc" }
      }
    },
  });

  if (!user) {
    redirect("/login");
  }

  const currentUserId = parseInt(session.user.id!);

  // 統計情報の計算
  const totalBackedAmount = user.backedProjects.reduce((sum, bp) => sum + bp.investAmount, 0);
  const totalCollectedAmount = user.projects.reduce((sum, p) => sum + p.collectedAmount, 0);
  const successCount = user.projects.filter(p => p.collectedAmount >= p.goalAmount).length;

  return (
    <div className="container mx-auto px-4 py-16 max-w-7xl">
      <div className="flex flex-col md:flex-row gap-12">
        {/* サイドバー: プロフィール概要 */}
        <aside className="md:w-1/3 space-y-8">
          <div className="glass p-8 rounded-[2.5rem] shadow-2xl relative overflow-hidden group">
            <div className="absolute top-0 right-0 w-32 h-32 bg-primary/10 rounded-full -mr-16 -mt-16 blur-3xl group-hover:bg-primary/20 transition-colors" />
            <div className="flex flex-col items-center text-center space-y-6">
              <div className="relative">
                <div className="w-32 h-32 rounded-[2rem] bg-gradient-to-br from-indigo-500 to-purple-500 p-1">
                  <div className="w-full h-full rounded-[1.8rem] bg-slate-900 flex items-center justify-center text-4xl font-black text-white">
                    {user.nickname[0]}
                  </div>
                </div>
                <div className="absolute -bottom-2 -right-2 p-2 bg-emerald-500 rounded-xl border-4 border-slate-950 shadow-xl">
                  <div className="w-3 h-3 bg-white rounded-full animate-pulse" />
                </div>
              </div>
              <div>
                <h1 className="text-3xl font-extrabold tracking-tight">{user.nickname}</h1>
                <p className="text-slate-500 text-sm mt-1">{user.email}</p>
              </div>
              
              {/* 実績サマリー */}
              <div className="w-full space-y-4 pt-4 border-t border-white/5">
                <div className="bg-white/5 rounded-2xl p-4 text-left border border-white/5">
                  <p className="text-[10px] text-slate-500 uppercase font-black tracking-widest flex items-center gap-2 mb-1">
                    支援実績 <span className="w-1 h-1 rounded-full bg-emerald-500" />
                  </p>
                  <div className="flex justify-between items-baseline">
                    <p className="text-2xl font-black text-white">¥{totalBackedAmount.toLocaleString()}</p>
                    <p className="text-xs text-slate-400">{user.backedProjects.length}件</p>
                  </div>
                </div>
                <div className="bg-white/5 rounded-2xl p-4 text-left border border-white/5">
                  <p className="text-[10px] text-slate-500 uppercase font-black tracking-widest flex items-center gap-2 mb-1">
                    掲載実績 <span className="w-1 h-1 rounded-full bg-indigo-500" />
                  </p>
                  <div className="flex justify-between items-baseline">
                    <p className="text-2xl font-black text-white">¥{totalCollectedAmount.toLocaleString()}</p>
                    <p className="text-xs text-slate-400">{successCount}件達成</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <nav className="glass p-4 rounded-[2rem] shadow-xl">
            <div className="space-y-2">
              <Link href="/mypage/settings" className="flex items-center gap-4 px-6 py-4 hover:bg-white/5 rounded-2xl font-bold text-slate-400 hover:text-white transition-all">
                <User size={20} /> プロフィール設定
              </Link>
              <Link href="#" className="flex items-center gap-4 px-6 py-4 hover:bg-white/5 rounded-2xl font-bold text-slate-400 hover:text-white transition-all">
                <Wallet size={20} /> 支払い情報の管理
              </Link>
              <Link href="#" className="flex items-center gap-4 px-6 py-4 hover:bg-white/5 rounded-2xl font-bold text-slate-400 hover:text-white transition-all">
                <Settings size={20} /> アカウント連携
              </Link>
            </div>
          </nav>
        </aside>

        {/* メインコンテンツ */}
        <div className="md:w-2/3 space-y-16">
          
          {/* お気に入りプロジェクト */}
          <section className="space-y-8">
            <div className="flex items-center gap-4">
              <div className="p-3 bg-pink-500/10 text-pink-500 rounded-2xl shadow-lg ring-1 ring-pink-500/20">
                <Heart size={24} />
              </div>
              <h2 className="text-2xl font-extrabold tracking-tight">お気に入りプロジェクト</h2>
            </div>
            {user.favorites.length > 0 ? (
              <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                {user.favorites.map((fav) => (
                  <ProjectCard 
                    key={fav.project.id} 
                    {...fav.project} 
                    initialIsFavorite={fav.project.favorites.length > 0} 
                  />
                ))}
              </div>
            ) : (
              <div className="p-12 glass rounded-[2.5rem] text-center space-y-4 border-dashed border-white/10">
                <p className="text-slate-500">お気に入りのプロジェクトはまだありません。</p>
                <Link href="/" className="inline-flex items-center text-primary font-bold hover:underline">
                  プロジェクトを探しに行く <ArrowRight size={16} className="ml-1" />
                </Link>
              </div>
            )}
          </section>

          {/* 支援したプロジェクト */}
          <section className="space-y-8">
            <div className="flex items-center gap-4">
              <div className="p-3 bg-emerald-500/10 text-emerald-500 rounded-2xl shadow-lg ring-1 ring-emerald-500/20">
                <Wallet size={24} />
              </div>
              <h2 className="text-2xl font-extrabold tracking-tight">支援したプロジェクト</h2>
            </div>
            {user.backedProjects.length > 0 ? (
              <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                {user.backedProjects.map((bp) => (
                  <ProjectCard 
                    key={bp.project.id} 
                    {...bp.project} 
                    initialIsFavorite={bp.project.favorites.length > 0}
                  />
                ))}
              </div>
            ) : (
              <div className="p-12 glass rounded-[2.5rem] text-center space-y-4 border-dashed border-white/10">
                <p className="text-slate-500">あなたが支援したプロジェクトはまだありません。</p>
                <Link href="/" className="inline-flex items-center text-primary font-bold hover:underline">
                  最初のプロジェクトを支援する <ArrowRight size={16} className="ml-1" />
                </Link>
              </div>
            )}
          </section>

          {/* 作成したプロジェクト */}
          <section className="space-y-8">
            <div className="flex items-center gap-4">
              <div className="p-3 bg-indigo-500/10 text-indigo-500 rounded-2xl shadow-lg ring-1 ring-indigo-500/20">
                <Rocket size={24} />
              </div>
              <h2 className="text-2xl font-extrabold tracking-tight">作成したプロジェクト</h2>
            </div>
            {user.projects.length > 0 ? (
              <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                {user.projects.map((project) => (
                  <div key={project.id} className="relative group/manage">
                    <ProjectCard 
                      {...project} 
                      initialIsFavorite={project.favorites.length > 0}
                    />
                    <Link 
                      href={`/projects/${project.id}/edit`}
                      className="absolute top-4 right-4 p-3 glass rounded-2xl opacity-0 group-hover/manage:opacity-100 transition-opacity flex items-center gap-2 text-sm font-bold shadow-2xl z-20"
                    >
                      <Edit3 size={16} /> 編集
                    </Link>
                  </div>
                ))}
              </div>
            ) : (
              <div className="p-12 glass rounded-[2.5rem] text-center space-y-4 border-dashed border-white/10">
                <p className="text-slate-500">あなたが作成したプロジェクトはまだありません。</p>
                <Link href="/projects/create" className="inline-flex items-center text-primary font-bold hover:underline">
                  プロジェクトを立ち上げる <ArrowRight size={16} className="ml-1" />
                </Link>
              </div>
            )}
          </section>

        </div>
      </div>
    </div>
  );
}

const ArrowRight = ({ size, className }: { size: number, className: string }) => (
  <svg width={size} height={size} viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" className={className}>
    <path d="M5 12h14M12 5l7 7-7 7"/>
  </svg>
);
