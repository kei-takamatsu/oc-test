'use client'

import { useFormStatus } from 'react-dom'
import { ReactNode } from 'react'

export function SubmitButton({ children, className, loadingText = "送信中..." }: { children: ReactNode, className?: string, loadingText?: string }) {
  const { pending } = useFormStatus()
  
  return (
    <button
      type="submit"
      disabled={pending}
      className={`${className} ${pending ? 'opacity-70 cursor-not-allowed' : ''}`}
    >
      {pending ? loadingText : children}
    </button>
  )
}
