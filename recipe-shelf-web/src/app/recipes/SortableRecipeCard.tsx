'use client'

import { useSortable } from '@dnd-kit/sortable'
import { CSS } from '@dnd-kit/utilities'
import Link from 'next/link'
import { Clock, GripHorizontal } from 'lucide-react'

export default function SortableRecipeCard({ recipe }: { recipe: any }) {
  const {
    attributes,
    listeners,
    setNodeRef,
    transform,
    transition,
    isDragging,
  } = useSortable({ id: recipe.id })

  const style = {
    transform: CSS.Transform.toString(transform),
    transition,
    zIndex: isDragging ? 10 : 1,
    opacity: isDragging ? 0.8 : 1,
  }

  return (
    <div ref={setNodeRef} style={style} className={`relative group block ${isDragging ? 'scale-105 shadow-xl' : ''}`}>
      {/* 
        ドラッグ用のハンドル 
        pointer-events-auto をつけて、Linkのクリック判定より優先させる。
        PCではHover時のみ表示、スマホでは常にうっすら表示でも良いが、今回は右上に配置
      */}
      <div 
        {...attributes} 
        {...listeners}
        className="absolute top-2 right-2 z-20 w-8 h-8 bg-white/80 backdrop-blur-sm rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-grab active:cursor-grabbing shadow-sm"
        title="長押しして移動"
      >
        <GripHorizontal size={16} className="text-gray-600" />
      </div>

      <Link href={`/recipes/${recipe.id}`} className="block">
        <div className="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
          <div className="aspect-square relative overflow-hidden bg-gray-100">
            <img
              src={recipe.image_local_path || 'https://images.unsplash.com/photo-1495521821757-a1efb6729352?auto=format&fit=crop&w=400&q=80'}
              alt={recipe.title}
              className="w-full h-full object-cover"
              draggable={false} // HTMLネイティブドラッグと競合しないように
            />
          </div>
          <div className="p-3">
            <h3 className="font-semibold text-gray-900 leading-tight line-clamp-2 text-sm">{recipe.title}</h3>
            <div className="mt-2 flex items-center gap-3 text-xs text-gray-500">
              {(recipe.prep_time || recipe.cook_time) && (
                <div className="flex items-center gap-1">
                  <Clock size={12} />
                  <span>{recipe.prep_time || recipe.cook_time}</span>
                </div>
              )}
            </div>
          </div>
        </div>
      </Link>
    </div>
  )
}
