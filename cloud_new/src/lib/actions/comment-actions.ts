"use server";

import { auth } from "@/auth";
import { prisma } from "@/lib/prisma";
import { revalidatePath } from "next/cache";

export async function createCommentAction(projectId: number, formData: FormData) {
  const session = await auth();
  if (!session?.user?.email) throw new Error("ログインが必要です。");

  const user = await prisma.user.findUnique({
    where: { email: session.user.email },
  });

  if (!user) throw new Error("ユーザーが見つかりません。");

  const content = formData.get("content") as string;
  if (!content) throw new Error("コメント内容を入力してください。");

  await prisma.comment.create({
    data: {
      projectId: projectId,
      userId: user.id,
      content: content,
    },
  });

  // プロジェクトオーナーに通知
  const project = await prisma.project.findUnique({
    where: { id: projectId },
    select: { ownerId: true, projectName: true },
  });

  if (project && project.ownerId !== user.id) {
    const { createNotification } = await import("./notification-actions");
    await createNotification(
      project.ownerId,
      "comment",
      `${user.nickname}さんが「${project.projectName}」にコメントしました`,
      `/projects/${projectId}`
    );
  }

  revalidatePath(`/projects/${projectId}`);
}
