import Link from "next/link";
import { Github, Twitter, Instagram, Mail } from "lucide-react";

export default function Footer() {
  return (
    <footer className="mt-20 border-t border-white/5 glass">
      <div className="container mx-auto px-4 py-16">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-12">
          {/* ブランド */}
          <div className="space-y-6">
            <Link href="/" className="text-2xl font-bold bg-gradient-to-r from-indigo-500 to-purple-500 bg-clip-text text-transparent">
              CloudNew
            </Link>
            <p className="text-sm text-slate-500 leading-relaxed">
              あなたの革新的なアイデアに、新しい重力を。
              次世代の共創型クラウドファンディング・プラットフォーム。
            </p>
            <div className="flex gap-4">
              <a href="#" className="p-2 glass rounded-xl hover:text-primary transition-colors">
                <Twitter size={20} />
              </a>
              <a href="#" className="p-2 glass rounded-xl hover:text-primary transition-colors">
                <Instagram size={20} />
              </a>
              <a href="#" className="p-2 glass rounded-xl hover:text-primary transition-colors">
                <Github size={20} />
              </a>
            </div>
          </div>

          {/* サービス */}
          <div className="space-y-6">
            <h4 className="font-bold text-lg">サービス</h4>
            <ul className="space-y-4 text-sm text-slate-500">
              <li><Link href="/" className="hover:text-primary transition-colors">プロジェクトを探す</Link></li>
              <li><Link href="/projects/create" className="hover:text-primary transition-colors">プロジェクトを始める</Link></li>
              <li><Link href="#" className="hover:text-primary transition-colors">注目のリターン</Link></li>
              <li><Link href="#" className="hover:text-primary transition-colors">成功事例インタビュー</Link></li>
            </ul>
          </div>

          {/* サポート */}
          <div className="space-y-6">
            <h4 className="font-bold text-lg">サポート</h4>
            <ul className="space-y-4 text-sm text-slate-500">
              <li><Link href="#" className="hover:text-primary transition-colors">ヘルプセンター</Link></li>
              <li><Link href="#" className="hover:text-primary transition-colors">利用規約</Link></li>
              <li><Link href="#" className="hover:text-primary transition-colors">プライバシーポリシー</Link></li>
              <li><Link href="#" className="hover:text-primary transition-colors">特定商取引法に基づく表記</Link></li>
            </ul>
          </div>

          {/* ニュースレター */}
          <div className="space-y-6">
            <h4 className="font-bold text-lg">最新情報を受け取る</h4>
            <p className="text-sm text-slate-500">
              週に一度、注目のプロジェクトをお届けします。
            </p>
            <div className="flex gap-2">
              <input 
                type="email" 
                placeholder="email@example.com" 
                className="flex-grow glass px-4 py-3 rounded-xl text-sm focus:ring-2 focus:ring-primary outline-none"
              />
              <button className="p-3 bg-primary text-white rounded-xl shadow-lg shadow-indigo-500/20 hover-subtle">
                <Mail size={20} />
              </button>
            </div>
          </div>
        </div>

        <div className="mt-16 pt-8 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-4">
          <p className="text-xs text-slate-500">
            © 2026 CloudNew Inc. All rights reserved.
          </p>
          <div className="flex gap-8 text-xs text-slate-500">
            <Link href="#" className="hover:text-primary">運営会社</Link>
            <Link href="#" className="hover:text-primary">採用情報</Link>
            <Link href="#" className="hover:text-primary">プレスリリース</Link>
          </div>
        </div>
      </div>
    </footer>
  );
}
