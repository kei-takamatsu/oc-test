import { auth } from "@/auth";
import { prisma } from "@/lib/prisma";
import { notFound, redirect } from "next/navigation";
import { processSupport } from "@/lib/actions/checkout";
import { ArrowLeft, ShieldCheck, Mail } from "lucide-react";
import Link from "next/link";

interface CheckoutPageProps {
  params: Promise<{ levelId: string }>;
}

export default async function CheckoutPage({ params }: CheckoutPageProps) {
  const session = await auth();
  if (!session) {
    redirect("/login");
  }

  const { levelId } = await params;
  const id = parseInt(levelId);

  if (isNaN(id)) {
    notFound();
  }

  const level = await prisma.backingLevel.findUnique({
    where: { id },
    include: { project: true },
  });

  if (!level) {
    notFound();
  }

  const supportWithId = processSupport.bind(null, id);

  return (
    <div className="container mx-auto px-4 py-12">
      <div className="max-w-4xl mx-auto space-y-8">
        <Link href={`/projects/${level.projectId}`} className="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-primary transition-colors">
          <ArrowLeft size={16} /> プロジェクトに戻る
        </Link>

        <h1 className="text-3xl font-bold">支援内容の確認</h1>

        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <div className="lg:col-span-2 space-y-8">
            {/* 選択したリターン */}
            <div className="glass p-8 rounded-3xl space-y-4">
              <h2 className="text-lg font-bold flex items-center gap-2">
                <ShieldCheck className="text-indigo-400" /> 選択したリターン
              </h2>
              <div className="p-6 rounded-2xl bg-white/5 border border-white/10 space-y-3">
                <div className="flex justify-between items-start">
                  <h3 className="font-bold">{level.name}</h3>
                  <span className="text-xl font-black text-primary">¥{level.investAmount.toLocaleString()}</span>
                </div>
                <p className="text-sm text-slate-500 leading-relaxed">
                  {level.returnAmount}
                </p>
                <div className="text-xs text-slate-400 font-bold pt-2">
                  お届け予定: 2026年8月頃
                </div>
              </div>
            </div>

            {/* 応援メッセージ */}
            <form action={supportWithId} className="space-y-6">
              <div className="glass p-8 rounded-3xl space-y-4">
                <h2 className="text-lg font-bold">応援メッセージ (任意)</h2>
                <textarea
                  name="comment"
                  rows={4}
                  placeholder="プロジェクトオーナーにメッセージを送りましょう！"
                  className="w-full bg-white/5 border border-white/10 rounded-2xl p-4 focus:outline-none focus:ring-2 focus:ring-primary transition-all resize-none"
                />
              </div>

              {/* 決済セクション (Mock) */}
              <div className="glass p-8 rounded-3xl space-y-6">
                <h2 className="text-lg font-bold">お支払い方法</h2>
                <div className="p-4 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center gap-4">
                  <div className="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center text-white">
                    JP
                  </div>
                  <div className="flex-grow">
                    <p className="text-sm font-bold">クレジットカード (デモモード)</p>
                    <p className="text-xs text-slate-500">**** **** **** 4242</p>
                  </div>
                  <div className="text-xs font-bold text-indigo-400">変更</div>
                </div>

                <div className="pt-4 border-t border-white/10 space-y-4">
                  <div className="flex justify-between text-sm">
                    <span className="text-slate-500">支援金額</span>
                    <span>¥{level.investAmount.toLocaleString()}</span>
                  </div>
                  <div className="flex justify-between text-sm">
                    <span className="text-slate-500">システム利用料</span>
                    <span>¥220</span>
                  </div>
                  <div className="flex justify-between text-xl font-bold pt-2">
                    <span>合計</span>
                    <span className="text-primary">¥{(level.investAmount + 220).toLocaleString()}</span>
                  </div>
                </div>

                <button 
                  type="submit"
                  className="w-full py-4 bg-primary text-white rounded-2xl font-bold shadow-xl shadow-indigo-500/20 hover-subtle"
                >
                  支援を確定する
                </button>
                <p className="text-[10px] text-center text-slate-500">
                  「支援を確定する」ボタンを押すことで、利用規約に同意したものとみなされます。
                </p>
              </div>
            </form>
          </div>

          <div className="space-y-6">
            <div className="glass p-6 rounded-3xl space-y-4">
              <div className="aspect-video rounded-xl bg-slate-800 overflow-hidden">
                <img src={level.project.imageUrl || ''} alt={level.project.projectName} className="w-full h-full object-cover" />
              </div>
              <div>
                <h3 className="font-bold text-sm line-clamp-2">{level.project.projectName}</h3>
                <p className="text-xs text-slate-500 mt-1">作成者: {session.user?.name}</p>
              </div>
            </div>

            <div className="p-6 rounded-3xl bg-indigo-500/5 border border-indigo-500/10 space-y-3">
              <h4 className="text-xs font-bold text-indigo-400 uppercase tracking-wider">安心・安全への取り組み</h4>
              <p className="text-xs text-slate-500 leading-relaxed">
                CloudNewでは、すべてのプロジェクトの審査を行っています。万が一、プロジェクトが履行されない場合の保証制度も用意しています。
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
