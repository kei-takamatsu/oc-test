import { auth } from "@/auth";
import { prisma } from "@/lib/prisma";
import { redirect } from "next/navigation";
import { Bell, Heart, MessageCircle, Wallet } from "lucide-react";
import Link from "next/link";
import { markNotificationsRead } from "@/lib/actions/notification-actions";

export default async function NotificationsPage() {
  const session = await auth();
  if (!session?.user?.email) redirect("/login");

  const user = await prisma.user.findUnique({
    where: { email: session.user.email },
  });
  if (!user) redirect("/login");

  const notifications = await prisma.notification.findMany({
    where: { userId: user.id },
    orderBy: { createdAt: "desc" },
    take: 50,
  });

  // ページ表示時に全て既読にする
  await markNotificationsRead();

  const getIcon = (type: string) => {
    switch (type) {
      case "support": return <Wallet size={18} className="text-emerald-400" />;
      case "comment": return <MessageCircle size={18} className="text-blue-400" />;
      default: return <Bell size={18} className="text-indigo-400" />;
    }
  };

  return (
    <div className="container mx-auto px-4 py-16 max-w-3xl">
      <div className="space-y-8">
        <div className="flex items-center gap-4 border-b border-white/5 pb-8">
          <div className="p-3 bg-amber-500/10 text-amber-500 rounded-2xl">
            <Bell size={32} />
          </div>
          <div>
            <h1 className="text-3xl font-bold">通知</h1>
            <p className="text-slate-500">{notifications.length}件の通知</p>
          </div>
        </div>

        {notifications.length > 0 ? (
          <div className="space-y-3">
            {notifications.map((n) => (
              <Link
                key={n.id}
                href={n.link || "#"}
                className={`block p-5 rounded-2xl transition-all hover:bg-white/5 ${
                  !n.read ? "bg-indigo-500/5 border border-indigo-500/10" : "bg-white/[0.02] border border-white/5"
                }`}
              >
                <div className="flex items-start gap-4">
                  <div className="p-2 rounded-xl bg-white/5 shrink-0 mt-0.5">
                    {getIcon(n.type)}
                  </div>
                  <div className="flex-grow min-w-0">
                    <p className="text-sm leading-relaxed">{n.message}</p>
                    <p className="text-xs text-slate-500 mt-1">
                      {n.createdAt.toLocaleDateString("ja-JP")} {n.createdAt.toLocaleTimeString("ja-JP", { hour: "2-digit", minute: "2-digit" })}
                    </p>
                  </div>
                  {!n.read && (
                    <div className="w-2 h-2 rounded-full bg-indigo-500 shrink-0 mt-2" />
                  )}
                </div>
              </Link>
            ))}
          </div>
        ) : (
          <div className="p-16 glass rounded-[2.5rem] text-center text-slate-500">
            まだ通知はありません。
          </div>
        )}
      </div>
    </div>
  );
}
