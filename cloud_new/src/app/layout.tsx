import type { Metadata } from "next";
import { Inter } from "next/font/google";
import "./globals.css";

const inter = Inter({
  variable: "--font-inter",
  subsets: ["latin"],
});

export const metadata: Metadata = {
  title: "Cloud Funding - モダンなクラウドファンディング・プラットフォーム",
  description: "あなたの夢をかなえる、次世代のクラウドファンディング体験。",
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="ja" className={`${inter.variable} h-full antialiased`}>
      <body className="min-h-full flex flex-col selection:bg-indigo-500/30">
        <header className="sticky top-0 z-50 w-full glass">
          <div className="container mx-auto px-4 h-16 flex items-center justify-between">
            <div className="text-xl font-bold bg-gradient-to-r from-indigo-500 to-purple-500 bg-clip-text text-transparent">
              CloudNew
            </div>
            <nav className="hidden md:flex gap-8 text-sm font-medium">
              <a href="/" className="hover:text-primary transition-colors">プロジェクトを探す</a>
              <a href="/projects/create" className="hover:text-primary transition-colors">プロジェクトを始める</a>
            </nav>
            <div className="flex gap-4">
              <button className="px-4 py-2 text-sm font-medium hover:text-primary transition-colors">ログイン</button>
              <button className="px-4 py-2 text-sm font-medium bg-primary text-white rounded-full shadow-lg shadow-indigo-500/20 hover-subtle">
                会員登録
              </button>
            </div>
          </div>
        </header>
        <main className="flex-grow">
          {children}
        </main>
        <footer className="py-12 border-t border-border glass">
          <div className="container mx-auto px-4 text-center text-sm text-slate-500">
            &copy; {new Date().getFullYear()} CloudNew. All rights reserved.
          </div>
        </footer>
      </body>
    </html>
  );
}
