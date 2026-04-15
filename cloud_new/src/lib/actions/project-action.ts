"use server";

import { auth } from "@/auth";
import { prisma } from "@/lib/prisma";
import { revalidatePath } from "next/cache";
import { redirect } from "next/navigation";

export async function createProjectAction(formData: FormData) {
  const session = await auth();
  if (!session?.user?.email) {
    throw new Error("ログインが必要です。");
  }

  const user = await prisma.user.findUnique({
    where: { email: session.user.email },
  });

  if (!user) throw new Error("ユーザーが見つかりません。");

  const projectName = formData.get("projectName") as string;
  const description = formData.get("description") as string;
  const imageUrl = formData.get("imageUrl") as string;
  const goalAmount = parseInt(formData.get("goalAmount") as string);
  const collectionTerm = parseInt(formData.get("collectionTerm") as string);
  const categoryId = formData.get("categoryId") ? parseInt(formData.get("categoryId") as string) : null;
  const areaId = formData.get("areaId") ? parseInt(formData.get("areaId") as string) : null;
  
  // Backing levels handling
  const backingLevelsJson = formData.get("backingLevelsJson") as string;
  const backingLevels = JSON.parse(backingLevelsJson);

  const project = await prisma.project.create({
    data: {
      projectName,
      description,
      imageUrl: imageUrl || null,
      goalAmount,
      collectionTerm,
      categoryId,
      areaId,
      ownerId: user.id,
      opened: "yes", // デモ用なので即公開
      backingLevels: {
        create: backingLevels.map((level: any) => ({
          name: level.name,
          investAmount: parseInt(level.investAmount),
          returnAmount: level.returnAmount,
          delivery: 1, // Default: Email
        })),
      },
    },
  });

  revalidatePath("/");
  revalidatePath("/mypage");
  redirect(`/projects/${project.id}`);
}
