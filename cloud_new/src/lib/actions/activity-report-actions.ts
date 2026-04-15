"use server";

import { auth } from "@/auth";
import { prisma } from "@/lib/prisma";
import { revalidatePath } from "next/cache";

export async function createActivityReportAction(projectId: number, formData: FormData) {
  const session = await auth();
  if (!session?.user?.email) throw new Error("Unauthorized");

  const project = await prisma.project.findUnique({
    where: { id: projectId },
    include: { owner: true }
  });

  if (!project || project.owner.email !== session.user.email) {
    throw new Error("権限がありません。");
  }

  const title = formData.get("title") as string;
  const content = formData.get("content") as string;

  if (!title || !content) {
    throw new Error("タイトルと内容を入力してください。");
  }

  await prisma.activityReport.create({
    data: {
      projectId,
      title,
      content,
    },
  });

  revalidatePath(`/projects/${projectId}`);
  revalidatePath(`/projects/${projectId}/edit`);
}
