"use server";

import { auth } from "@/auth";
import { prisma } from "@/lib/prisma";
import { revalidatePath } from "next/cache";

export async function toggleFavoriteAction(projectId: number) {
  const session = await auth();
  if (!session?.user?.email) throw new Error("ログインが必要です。");

  const user = await prisma.user.findUnique({
    where: { email: session.user.email },
  });

  if (!user) throw new Error("ユーザーが見つかりません。");

  const existing = await prisma.favorite.findUnique({
    where: {
      userId_projectId: {
        userId: user.id,
        projectId: projectId,
      },
    },
  });

  if (existing) {
    await prisma.favorite.delete({
      where: { id: existing.id },
    });
  } else {
    await prisma.favorite.create({
      data: {
        userId: user.id,
        projectId: projectId,
      },
    });
  }

  revalidatePath("/");
  revalidatePath("/mypage");
  revalidatePath(`/projects/${projectId}`);
}
