"use server";

import { auth } from "@/auth";
import { prisma } from "@/lib/prisma";
import { revalidatePath } from "next/cache";
import { redirect } from "next/navigation";
import { createNotification } from "./notification-actions";

export async function processSupport(levelId: number, formData: FormData) {
  const session = await auth();

  if (!session?.user?.email) {
    throw new Error("ログインが必要です。");
  }

  const user = await prisma.user.findUnique({
    where: { email: session.user.email },
  });

  if (!user) {
    throw new Error("ユーザーが見つかりません。");
  }

  const level = await prisma.backingLevel.findUnique({
    where: { id: levelId },
    include: { project: true },
  });

  if (!level) {
    throw new Error("リターンが見つかりません。");
  }

  // 期限チェック
  if (level.project.collectionEndDate && level.project.collectionEndDate < new Date()) {
    throw new Error("このプロジェクトの募集期間は終了しています。");
  }

  const comment = formData.get("comment") as string;

  // 1. Create BackedProject record
  await prisma.backedProject.create({
    data: {
      projectId: level.projectId,
      userId: user.id,
      backingLevelId: level.id,
      investAmount: level.investAmount,
      comment: comment || null,
      status: "authorized", // For now, we assume it's success
    },
  });

  // 2. Update Project collectedAmount and backers
  // Note: In a real app, this should be a transaction
  await prisma.$transaction([
    prisma.project.update({
      where: { id: level.projectId },
      data: {
        collectedAmount: { increment: level.investAmount },
        backers: { increment: 1 },
      },
    }),
    prisma.backingLevel.update({
      where: { id: level.id },
      data: {
        nowCount: { increment: 1 },
      },
    }),
  ]);

  revalidatePath("/");
  revalidatePath("/mypage");
  revalidatePath(`/projects/${level.projectId}`);

  // オーナーに通知
  await createNotification(
    level.project.ownerId,
    "support",
    `${user.nickname}さんが「${level.project.projectName}」に¥${level.investAmount.toLocaleString()}を支援しました！`,
    `/projects/${level.projectId}`
  );

  redirect(`/projects/${level.projectId}?success=true`);
}
