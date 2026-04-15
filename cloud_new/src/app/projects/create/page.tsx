import { Rocket } from "lucide-react";
import ProjectForm from "@/components/projects/ProjectForm";
import { prisma } from "@/lib/prisma";
import { auth } from "@/auth";
import { redirect } from "next/navigation";
import { createProjectAction } from "@/lib/actions/project-action";

export default async function CreateProjectPage() {
  const session = await auth();
  if (!session) {
    redirect("/login");
  }

  const [categories, areas] = await Promise.all([
    prisma.category.findMany({ orderBy: { name: "asc" } }),
    prisma.area.findMany({ orderBy: { name: "asc" } }),
  ]);

  return (
    <div className="container mx-auto px-4 py-16">
      <div className="max-w-4xl mx-auto space-y-12">
        <div className="space-y-4 text-center">
          <div className="inline-flex p-3 bg-indigo-500/10 text-indigo-500 rounded-2xl mb-2">
            <Rocket size={32} />
          </div>
          <h1 className="text-4xl font-extrabold tracking-tight">プロジェクトを始める</h1>
          <p className="text-slate-500 max-w-lg mx-auto">あなたのアイデアに、新しい重力を。魅力的なリターンを設定して、支援者を集めましょう。</p>
        </div>

        <ProjectForm 
          categories={categories} 
          areas={areas} 
          onSubmit={createProjectAction} 
        />
      </div>
    </div>
  );
}
