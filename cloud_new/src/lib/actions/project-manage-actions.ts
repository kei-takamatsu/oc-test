"use server";

import { auth } from "@/auth";
import { prisma } from "@/lib/prisma";
import { revalidatePath } from "next/cache";
import { redirect } from "next/navigation";

export async function updateProjectAction(id: number, formData: FormData) {
  const session = await auth();
  if (!session?.user?.email) throw new Error("Unauthorized");

  const project = await prisma.project.findUnique({
    where: { id },
    include: { owner: true }
  });

  if (!project || project.owner.email !== session.user.email) {
    throw new Error("権限がありません。");
  }

  const projectName = formData.get("projectName") as string;
  const description = formData.get("description") as string;
  const imageUrl = formData.get("imageUrl") as string;
  const goalAmount = parseInt(formData.get("goalAmount") as string);
  const categoryId = formData.get("categoryId") ? parseInt(formData.get("categoryId") as string) : null;
  const areaId = formData.get("areaId") ? parseInt(formData.get("areaId") as string) : null;

  await prisma.project.update({
    where: { id },
    data: {
      projectName,
      description,
      imageUrl: imageUrl || null,
      goalAmount,
      categoryId,
      areaId,
    },
  });

  revalidatePath(`/projects/${id}`);
  revalidatePath("/mypage");
  redirect(`/projects/${id}`);
}

export async function deleteProjectAction(id: number) {
  const session = await auth();
  if (!session?.user?.email) throw new Error("Unauthorized");

  const project = await prisma.project.findUnique({
    where: { id },
    include: { owner: true }
  });

  if (!project || project.owner.email !== session.user.email) {
    throw new Error("権限がありません。");
  }

  // Related data deletion (Backing levels are often preserved or handled with caution, but for MVP we clear)
  await prisma.$transaction([
    prisma.backedProject.deleteMany({ where: { projectId: id } }),
    prisma.backingLevel.deleteMany({ where: { projectId: id } }),
    prisma.project.delete({ where: { id } }),
  ]);

  revalidatePath("/");
  revalidatePath("/mypage");
  redirect("/mypage");
}
