"use server";

import { auth } from "@/auth";
import { prisma } from "@/lib/prisma";
import { revalidatePath } from "next/cache";

export async function createNotification(
  userId: number,
  type: string,
  message: string,
  link?: string
) {
  await prisma.notification.create({
    data: { userId, type, message, link },
  });
}

export async function markNotificationsRead() {
  const session = await auth();
  if (!session?.user?.email) return;

  const user = await prisma.user.findUnique({
    where: { email: session.user.email },
  });
  if (!user) return;

  await prisma.notification.updateMany({
    where: { userId: user.id, read: false },
    data: { read: true },
  });

  revalidatePath("/mypage/notifications");
}

export async function getUnreadCount(): Promise<number> {
  const session = await auth();
  if (!session?.user?.email) return 0;

  const user = await prisma.user.findUnique({
    where: { email: session.user.email },
  });
  if (!user) return 0;

  return prisma.notification.count({
    where: { userId: user.id, read: false },
  });
}
