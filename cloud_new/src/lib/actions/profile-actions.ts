"use server";

import { auth } from "@/auth";
import { prisma } from "@/lib/prisma";
import { revalidatePath } from "next/cache";

export async function updateProfileAction(formData: FormData) {
  const session = await auth();
  if (!session?.user?.email) throw new Error("Unauthorized");

  const nickname = formData.get("nickname") as string;
  const selfDescription = formData.get("description") as string;
  const receiveAddress = formData.get("address") as string;
  const twitterId = formData.get("twitterId") as string;
  const facebookId = formData.get("facebookId") as string;

  await prisma.user.update({
    where: { email: session.user.email },
    data: {
      nickname,
      selfDescription,
      receiveAddress,
      twitterId,
      facebookId,
    },
  });

  revalidatePath("/mypage");
  revalidatePath("/mypage/settings");
}
