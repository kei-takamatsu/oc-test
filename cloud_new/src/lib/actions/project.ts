"use server";

import { auth } from "@/auth";
import { prisma } from "@/lib/prisma";
import { revalidatePath } from "next/cache";
import { redirect } from "next/navigation";

export async function createProject(formData: FormData) {
  const session = await auth();
  if (!session?.user?.email) {
    throw new Error("Unauthorized");
  }

  const user = await prisma.user.findUnique({
    where: { email: session.user.email },
  });

  if (!user) throw new Error("User not found");

  const projectName = formData.get("projectName") as string;
  const description = formData.get("description") as string;
  const goalAmount = parseInt(formData.get("goalAmount") as string);
  const collectionTerm = parseInt(formData.get("collectionTerm") as string);

  const project = await prisma.project.create({
    data: {
      projectName,
      description,
      goalAmount,
      collectionTerm,
      ownerId: user.id,
      opened: "no", // 初期状態は非公開
    },
  });

  revalidatePath("/");
  redirect(`/projects/${project.id}`);
}
