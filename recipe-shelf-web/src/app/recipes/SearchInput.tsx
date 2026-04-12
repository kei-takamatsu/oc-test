'use client'

import { Search } from 'lucide-react'
import { useRouter, useSearchParams } from 'next/navigation'
import { useState, useEffect } from 'react'

export default function SearchInput() {
  const router = useRouter()
  const searchParams = useSearchParams()
  const [query, setQuery] = useState(searchParams.get('q') || '')

  useEffect(() => {
    const timeoutId = setTimeout(() => {
      const currentQuery = searchParams.get('q') || ''
      if (query !== currentQuery) {
        if (query) {
          router.push(`/recipes?q=${encodeURIComponent(query)}`)
        } else {
          router.push('/recipes')
        }
      }
    }, 300)

    return () => clearTimeout(timeoutId)
  }, [query, router, searchParams])

  return (
    <div className="relative">
      <Search className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" size={20} />
      <input 
        type="text" 
        placeholder="レシピを探す..." 
        value={query}
        onChange={(e) => setQuery(e.target.value)}
        className="w-full pl-10 pr-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all shadow-sm"
      />
    </div>
  )
}
