import { prisma } from "@/lib/prisma";
import { auth } from "@/auth";
import { notFound, redirect } from "next/navigation";
import { Settings, Trash2, Newspaper, Send, Users } from "lucide-react";
import ProjectForm from "@/components/projects/ProjectForm";
import { updateProjectAction, deleteProjectAction } from "@/lib/actions/project-manage-actions";
import { createActivityReportAction } from "@/lib/actions/activity-report-actions";

interface EditProjectPageProps {
  params: Promise<{ id: string }>;
}

export default async function EditProjectPage({ params }: EditProjectPageProps) {
  const session = await auth();
  if (!session) redirect("/login");

  const { id } = await params;
  const projectId = parseInt(id);

  if (isNaN(projectId)) notFound();

  const project = await prisma.project.findUnique({
    where: { id: projectId },
    include: {
      owner: true,
      backingLevels: true,
      backedProjects: {
        include: { user: true, backingLevel: true },
        orderBy: { createdAt: "desc" },
      },
    },
  });

  if (!project) notFound();
  if (project.owner.email !== session?.user?.email) redirect("/mypage");

  const updateWithId = updateProjectAction.bind(null, projectId);
  const deleteWithId = deleteProjectAction.bind(null, projectId);
  const createReportWithId = createActivityReportAction.bind(null, projectId);

  return (
    <div className="container mx-auto px-4 py-16">
      <div className="max-w-4xl mx-auto space-y-12">
        <div className="flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-white/5 pb-8">
          <div className="space-y-4">
            <div className="inline-flex p-3 bg-indigo-500/10 text-indigo-500 rounded-2xl">
              <Settings size={32} />
            </div>
            <h1 className="text-4xl font-extrabold tracking-tight">プロジェクト編集</h1>
            <p className="text-slate-500">内容を最新の情報にアップデートしましょう。</p>
          </div>

          <form action={deleteWithId} onSubmit={(e) => {
            if (!confirm("本当にこのプロジェクトを削除しますか？この操作は取り消せません。")) {
              e.preventDefault();
            }
          }}>
            <button
              type="submit"
              className="flex items-center gap-2 px-6 py-3 bg-red-500/10 text-red-500 rounded-2xl font-bold hover:bg-red-500/20 transition-colors"
            >
              <Trash2 size={18} /> プロジェクトを削除
            </button>
          </form>
        </div>

        {/* 活動報告投稿フォーム */}
        <section className="glass p-8 md:p-12 rounded-[2.5rem] shadow-2xl space-y-8 bg-indigo-500/5">
          <div className="flex items-center gap-3">
            <div className="p-3 bg-indigo-500/10 text-indigo-500 rounded-2xl">
              <Newspaper size={24} />
            </div>
            <h2 className="text-2xl font-bold">活動報告を投稿する</h2>
          </div>
          
          <form action={createReportWithId} className="space-y-4">
            <input 
              name="title" 
              placeholder="報告のタイトル (例: 試作品が完成しました！)"
              required
              className="w-full px-6 py-4 rounded-2xl bg-white/5 border border-white/10 focus:ring-2 focus:ring-primary outline-none transition-all"
            />
            <textarea 
              name="content" 
              placeholder="進捗状況を支援者に伝えましょう。"
              required
              rows={4}
              className="w-full px-6 py-4 rounded-2xl bg-white/5 border border-white/10 focus:ring-2 focus:ring-primary outline-none transition-all resize-none"
            />
            <button 
              type="submit"
              className="flex items-center gap-2 px-8 py-4 bg-indigo-500 text-white rounded-2xl font-bold border border-white/10 shadow-lg hover:brightness-110 transition-all"
            >
              <Send size={18} /> 報告を投稿する
            </button>
          </form>
        </section>

        <ProjectForm
          initialData={project}
          categories={await prisma.category.findMany({ orderBy: { name: "asc" } })}
          areas={await prisma.area.findMany({ orderBy: { name: "asc" } })}
          onSubmit={updateWithId}
          isEdit={true}
        />

        {/* 支援者管理リスト */}
        <section className="glass p-8 md:p-12 rounded-[2.5rem] shadow-2xl space-y-8 bg-emerald-500/5">
          <div className="flex items-center justify-between">
            <div className="flex items-center gap-3">
              <div className="p-3 bg-emerald-500/10 text-emerald-500 rounded-2xl">
                <Users size={24} />
              </div>
              <h2 className="text-2xl font-bold">支援者一覧</h2>
            </div>
            <span className="text-sm font-bold text-slate-500">{project.backedProjects.length}件の支援</span>
          </div>

          {project.backedProjects.length > 0 ? (
            <div className="space-y-4">
              {project.backedProjects.map((bp) => (
                <div key={bp.id} className="flex items-center gap-4 p-5 rounded-2xl bg-white/5 border border-white/5">
                  <div className="w-10 h-10 rounded-full bg-emerald-500/10 flex items-center justify-center text-emerald-500 font-bold text-sm shrink-0">
                    {bp.user.nickname?.[0]}
                  </div>
                  <div className="flex-grow min-w-0">
                    <p className="font-bold text-sm">{bp.user.nickname}</p>
                    {bp.comment && <p className="text-xs text-slate-500 truncate mt-0.5">{bp.comment}</p>}
                  </div>
                  <div className="text-right shrink-0">
                    <p className="font-black text-primary">¥{bp.investAmount.toLocaleString()}</p>
                    <p className="text-[10px] text-slate-500">{bp.backingLevel?.name}</p>
                  </div>
                </div>
              ))}
            </div>
          ) : (
            <div className="p-12 rounded-[2rem] text-center text-slate-500 bg-white/5">
              まだ支援者はいません。
            </div>
          )}
        </section>
      </div>
    </div>
  );
}
