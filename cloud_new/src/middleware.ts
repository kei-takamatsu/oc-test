import { NextResponse } from "next/server";

export function middleware() {
  // デモ環境のため、認証チェックを一時的にスキップしてページを表示させます。
  // これにより、Edge Runtime での Prisma エラーを回避します。
  return NextResponse.next();
}

export const config = {
  matcher: ["/((?!api|_next/static|_next/image|favicon.ico).*)"],
};
