import type { Metadata } from "next";
import "./globals.css";

export const metadata: Metadata = {
  title: "StoryWeaver - AI絵本ジェネレーター",
  description: "AIがストーリーから美しい絵本を生成。親和性スライダーで画像とテキストの融合度を調整し、トンボ付きPDFやKindle EPUBで出力できるプロフェッショナル絵本制作ツール。",
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="ja">
      <body>{children}</body>
    </html>
  );
}
