import type { Metadata, Viewport } from "next";
import "./globals.css";

export const metadata: Metadata = {
  title: "StoryWeaver - AI絵本ジェネレーター",
  description: "AIがストーリーから美しい絵本を生成。親和性スライダーで画像とテキストの融合度を調整し、トンボ付きPDFやKindle EPUBで出力できるプロフェッショナル絵本制作ツール。",
  manifest: "/manifest.json",
  appleWebApp: {
    capable: true,
    statusBarStyle: "default",
    title: "StoryWeaver",
  },
};

export const viewport: Viewport = {
  themeColor: "#8e7dbe",
  width: "device-width",
  initialScale: 1,
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="ja">
      <head>
        <link rel="apple-touch-icon" href="/icon-192.png" />
      </head>
      <body>{children}</body>
    </html>
  );
}
