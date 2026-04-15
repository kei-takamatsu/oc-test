"use client";

import { Twitter, Facebook, Link as LinkIcon } from "lucide-react";
import { useState } from "react";

interface ShareButtonsProps {
  projectName: string;
}

export default function ShareButtons({ projectName }: ShareButtonsProps) {
  const [copied, setCopied] = useState(false);

  const shareUrl = typeof window !== "undefined" ? window.location.href : "";
  const text = `${projectName} | CloudNew で応援中！`;

  const shareTwitter = () => {
    window.open(`https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(shareUrl)}`, "_blank");
  };

  const shareFacebook = () => {
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(shareUrl)}`, "_blank");
  };

  const copyLink = () => {
    navigator.clipboard.writeText(shareUrl);
    setCopied(true);
    setTimeout(() => setCopied(false), 2000);
  };

  return (
    <div className="flex items-center gap-3">
      <span className="text-xs font-bold text-slate-500 uppercase tracking-widest mr-2">Share</span>
      <button 
        onClick={shareTwitter}
        className="p-2.5 rounded-xl bg-white/5 border border-white/10 hover:bg-sky-500/10 hover:text-sky-400 hover:border-sky-500/30 transition-all"
        title="X (Twitter)でシェア"
      >
        <Twitter size={18} />
      </button>
      <button 
        onClick={shareFacebook}
        className="p-2.5 rounded-xl bg-white/5 border border-white/10 hover:bg-blue-600/10 hover:text-blue-500 hover:border-blue-600/30 transition-all"
        title="Facebookでシェア"
      >
        <Facebook size={18} />
      </button>
      <button 
        onClick={copyLink}
        className={`p-2.5 rounded-xl bg-white/5 border border-white/10 hover:bg-emerald-500/10 hover:text-emerald-400 hover:border-emerald-500/30 transition-all relative`}
        title="リンクをコピー"
      >
        <LinkIcon size={18} />
        {copied && (
          <span className="absolute -top-8 left-1/2 -translate-x-1/2 px-2 py-1 bg-emerald-500 text-white text-[10px] font-bold rounded shadow-lg">
            Copied!
          </span>
        )}
      </button>
    </div>
  );
}
