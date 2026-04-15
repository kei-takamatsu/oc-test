"use client";

import { useState, useTransition } from "react";
import { Heart } from "lucide-react";
import { toggleFavoriteAction } from "@/lib/actions/favorite-actions";

interface FavoriteButtonProps {
  projectId: number;
  initialIsFavorite: boolean;
}

export default function FavoriteButton({ projectId, initialIsFavorite }: FavoriteButtonProps) {
  const [isFavorite, setIsFavorite] = useState(initialIsFavorite);
  const [isPending, startTransition] = useTransition();

  const handleToggle = async (e: React.MouseEvent) => {
    e.preventDefault();
    e.stopPropagation();
    
    // 楽観的更新
    setIsFavorite(!isFavorite);

    startTransition(async () => {
      try {
        await toggleFavoriteAction(projectId);
      } catch (error) {
        // エラー時は元に戻す
        setIsFavorite(isFavorite);
        console.error("Failed to toggle favorite:", error);
      }
    });
  };

  return (
    <button
      onClick={handleToggle}
      disabled={isPending}
      className={`p-3 rounded-2xl glass transition-all ${
        isFavorite 
          ? "bg-pink-500/20 text-pink-500 border-pink-500/30" 
          : "bg-white/5 text-slate-400 border-white/10 hover:bg-white/10"
      } border shadow-xl flex items-center justify-center`}
    >
      <Heart 
        size={24} 
        fill={isFavorite ? "currentColor" : "none"} 
        className={isPending ? "animate-pulse" : ""}
      />
    </button>
  );
}
