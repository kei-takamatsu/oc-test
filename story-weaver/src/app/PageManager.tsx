'use client';
import React from 'react';
import { Upload, X, ImageIcon } from 'lucide-react';
import { BookPage, PageType, PAGE_TYPE_LABELS } from './types';

interface Props {
  pages: BookPage[];
  currentPage: number;
  onUpdatePage: (index: number, updates: Partial<BookPage>) => void;
  onPageImageUpload: (index: number, file: File) => void;
  onRemovePageImage: (index: number) => void;
}

export default function PageManager({ pages, currentPage, onUpdatePage, onPageImageUpload, onRemovePageImage }: Props) {
  const page = pages[currentPage];
  if (!page) return null;

  return (
    <div style={{ background: '#fafafa', borderRadius: '12px', padding: '1.2rem', border: '1px solid #edf2f7' }}>
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '1rem' }}>
        <span style={{ fontWeight: 600, fontSize: '0.85rem', color: '#4a5568' }}>
          ページ {currentPage + 1} 設定
        </span>
        <select
          value={page.type}
          onChange={(e) => onUpdatePage(currentPage, { type: e.target.value as PageType })}
          style={{
            padding: '0.3rem 0.6rem',
            borderRadius: '6px',
            border: '1px solid #e2e8f0',
            fontSize: '0.8rem',
            background: 'white',
            color: '#4a5568',
            cursor: 'pointer'
          }}
        >
          {Object.entries(PAGE_TYPE_LABELS).map(([key, label]) => (
            <option key={key} value={key}>{label}</option>
          ))}
        </select>
      </div>

      {/* Image toggle */}
      <div style={{ display: 'flex', alignItems: 'center', gap: '0.8rem', marginBottom: '0.8rem' }}>
        <label style={{ display: 'flex', alignItems: 'center', gap: '0.4rem', fontSize: '0.8rem', color: '#718096', cursor: 'pointer' }}>
          <input
            type="checkbox"
            checked={page.hasImage}
            onChange={(e) => onUpdatePage(currentPage, { hasImage: e.target.checked })}
            style={{ accentColor: 'var(--primary)' }}
          />
          画像あり
        </label>
      </div>

      {/* Custom image upload for this page */}
      {page.hasImage && (
        <div style={{ display: 'flex', alignItems: 'center', gap: '0.6rem' }}>
          {page.customImageUrl ? (
            <div style={{ position: 'relative', width: '50px', height: '50px' }}>
              <img src={page.customImageUrl} style={{ width: '100%', height: '100%', objectFit: 'cover', borderRadius: '6px', border: '2px solid var(--primary)' }} alt="" />
              <button onClick={() => onRemovePageImage(currentPage)} style={{ position: 'absolute', top: '-5px', right: '-5px', width: '18px', height: '18px', borderRadius: '50%', background: 'var(--accent)', color: 'white', display: 'flex', alignItems: 'center', justifyContent: 'center', padding: 0 }}>
                <X size={10} />
              </button>
            </div>
          ) : (
            <label style={{ display: 'flex', alignItems: 'center', gap: '0.4rem', padding: '0.4rem 0.8rem', borderRadius: '6px', border: '1px dashed #cbd5e0', cursor: 'pointer', fontSize: '0.75rem', color: '#a0aec0' }}>
              <Upload size={14} /> 画像を指定
              <input type="file" hidden accept="image/*" onChange={(e) => {
                const f = e.target.files?.[0];
                if (f) onPageImageUpload(currentPage, f);
                e.target.value = '';
              }} />
            </label>
          )}
          <span style={{ fontSize: '0.7rem', color: '#cbd5e0' }}>
            {page.customImageUrl ? '手動指定済' : page.imageUrl ? 'AI生成済' : '未生成'}
          </span>
        </div>
      )}
    </div>
  );
}
