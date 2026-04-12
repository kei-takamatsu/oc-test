'use client'

import { useState, useTransition } from 'react'
import {
  DndContext,
  closestCenter,
  KeyboardSensor,
  PointerSensor,
  TouchSensor,
  useSensor,
  useSensors,
  DragEndEvent,
} from '@dnd-kit/core'
import {
  arrayMove,
  SortableContext,
  sortableKeyboardCoordinates,
  rectSortingStrategy,
} from '@dnd-kit/sortable'
import SortableRecipeCard from './SortableRecipeCard'
import { updateRecipeOrder } from './actions'

export default function SortableRecipeGrid({ initialRecipes }: { initialRecipes: any[] }) {
  const [recipes, setRecipes] = useState(initialRecipes)
  const [isPending, startTransition] = useTransition()

  // 1. スマホ(Touch)でもスクロールできるようにする設定
  const sensors = useSensors(
    useSensor(PointerSensor, {
      activationConstraint: {
        distance: 5, // 5pxドラッグで判定（スマホで少し指が動いても許容）
      },
    }),
    useSensor(TouchSensor, {
      activationConstraint: {
        delay: 250, // 250ms長押しでドラッグ開始
        tolerance: 5, 
      },
    }),
    useSensor(KeyboardSensor, {
      coordinateGetter: sortableKeyboardCoordinates,
    })
  )

  const handleDragEnd = async (event: DragEndEvent) => {
    const { active, over } = event

    if (over && active.id !== over.id) {
      const oldIndex = recipes.findIndex((r) => r.id === active.id)
      const newIndex = recipes.findIndex((r) => r.id === over.id)

      const reordered = arrayMove(recipes, oldIndex, newIndex)
      setRecipes(reordered)

      startTransition(async () => {
        try {
          const orderedIds = reordered.map((r) => r.id).filter(Boolean)
          await updateRecipeOrder(orderedIds)
        } catch (e) {
          console.error('Failed to update order', e)
        }
      })
    }
  }

  if (!recipes || recipes.length === 0) {
    return (
      <div className="col-span-full py-12 text-center text-gray-500">
        <p>レシピがありません。</p>
        <p className="text-sm mt-1">「+追加」からレシピを登録してください。</p>
      </div>
    )
  }

  return (
    <DndContext 
      sensors={sensors} 
      collisionDetection={closestCenter} 
      onDragEnd={handleDragEnd}
    >
      <SortableContext items={recipes.map(r => r.id)} strategy={rectSortingStrategy}>
        <div className="grid grid-cols-2 sm:grid-cols-3 gap-4">
          {recipes.map((recipe) => (
            <SortableRecipeCard key={recipe.id} recipe={recipe} />
          ))}
        </div>
      </SortableContext>
    </DndContext>
  )
}
