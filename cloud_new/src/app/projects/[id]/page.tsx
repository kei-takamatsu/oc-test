import { notFound } from "next/navigation";
import { prisma } from "@/lib/prisma";
import { ArrowRight, Calendar, Users, Target, ShieldCheck, MapPin, Tag, History, MessageCircle, Send } from "lucide-react";
import Link from "next/link";
import { auth } from "@/auth";
import FavoriteButton from "@/components/FavoriteButton";
import ShareButtons from "@/components/ShareButtons";
import { createCommentAction } from "@/lib/actions/comment-actions";

interface ProjectDetailPageProps {
  params: Promise<{ id: string }>;
  searchParams: Promise<{ success?: string }>;
}

export default async function ProjectDetailPage({ params, searchParams }: ProjectDetailPageProps) {
  const { id } = await params;
  const { success } = await searchParams;
  const projectId = parseInt(id);

  if (isNaN(projectId)) {
    notFound();
  }

  const session = await auth();

  const project = await prisma.project.findUnique({
    where: { id: projectId },
    include: {
      owner: true,
      category: true,
      area: true,
      backingLevels: {
        orderBy: { investAmount: "asc" },
      },
      activityReports: {
        orderBy: { createdAt: "desc" },
      },
      comments: {
        include: { user: true },
        orderBy: { createdAt: "desc" },
      },
    },
  });

  if (!project) {
    notFound();
  }

  const currentUserId = session?.user?.id ? parseInt(session.user.id) : null;
  const isFavorite = currentUserId ? !!(await prisma.favorite.findUnique({
    where: { userId_projectId: { userId: currentUserId, projectId: project.id } }
  })) : false;

  const isEnded = project.collectionEndDate ? project.collectionEndDate < new Date() : false;
  const isSuccess = project.collectedAmount >= project.goalAmount;

  const progress = Math.min(Math.round((project.collectedAmount / project.goalAmount) * 100), 100);
  const daysRemaining = project.collectionEndDate 
    ? Math.max(0, Math.ceil((project.collectionEndDate.getTime() - Date.now()) / (1000 * 60 * 60 * 24)))
    : 0;

  return (
    <div className="container mx-auto px-4 py-12 space-y-12">
      {/* ヒーローセクション */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <div className="space-y-6">
          <div className="aspect-video bg-slate-800 rounded-[2.5rem] overflow-hidden shadow-2xl relative">
            {project.imageUrl ? (
              <img 
                src={project.imageUrl} 
                alt={project.projectName} 
                className="w-full h-full object-cover"
              />
            ) : (
              <div className="w-full h-full bg-gradient-to-br from-indigo-900 to-purple-900" />
            )}
            <div className="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent" />
          </div>
        </div>

        <div className="space-y-8">
          {success && (
            <div className="p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-emerald-500 text-sm font-bold flex items-center justify-center gap-2">
              <ShieldCheck size={20} /> 支援が完了しました！ありがとうございます。
            </div>
          )}
          <div className="space-y-4">
            <div className="flex items-center gap-3">
              <h1 className="text-4xl font-extrabold tracking-tight leading-tight">
                {project.projectName}
              </h1>
              {isSuccess && (
                <span className="px-3 py-1 bg-emerald-500 text-white text-[10px] font-black rounded-lg shadow-lg shadow-emerald-500/20 whitespace-nowrap">
                  SUCCESS!
                </span>
              )}
            </div>
            <div className="flex flex-wrap items-center gap-6 text-sm">
              <div className="flex items-center gap-2">
                <div className="w-8 h-8 rounded-full bg-indigo-500/10 flex items-center justify-center text-indigo-500 font-bold uppercase text-[10px]">
                  {project.owner.nickname?.[0]}
                </div>
                <span className="font-medium">{project.owner.nickname}</span>
              </div>
              <div className="flex items-center gap-3">
                {project.category && (
                  <span className="flex items-center gap-1 px-3 py-1 rounded-full bg-indigo-500/10 text-indigo-500 text-xs font-bold ring-1 ring-inset ring-indigo-500/20">
                    <Tag size={12} /> {project.category.name}
                  </span>
                )}
                {project.area && (
                  <span className="flex items-center gap-1 text-slate-500 text-xs font-medium border-l border-white/10 pl-3">
                    <MapPin size={14} className="text-indigo-400" /> {project.area.name}
                  </span>
                )}
              </div>
              <div className="ml-auto">
                <ShareButtons projectName={project.projectName} />
              </div>
            </div>
          </div>

          <div className="space-y-6 p-8 glass rounded-[2rem] shadow-xl">
            <div className="space-y-2">
              <div className="flex justify-between items-end">
                <span className="text-3xl font-bold">¥{project.collectedAmount.toLocaleString()}</span>
                <span className="text-slate-500 text-sm">目標: ¥{project.goalAmount.toLocaleString()}</span>
              </div>
              <div className="h-3 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                <div 
                  className="h-full bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 transition-all duration-1000" 
                  style={{ width: `${progress}%` }}
                />
              </div>
              <div className="flex justify-between text-xs font-bold text-slate-500">
                <span>{progress}% 達成</span>
                <span>残り{daysRemaining}日</span>
              </div>
            </div>

            <div className="grid grid-cols-2 gap-4">
              <div className="p-4 rounded-2xl bg-white/5 border border-white/5 flex flex-col items-center justify-center gap-1">
                <Users className="text-indigo-400" size={20} />
                <span className="text-lg font-bold">{project.backers}</span>
                <span className="text-[10px] text-slate-500 uppercase font-bold tracking-wider">支援者数</span>
              </div>
              <div className="p-4 rounded-2xl bg-white/5 border border-white/5 flex flex-col items-center justify-center gap-1">
                <Calendar className="text-purple-400" size={20} />
                <span className="text-lg font-bold">{daysRemaining}</span>
                <span className="text-[10px] text-slate-500 uppercase font-bold tracking-wider">残り日数</span>
              </div>
            </div>

            <div className="flex gap-4">
              {isEnded ? (
                <div className="flex-grow py-5 bg-slate-800 text-slate-500 rounded-2xl font-bold flex items-center justify-center gap-2 text-lg border border-white/5 cursor-not-allowed">
                  このプロジェクトの募集は終了しました
                </div>
              ) : (
                <>
                  <Link 
                    href="#returns"
                    className="flex-grow py-5 bg-primary text-white rounded-2xl font-bold shadow-2xl shadow-indigo-500/20 hover-subtle flex items-center justify-center gap-2 text-lg"
                  >
                    このプロジェクトを支援する <ArrowRight size={22} />
                  </Link>
                  <FavoriteButton 
                    projectId={project.id} 
                    initialIsFavorite={isFavorite} 
                  />
                </>
              )}
            </div>
          </div>
        </div>
      </div>

      {/* 詳細内容 */}
      <div id="returns" className="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <div className="lg:col-span-2 space-y-12">
          <section className="space-y-6">
            <h2 className="text-2xl font-bold border-l-4 border-primary pl-4">プロジェクトについて</h2>
            <div className="text-lg text-slate-600 dark:text-slate-400 leading-relaxed whitespace-pre-wrap">
              {project.description}
            </div>
          </section>

          {/* 活動報告セクション */}
          <section className="space-y-8">
            <h2 className="text-2xl font-bold border-l-4 border-indigo-500 pl-4 flex items-center gap-2">
              <History size={24} className="text-indigo-500" /> 活動報告
            </h2>
            
            <div className="space-y-6">
              {project.activityReports.length > 0 ? (
                project.activityReports.map((report) => (
                  <div key={report.id} className="glass p-8 rounded-[2rem] space-y-3 relative overflow-hidden group hover:bg-white/5 transition-colors">
                    <div className="absolute top-0 left-0 w-1 h-full bg-indigo-500/50" />
                    <div className="flex justify-between items-center text-xs font-bold text-slate-500">
                      <span>{report.createdAt.toLocaleDateString('ja-JP')}</span>
                      <span className="px-2 py-0.5 rounded bg-indigo-500/10 text-indigo-400">UPDATE</span>
                    </div>
                    <h3 className="text-xl font-bold group-hover:text-indigo-400 transition-colors">{report.title}</h3>
                    <p className="text-slate-600 dark:text-slate-400 leading-relaxed whitespace-pre-wrap text-sm">
                      {report.content}
                    </p>
                  </div>
                ))
              ) : (
                <div className="p-12 glass rounded-[2rem] text-center text-slate-500">
                  まだ活動報告はありません。
                </div>
              )}
            </div>
          </section>

          {/* コメントセクション */}
          <section className="space-y-8">
            <h2 className="text-2xl font-bold border-l-4 border-emerald-500 pl-4 flex items-center gap-2">
              <MessageCircle size={24} className="text-emerald-500" /> コメント・Q&A
            </h2>

            {session && (
              <form action={createCommentAction.bind(null, project.id)} className="glass p-6 rounded-[2rem] space-y-4">
                <textarea
                  name="content"
                  required
                  rows={3}
                  placeholder="質問や応援メッセージを投稿しましょう"
                  className="w-full px-6 py-4 rounded-2xl bg-white/5 border border-white/10 focus:ring-2 focus:ring-emerald-500 outline-none transition-all resize-none"
                />
                <button type="submit" className="flex items-center gap-2 px-6 py-3 bg-emerald-500 text-white rounded-2xl font-bold hover:brightness-110 transition-all">
                  <Send size={16} /> コメントを投稿
                </button>
              </form>
            )}

            <div className="space-y-4">
              {project.comments.length > 0 ? (
                project.comments.map((comment) => (
                  <div key={comment.id} className="glass p-6 rounded-[2rem] space-y-2">
                    <div className="flex items-center gap-3">
                      <div className="w-8 h-8 rounded-full bg-emerald-500/10 flex items-center justify-center text-emerald-500 font-bold text-xs">
                        {comment.user.nickname?.[0]}
                      </div>
                      <span className="font-bold text-sm">{comment.user.nickname}</span>
                      <span className="text-xs text-slate-500 ml-auto">{comment.createdAt.toLocaleDateString('ja-JP')}</span>
                    </div>
                    <p className="text-sm text-slate-400 leading-relaxed pl-11 whitespace-pre-wrap">{comment.content}</p>
                  </div>
                ))
              ) : (
                <div className="p-12 glass rounded-[2rem] text-center text-slate-500">
                  まだコメントはありません。最初のコメントを投稿しましょう！
                </div>
              )}
            </div>
          </section>
        </div>

        <div className="space-y-8">
          <h2 className="text-2xl font-bold">リターンを選ぶ</h2>
          <div className="space-y-6">
            {project.backingLevels.map((level) => (
              <Link key={level.id} href={`/checkout/${level.id}`} className="block">
                <div className="group glass p-6 rounded-3xl border border-white/10 hover:border-primary/50 transition-all cursor-pointer">
                  <div className="space-y-4">
                    <div className="flex justify-between items-start">
                      <h3 className="font-bold text-lg group-hover:text-primary transition-colors">{level.name}</h3>
                      <span className="text-xl font-black text-primary">¥{level.investAmount.toLocaleString()}</span>
                    </div>
                    <p className="text-sm text-slate-500 leading-relaxed">
                      {level.returnAmount}
                    </p>
                    <div className="pt-4 flex justify-between items-center text-xs font-bold">
                      <span className="text-slate-400">お届け予定: 2026年8月</span>
                      <span className="px-2 py-1 rounded bg-slate-800 text-slate-400">残り無制限</span>
                    </div>
                  </div>
                </div>
              </Link>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}
