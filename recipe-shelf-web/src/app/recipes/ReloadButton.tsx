'use client'

import { useRouter } from 'next/navigation'
import { RefreshCw } from 'lucide-react'
import { useState } from 'react'

export function ReloadButton() {
  const router = useRouter()
  const [spinning, setSpinning] = useState(false)

  const handleReload = () => {
    setSpinning(true)
    router.refresh()
    setTimeout(() => setSpinning(false), 1000)
  }

  return (
    <button 
      onClick={handleReload} 
      className="p-2 mr-1 text-gray-500 hover:text-orange-500 transition-all active:scale-95 bg-orange-50 hover:bg-orange-100 rounded-full"
      title="リストを更新"
    >
      <RefreshCw size={22} className={spinning ? 'animate-spin' : ''} />
    </button>
  )
}
