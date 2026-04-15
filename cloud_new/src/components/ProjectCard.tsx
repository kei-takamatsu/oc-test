import { ArrowRight } from "lucide-react";
import Link from "next/link";
import FavoriteButton from "./FavoriteButton";

interface ProjectCardProps {
  id: number;
  projectName: string;
  description: string;
  goalAmount: number;
  collectedAmount: number;
  backers: number;
  collectionEndDate: Date | null;
  imageUrl: string | null;
  category?: { name: string } | null;
  area?: { name: string } | null;
  initialIsFavorite?: boolean;
}

export default function ProjectCard({
  id,
  projectName,
  description,
  goalAmount,
  collectedAmount,
  backers,
  collectionEndDate,
  imageUrl,
  category,
  area,
  initialIsFavorite = false,
}: ProjectCardProps) {
  const progress = Math.min(Math.round((collectedAmount / goalAmount) * 100), 100);
  
  const daysRemaining = collectionEndDate 
    ? Math.max(0, Math.ceil((collectionEndDate.getTime() - Date.now()) / (1000 * 60 * 60 * 24)))
    : 0;

  const isEnded = collectionEndDate ? collectionEndDate < new Date() : false;

  return (
    <div className="group glass rounded-3xl overflow-hidden hover-subtle flex flex-col h-full relative">
       {isEnded && (
        <div className="absolute top-4 left-4 z-20">
          <span className="px-3 py-1 rounded-full bg-pink-500 text-white text-[10px] font-black uppercase tracking-wider shadow-lg">
            募集終了
          </span>
        </div>
      )}
      <Link href={`/projects/${id}`} className="block h-full transition-transform hover:-translate-y-1">
      <div className="h-48 bg-slate-800 relative">
        {imageUrl ? (
          <img 
            src={imageUrl} 
            alt={projectName} 
            className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
          />
        ) : (
          <div className="w-full h-full bg-gradient-to-br from-indigo-900 to-purple-900" />
        )}
        <div className="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent" />
        
        <div className="absolute top-4 right-4 z-20">
          <FavoriteButton projectId={id} initialIsFavorite={initialIsFavorite} />
        </div>

        <div className="absolute bottom-4 left-4 flex gap-2">
          {category && (
            <span className="px-2 py-1 rounded bg-white/20 backdrop-blur-md text-[10px] font-bold text-white uppercase tracking-wider border border-white/20">
              {category.name}
            </span>
          )}
          {area && (
            <span className="px-2 py-1 rounded bg-indigo-500/80 backdrop-blur-md text-[10px] font-bold text-white uppercase tracking-wider border border-white/10">
              {area.name}
            </span>
          )}
        </div>
      </div>
      <div className="p-6 space-y-4 flex-grow flex flex-col">
        <h3 className="text-xl font-bold group-hover:text-primary transition-colors line-clamp-2">
          {projectName}
        </h3>
        <p className="text-sm text-slate-500 line-clamp-2">
          {description}
        </p>
        <div className="space-y-2 pt-4 mt-auto">
          <div className="flex justify-between text-xs font-medium">
            <span>達成率 {progress}%</span>
            <span>残り {daysRemaining}日</span>
          </div>
          <div className="h-1.5 w-full bg-slate-200 dark:bg-slate-800 rounded-full overflow-hidden">
            <div 
              className="h-full bg-gradient-to-r from-indigo-500 to-purple-500 transition-all duration-1000" 
              style={{ width: `${progress}%` }}
            />
          </div>
          <div className="flex justify-between items-center pt-2">
            <span className="font-bold">¥{collectedAmount.toLocaleString()}</span>
            <span className="text-xs text-slate-400">{backers}人の支援者が応援中</span>
          </div>
        </div>
      </div>
    </Link>
  </div>
  );
}
