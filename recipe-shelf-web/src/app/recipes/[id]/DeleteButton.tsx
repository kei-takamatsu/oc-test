'use client'

import { useState } from 'react'
import { useRouter } from 'next/navigation'
import { Trash2 } from 'lucide-react'
import { deleteRecipe } from '../actions'

export default function DeleteButton({ recipeId }: { recipeId: number }) {
  const [showConfirm, setShowConfirm] = useState(false)
  const [isDeleting, setIsDeleting] = useState(false)
  const router = useRouter()

  const handleDelete = async () => {
    setIsDeleting(true)
    try {
      await deleteRecipe(recipeId)
      router.push('/recipes')
    } catch (err) {
      console.error('Delete failed:', err)
      setIsDeleting(false)
      setShowConfirm(false)
    }
  }

  if (showConfirm) {
    return (
      <>
        {/* Overlay */}
        <div
          className="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4"
          onClick={() => !isDeleting && setShowConfirm(false)}
        >
          <div
            className="bg-white rounded-2xl shadow-2xl p-6 max-w-sm w-full animate-in"
            onClick={(e) => e.stopPropagation()}
          >
            <div className="flex items-center gap-3 mb-4">
              <div className="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                <Trash2 size={20} className="text-red-600" />
              </div>
              <h3 className="text-lg font-bold text-gray-900">レシピを削除</h3>
            </div>
            <p className="text-gray-600 text-sm mb-6">
              このレシピを削除しますか？この操作は取り消せません。
            </p>
            <div className="flex gap-3">
              <button
                onClick={() => setShowConfirm(false)}
                disabled={isDeleting}
                className="flex-1 py-2.5 px-4 rounded-xl border border-gray-200 text-gray-700 font-medium hover:bg-gray-50 transition-colors disabled:opacity-50"
              >
                キャンセル
              </button>
              <button
                onClick={handleDelete}
                disabled={isDeleting}
                className="flex-1 py-2.5 px-4 rounded-xl bg-red-500 text-white font-medium hover:bg-red-600 transition-colors disabled:opacity-50 flex items-center justify-center gap-2"
              >
                {isDeleting ? (
                  <>
                    <span className="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin" />
                    削除中...
                  </>
                ) : (
                  '削除する'
                )}
              </button>
            </div>
          </div>
        </div>
      </>
    )
  }

  return (
    <button
      onClick={() => setShowConfirm(true)}
      className="inline-flex items-center justify-center w-10 h-10 bg-black/30 backdrop-blur-md rounded-full text-white hover:bg-red-500/80 transition-colors"
      title="レシピを削除"
    >
      <Trash2 size={20} />
    </button>
  )
}
