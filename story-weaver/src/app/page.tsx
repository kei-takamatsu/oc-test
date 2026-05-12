'use client';

import React, { useState, useEffect, useCallback } from 'react';
import { Upload, Image as ImageIcon, FileText, Printer, BookOpen, ChevronLeft, ChevronRight, Download, X, Ruler, Sparkles, Loader2 } from 'lucide-react';
import jsPDF from 'jspdf';
import PageManager from './PageManager';
import { BookPage, OUTPUT_PAPERS, parseStoryToPages, createPageId } from './types';
import NarrationPlayer from './NarrationPlayer';

export default function Home() {
  const [affinity, setAffinity] = useState(5);
  const [overlayOpacity, setOverlayOpacity] = useState(75);
  const [story, setStory] = useState('むかしむかし、あるところにふわふわのねこがいました。P!ねこは月の上にすわって、星たちとおしゃべりするのが大好きでした。P!今夜は特別に、遠くの銀河まで冒険に出かけることにしました。');
  const [style, setStyle] = useState('watercolor');
  const [currentPage, setCurrentPage] = useState(0);
  const [referenceImages, setReferenceImages] = useState<string[]>([]);
  const [pageW, setPageW] = useState(148);
  const [pageH, setPageH] = useState(148);
  const [outputPaper, setOutputPaper] = useState('a4');
  const [bookPages, setBookPages] = useState<BookPage[]>(() => parseStoryToPages('むかしむかし、あるところにふわふわのねこがいました。P!ねこは月の上にすわって、星たちとおしゃべりするのが大好きでした。P!今夜は特別に、遠くの銀河まで冒険に出かけることにしました。'));
  const [isGenerating, setIsGenerating] = useState(false);
  const [generateProgress, setGenerateProgress] = useState('');

  // Sync bookPages text from story
  useEffect(() => {
    const segs = story.split('P!').map(s => s.trim()).filter(s => s !== '');
    setBookPages(prev => segs.map((text, i) => prev[i] ? { ...prev[i], text } : { id: createPageId(), type: 'content', text, hasImage: true }));
  }, [story]);

  const pages = story.split('P!').map(p => p.trim()).filter(p => p !== '');
  
  // Ensure currentPage is valid if story changes
  useEffect(() => {
    if (currentPage >= pages.length) {
      setCurrentPage(Math.max(0, pages.length - 1));
    }
  }, [pages.length, currentPage]);

  const handleImageUpload = (e: React.ChangeEvent<HTMLInputElement>) => {
    const files = e.target.files;
    if (files) {
      const newImages = Array.from(files).map(file => URL.createObjectURL(file));
      setReferenceImages(prev => [...prev, ...newImages].slice(0, 3)); // Max 3
    }
    // Reset input so the same file can be re-selected
    e.target.value = '';
  };

  const removeReferenceImage = useCallback((index: number) => {
    setReferenceImages(prev => {
      const removed = prev[index];
      if (removed) URL.revokeObjectURL(removed);
      return prev.filter((_, i) => i !== index);
    });
  }, []);

  const generateBook = async () => {
    if (pages.length === 0) return;
    setIsGenerating(true);
    setGenerateProgress(`${pages.length}ページを生成中...`);
    try {
      const res = await fetch('/api/generate', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ pages, style, referenceImages }),
      });
      const data = await res.json();
      if (data.success) {
        setBookPages(prev => prev.map((bp, i) => {
          const gen = data.pages.find((p: { pageIndex: number }) => p.pageIndex === i);
          return gen ? { ...bp, imageUrl: gen.imageUrl } : bp;
        }));
        setCurrentPage(0);
        setGenerateProgress('生成完了！');
      } else {
        setGenerateProgress(`エラー: ${data.error}`);
      }
    } catch {
      setGenerateProgress('通信エラーが発生しました。');
    } finally {
      setIsGenerating(false);
      setTimeout(() => setGenerateProgress(''), 3000);
    }
  };

  const updateBookPage = (index: number, updates: Partial<BookPage>) => {
    setBookPages(prev => prev.map((p, i) => i === index ? { ...p, ...updates } : p));
  };

  const handlePageImageUpload = (index: number, file: File) => {
    const url = URL.createObjectURL(file);
    updateBookPage(index, { customImageUrl: url });
  };

  const removePageImage = (index: number) => {
    const bp = bookPages[index];
    if (bp?.customImageUrl) URL.revokeObjectURL(bp.customImageUrl);
    updateBookPage(index, { customImageUrl: undefined });
  };

  // Get the display image for a page
  const getPageImage = (index: number): string | undefined => {
    const bp = bookPages[index];
    if (!bp || !bp.hasImage) return undefined;
    return bp.customImageUrl || bp.imageUrl;
  };

  const exportToPDF = async () => {
    const paper = OUTPUT_PAPERS[outputPaper];
    const doc = new jsPDF({
      orientation: paper.width > paper.height ? 'landscape' : 'portrait',
      unit: 'mm',
      format: [paper.width, paper.height]
    });

    const pageWidth = paper.width;
    const pageHeight = paper.height;
    const bleed = 3;

    // Load the sample image as base64 for embedding
    let imgData: string | null = null;
    try {
      const response = await fetch('/sample-page.png');
      const blob = await response.blob();
      imgData = await new Promise<string>((resolve) => {
        const reader = new FileReader();
        reader.onload = () => resolve(reader.result as string);
        reader.readAsDataURL(blob);
      });
    } catch {
      // If image loading fails, continue without image
    }

    pages.forEach((pageText, index) => {
      if (index > 0) doc.addPage();

      // Draw the actual image as background
      if (imgData) {
        doc.addImage(imgData, 'PNG', 0, 0, pageWidth, pageHeight);
      } else {
        doc.setFillColor(250, 248, 240);
        doc.rect(0, 0, pageWidth, pageHeight, 'F');
      }

      // ===== Draw Crop Marks (トンボ) =====
      doc.setDrawColor(0, 0, 0);
      doc.setLineWidth(0.15);
      
      const markLen = 10; // Length of crop mark lines
      const gap = 1;      // Gap between inner/outer marks

      // --- Corner Marks (コーナートンボ) ---
      // Top Left - Inner
      doc.line(bleed, 0, bleed, bleed - gap);
      doc.line(0, bleed, bleed - gap, bleed);
      // Top Left - Outer (at page edge, extending outward conceptually)

      // Top Right - Inner
      doc.line(pageWidth - bleed, 0, pageWidth - bleed, bleed - gap);
      doc.line(pageWidth, bleed, pageWidth - bleed + gap, bleed);

      // Bottom Left - Inner
      doc.line(bleed, pageHeight, bleed, pageHeight - bleed + gap);
      doc.line(0, pageHeight - bleed, bleed - gap, pageHeight - bleed);

      // Bottom Right - Inner
      doc.line(pageWidth - bleed, pageHeight, pageWidth - bleed, pageHeight - bleed + gap);
      doc.line(pageWidth, pageHeight - bleed, pageWidth - bleed + gap, pageHeight - bleed);

      // --- Center Marks (センタートンボ) ---
      const cx = pageWidth / 2;
      const cy = pageHeight / 2;

      // Top center
      doc.line(cx, 0, cx, markLen);
      // Bottom center
      doc.line(cx, pageHeight, cx, pageHeight - markLen);
      // Left center
      doc.line(0, cy, markLen, cy);
      // Right center
      doc.line(pageWidth, cy, pageWidth - markLen, cy);

      // Center crosshair
      doc.setLineWidth(0.1);
      doc.circle(cx, cy, 3);
      doc.line(cx - 5, cy, cx + 5, cy);
      doc.line(cx, cy - 5, cx, cy + 5);

      // ===== Text rendering =====
      doc.setTextColor(40, 40, 40);
      doc.setFontSize(14);
      
      const margin = bleed + 15;
      const splitText = doc.splitTextToSize(pageText, pageWidth - margin * 2);
      
      if (affinity <= 2) {
        // Text at bottom with white background
        doc.setFillColor(255, 255, 255);
        doc.rect(bleed, pageHeight * 0.72, pageWidth - bleed * 2, pageHeight * 0.25, 'F');
        doc.text(splitText, pageWidth / 2, pageHeight * 0.8, { align: 'center' });
      } else if (affinity <= 5) {
        // White overlay at bottom for text readability
        doc.setFillColor(245, 245, 245);
        doc.roundedRect(margin, pageHeight * 0.75, pageWidth - margin * 2, pageHeight * 0.2, 4, 4, 'F');
        doc.setTextColor(40, 40, 40);
        doc.text(splitText, pageWidth / 2, pageHeight * 0.82, { align: 'center' });
      } else {
        // Text centered
        doc.setTextColor(255, 255, 255);
        doc.text(splitText, pageWidth / 2, pageHeight / 2, { align: 'center' });
      }
    });

    doc.save(`story-weaver-${pageW}x${pageH}-${outputPaper}.pdf`);
  };

  // Dynamic styles based on affinity
  const getAffinityStyles = () => {
    if (affinity <= 2) {
      return {
        container: { flexDirection: 'column' as const },
        imageWrap: { width: '100%', height: '70%', borderRadius: '12px 12px 0 0' },
        textWrap: { 
          width: '100%', 
          height: '30%', 
          background: 'white', 
          color: '#333',
          padding: '2rem',
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
          textAlign: 'center' as const,
          fontSize: '1.2rem',
          borderTop: '1px solid #eee'
        }
      };
    } else if (affinity <= 5) {
      return {
        container: { position: 'relative' as const },
        imageWrap: { width: '100%', height: '100%', borderRadius: '16px' },
        textWrap: { 
          position: 'absolute' as const,
          bottom: '8%',
          left: '5%',
          right: '5%',
          background: `rgba(255, 255, 255, ${overlayOpacity / 100})`,
          backdropFilter: 'blur(12px)',
          padding: '1.8rem',
          borderRadius: '16px',
          color: '#1a202c',
          boxShadow: '0 8px 32px rgba(0,0,0,0.12)',
          fontSize: '1.3rem',
          border: '1px solid rgba(255,255,255,0.4)'
        }
      };
    } else if (affinity <= 8) {
      return {
        container: { position: 'relative' as const },
        imageWrap: { width: '100%', height: '100%', borderRadius: '24px' },
        textWrap: { 
          position: 'absolute' as const,
          top: '50%',
          left: '50%',
          transform: 'translate(-50%, -50%)',
          width: '85%',
          color: 'white',
          textShadow: '0 2px 15px rgba(0,0,0,0.9), 0 0 30px rgba(0,0,0,0.6)',
          fontSize: '1.6rem',
          textAlign: 'center' as const,
          fontWeight: 600,
          letterSpacing: '0.05em'
        }
      };
    } else {
      return {
        container: { position: 'relative' as const },
        imageWrap: { width: '100%', height: '100%', borderRadius: '32px' },
        textWrap: { 
          position: 'absolute' as const,
          bottom: '8%',
          right: '8%',
          width: '60%',
          color: '#2d3436',
          background: 'linear-gradient(to left, rgba(255,255,255,0.4), transparent)',
          padding: '1rem',
          fontSize: '1.8rem',
          textAlign: 'right' as const,
          fontFamily: 'var(--font-story)',
          letterSpacing: '0.12em',
          fontWeight: 500,
          textShadow: '1px 1px 0 rgba(255,255,255,0.8), -1px -1px 0 rgba(255,255,255,0.8), 0 0 10px rgba(255,255,255,0.5)'
        }
      };
    }
  };

  const styles = getAffinityStyles();

  return (
    <main style={{ minHeight: '100vh', display: 'flex', background: '#fdfaf5' }}>
      {/* Sidebar Controls */}
      <aside style={{ 
        width: '420px', 
        padding: '2.5rem', 
        background: 'white', 
        borderRight: '1px solid #e2e8f0',
        display: 'flex',
        flexDirection: 'column',
        gap: '2rem',
        boxShadow: '4px 0 20px rgba(0,0,0,0.02)',
        zIndex: 10,
        overflowY: 'auto'
      }}>
        <div className="animate-fade-in">
          <div style={{ display: 'flex', alignItems: 'center', gap: '0.8rem', marginBottom: '0.4rem' }}>
            <div style={{ padding: '0.5rem', background: 'var(--primary)', borderRadius: '10px', color: 'white' }}>
              <BookOpen size={24} />
            </div>
            <h1 style={{ fontSize: '1.8rem', color: 'var(--primary)' }}>StoryWeaver</h1>
          </div>
          <p style={{ fontSize: '0.85rem', color: '#718096' }}>Professional AI Picture Book Studio</p>
        </div>

        {/* Affinity Slider */}
        <div className="animate-fade-in" style={{ animationDelay: '0.1s' }}>
          <label style={{ display: 'flex', justifyContent: 'space-between', fontWeight: 600, marginBottom: '1rem', fontSize: '0.9rem', color: '#4a5568' }}>
            <span style={{ display: 'flex', alignItems: 'center', gap: '0.5rem' }}><ImageIcon size={16} /> 親和性 (Affinity)</span>
            <span style={{ color: 'var(--primary)', background: '#f0ebf8', padding: '0.1rem 0.6rem', borderRadius: '10px' }}>{affinity}</span>
          </label>
          <input 
            type="range" 
            min="0" 
            max="10" 
            value={affinity} 
            onChange={(e) => setAffinity(parseInt(e.target.value))}
            style={{ width: '100%', height: '6px', accentColor: 'var(--primary)', cursor: 'pointer' }}
          />
          <div style={{ display: 'flex', justifyContent: 'space-between', marginTop: '0.6rem', fontSize: '0.7rem', color: '#a0aec0', fontWeight: 500 }}>
            <span>セパレート</span>
            <span>ビジュアル統合</span>
          </div>
        </div>

        {/* Overlay Opacity (吹き出し透過度) */}
        {affinity >= 3 && affinity <= 7 && (
          <div className="animate-fade-in" style={{ animationDelay: '0.15s' }}>
            <label style={{ display: 'flex', justifyContent: 'space-between', fontWeight: 600, marginBottom: '0.6rem', fontSize: '0.85rem', color: '#4a5568' }}>
              <span>吹き出し透過度</span>
              <span style={{ color: 'var(--primary)', background: '#f0ebf8', padding: '0.1rem 0.5rem', borderRadius: '10px', fontSize: '0.8rem' }}>{overlayOpacity}%</span>
            </label>
            <input type="range" min="0" max="100" value={overlayOpacity} onChange={(e) => setOverlayOpacity(parseInt(e.target.value))} style={{ width: '100%', height: '5px', accentColor: 'var(--secondary)', cursor: 'pointer' }} />
            <div style={{ display: 'flex', justifyContent: 'space-between', marginTop: '0.4rem', fontSize: '0.65rem', color: '#cbd5e0' }}>
              <span>透明</span><span>不透明</span>
            </div>
          </div>
        )}

        {/* Story Editor */}
        <div className="animate-fade-in" style={{ animationDelay: '0.2s' }}>
          <label style={{ display: 'flex', alignItems: 'center', gap: '0.5rem', fontWeight: 600, marginBottom: '0.8rem', fontSize: '0.9rem', color: '#4a5568' }}>
            <FileText size={16} /> ストーリー構成
          </label>
          <textarea 
            value={story}
            onChange={(e) => setStory(e.target.value)}
            style={{ 
              width: '100%', 
              height: '160px', 
              padding: '1.2rem', 
              borderRadius: 'var(--radius-sm)', 
              border: '1.5px solid #edf2f7',
              fontSize: '0.9rem',
              lineHeight: '1.6',
              resize: 'none',
              outline: 'none',
              transition: 'all 0.2s',
              fontFamily: 'inherit',
              background: '#fcfcfc'
            }}
            placeholder="昔々あるところに... P! そこで出会ったのは..."
          />
          <p style={{ fontSize: '0.7rem', color: '#cbd5e0', marginTop: '0.6rem' }}>「P!」で改ページ ・ 現在 {pages.length} ページ</p>
        </div>

        {/* Reference Images */}
        <div className="animate-fade-in" style={{ animationDelay: '0.3s' }}>
          <label style={{ display: 'flex', alignItems: 'center', gap: '0.5rem', fontWeight: 600, marginBottom: '1rem', fontSize: '0.9rem', color: '#4a5568' }}>
            <Upload size={16} /> 参考テイスト画像 (分析用)
          </label>
          <div style={{ display: 'flex', gap: '0.8rem', marginBottom: '0.8rem' }}>
            {referenceImages.map((img, i) => (
              <div key={i} style={{ position: 'relative', width: '70px', height: '70px' }}>
                <div style={{ width: '100%', height: '100%', borderRadius: '10px', overflow: 'hidden', border: '2px solid var(--primary)' }}>
                  <img src={img} style={{ width: '100%', height: '100%', objectFit: 'cover' }} alt={`Reference ${i + 1}`} />
                </div>
                <button
                  onClick={() => removeReferenceImage(i)}
                  style={{
                    position: 'absolute',
                    top: '-6px',
                    right: '-6px',
                    width: '20px',
                    height: '20px',
                    borderRadius: '50%',
                    background: 'var(--accent)',
                    color: 'white',
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    padding: 0,
                    boxShadow: '0 2px 6px rgba(0,0,0,0.2)'
                  }}
                >
                  <X size={12} />
                </button>
              </div>
            ))}
            {referenceImages.length < 3 && (
              <label style={{ 
                width: '70px', 
                height: '70px', 
                borderRadius: '10px', 
                border: '2px dashed #e2e8f0', 
                display: 'flex', 
                alignItems: 'center', 
                justifyContent: 'center', 
                cursor: 'pointer',
                color: '#a0aec0',
                transition: 'all 0.2s'
              }}>
                <Upload size={20} />
                <input type="file" hidden accept="image/*" multiple onChange={handleImageUpload} />
              </label>
            )}
          </div>
          <p style={{ fontSize: '0.7rem', color: '#a0aec0' }}>最大3枚 ・ AIが画風を分析して再現します</p>
        </div>

        {/* Art Style Grid */}
        <div className="animate-fade-in" style={{ animationDelay: '0.4s' }}>
          <label style={{ fontWeight: 600, marginBottom: '1rem', display: 'block', fontSize: '0.9rem', color: '#4a5568' }}>アートスタイル</label>
          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '0.8rem' }}>
            {['Watercolor', 'Oil Painting', 'Digital Art', 'Pencil'].map((s) => (
              <button 
                key={s}
                onClick={() => setStyle(s.toLowerCase())}
                style={{ 
                  padding: '0.8rem', 
                  borderRadius: 'var(--radius-sm)', 
                  fontSize: '0.85rem',
                  background: style === s.toLowerCase() ? 'var(--primary)' : '#f7fafc',
                  color: style === s.toLowerCase() ? 'white' : '#4a5568',
                  border: style === s.toLowerCase() ? 'none' : '1px solid #edf2f7',
                  fontWeight: style === s.toLowerCase() ? 600 : 500
                }}
              >
                {s}
              </button>
            ))}
          </div>
        </div>

        {/* Page Size (台紙サイズ - custom) */}
        <div className="animate-fade-in" style={{ animationDelay: '0.5s' }}>
          <label style={{ display: 'flex', alignItems: 'center', gap: '0.5rem', fontWeight: 600, marginBottom: '0.8rem', fontSize: '0.9rem', color: '#4a5568' }}>
            <Ruler size={16} /> 台紙サイズ (mm)
          </label>
          <div style={{ display: 'flex', gap: '0.5rem', alignItems: 'center' }}>
            <input type="number" value={pageW} onChange={(e) => setPageW(Number(e.target.value))} style={{ width: '80px', padding: '0.6rem', borderRadius: '6px', border: '1.5px solid #edf2f7', fontSize: '0.9rem', textAlign: 'center' }} />
            <span style={{ color: '#a0aec0', fontWeight: 600 }}>×</span>
            <input type="number" value={pageH} onChange={(e) => setPageH(Number(e.target.value))} style={{ width: '80px', padding: '0.6rem', borderRadius: '6px', border: '1.5px solid #edf2f7', fontSize: '0.9rem', textAlign: 'center' }} />
            <span style={{ fontSize: '0.75rem', color: '#a0aec0' }}>mm</span>
          </div>
          <div style={{ display: 'flex', gap: '0.4rem', marginTop: '0.6rem', flexWrap: 'wrap' }}>
            {[{l:'120²',w:120,h:120},{l:'148²',w:148,h:148},{l:'A5',w:148,h:210},{l:'B5',w:182,h:257}].map(p => (
              <button key={p.l} onClick={() => { setPageW(p.w); setPageH(p.h); }} style={{ padding: '0.3rem 0.6rem', borderRadius: '4px', fontSize: '0.7rem', background: pageW===p.w && pageH===p.h ? 'var(--primary)' : '#f7fafc', color: pageW===p.w && pageH===p.h ? 'white' : '#718096', border: '1px solid #edf2f7' }}>{p.l}</button>
            ))}
          </div>
        </div>

        {/* Output Paper */}
        <div className="animate-fade-in" style={{ animationDelay: '0.55s' }}>
          <label style={{ fontWeight: 600, marginBottom: '0.6rem', display: 'block', fontSize: '0.85rem', color: '#4a5568' }}>出力用紙</label>
          <select value={outputPaper} onChange={(e) => setOutputPaper(e.target.value)} style={{ width: '100%', padding: '0.6rem', borderRadius: '6px', border: '1.5px solid #edf2f7', fontSize: '0.85rem', background: '#fcfcfc', cursor: 'pointer' }}>
            {Object.entries(OUTPUT_PAPERS).map(([key, { label }]) => (
              <option key={key} value={key}>{label}</option>
            ))}
          </select>
        </div>

        {/* Per-Page Settings */}
        <div className="animate-fade-in" style={{ animationDelay: '0.6s' }}>
          <PageManager pages={bookPages} currentPage={currentPage} onUpdatePage={updateBookPage} onPageImageUpload={handlePageImageUpload} onRemovePageImage={removePageImage} />
        </div>

        {/* Generate Button - PRIMARY CTA */}
        <div style={{ paddingTop: '0.5rem' }}>
          <button 
            onClick={generateBook}
            disabled={isGenerating || pages.length === 0}
            style={{ 
              width: '100%', 
              padding: '1.4rem', 
              borderRadius: 'var(--radius-md)', 
              background: isGenerating ? '#a0aec0' : 'linear-gradient(135deg, #f6d365, #fda085)',
              color: 'white',
              fontWeight: 700,
              fontSize: '1.1rem',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              gap: '0.8rem',
              boxShadow: isGenerating ? 'none' : '0 6px 25px rgba(253, 160, 133, 0.5)',
              cursor: isGenerating ? 'wait' : 'pointer'
            }}>
            {isGenerating ? <Loader2 size={22} style={{ animation: 'spin 1s linear infinite' }} /> : <Sparkles size={22} />}
            {isGenerating ? '生成中...' : '絵本を生成する'}
          </button>
          {generateProgress && (
            <p style={{ textAlign: 'center', fontSize: '0.8rem', marginTop: '0.8rem', color: 'var(--primary)', fontWeight: 500 }}>
              {generateProgress}
            </p>
          )}
        </div>

        {/* Export Buttons */}
        <div style={{ marginTop: 'auto', paddingTop: '1.5rem', borderTop: '1px solid #f0f0f0' }}>
          <button 
            onClick={exportToPDF}
            style={{ 
              width: '100%', 
              padding: '1.2rem', 
              borderRadius: 'var(--radius-md)', 
              background: 'linear-gradient(135deg, var(--primary), var(--accent))',
              color: 'white',
              fontWeight: 600,
              fontSize: '1rem',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              gap: '0.8rem',
              boxShadow: '0 4px 20px rgba(142, 125, 190, 0.4)',
              marginBottom: '1rem'
            }}>
            <Printer size={20} /> 印刷用PDF (トンボ付) を書き出す
          </button>
          <div style={{ display: 'flex', gap: '0.5rem' }}>
            <button style={{ flex: 1, padding: '0.8rem', borderRadius: 'var(--radius-sm)', background: '#f7fafc', color: '#4a5568', fontSize: '0.85rem', fontWeight: 500, border: '1px solid #edf2f7', display: 'flex', alignItems: 'center', justifyContent: 'center', gap: '0.4rem' }}>
              <Download size={16} /> Kindle EPUB
            </button>
            <button style={{ flex: 1, padding: '0.8rem', borderRadius: 'var(--radius-sm)', background: '#f7fafc', color: '#4a5568', fontSize: '0.85rem', fontWeight: 500, border: '1px solid #edf2f7' }}>
              下書き保存
            </button>
          </div>
        </div>
      </aside>

      {/* Preview Area */}
      <section style={{ flex: 1, padding: '3rem', display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center', background: '#f8f5f0' }}>
        <p style={{ fontSize: '0.75rem', color: '#a0aec0', marginBottom: '1rem' }}>
          台紙: {pageW}×{pageH}mm → 出力: {OUTPUT_PAPERS[outputPaper]?.label}
        </p>
        <div style={{ 
          width: pageW >= pageH ? '560px' : `${560 * (pageW / pageH)}px`,
          height: pageW >= pageH ? `${560 * (pageH / pageW)}px` : '560px',
          ...styles.container,
          boxShadow: '0 50px 100px rgba(0,0,0,0.18)',
          overflow: 'hidden',
          transition: 'all 0.6s cubic-bezier(0.23, 1, 0.32, 1)',
          backgroundColor: 'white',
          position: 'relative',
          borderRadius: '24px'
        }}>
          <div style={{ ...styles.imageWrap, position: 'relative', overflow: 'hidden', transition: 'all 0.6s' }}>
            {getPageImage(currentPage) ? (
              <img 
                src={getPageImage(currentPage)} 
                alt={`Page ${currentPage + 1}`} 
                style={{ width: '100%', height: '100%', objectFit: 'cover' }}
              />
            ) : bookPages[currentPage]?.hasImage === false ? (
              <div style={{ width: '100%', height: '100%', display: 'flex', alignItems: 'center', justifyContent: 'center', background: 'white', color: '#cbd5e0' }}>
                <p style={{ fontSize: '0.85rem' }}>画像なしページ</p>
              </div>
            ) : (
              <div style={{ width: '100%', height: '100%', display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center', background: 'linear-gradient(135deg, #f0ebf8, #fdf2e9)', color: '#a0aec0' }}>
                <Sparkles size={48} style={{ marginBottom: '1rem', opacity: 0.5 }} />
                <p style={{ fontSize: '0.9rem' }}>「絵本を生成する」を押してください</p>
              </div>
            )}
          </div>
          <div style={{ ...styles.textWrap, transition: 'all 0.6s' }}>
            <p style={{ fontFamily: 'var(--font-story)', whiteSpace: 'pre-wrap' }}>
              {pages[currentPage] || 'ストーリーがありません。'}
            </p>
          </div>
        </div>

        {/* Pagination Controls */}
        <div style={{ marginTop: '3.5rem', display: 'flex', alignItems: 'center', gap: '2.5rem' }}>
          <button 
            disabled={currentPage === 0}
            onClick={() => setCurrentPage(prev => prev - 1)}
            style={{ 
              width: '48px',
              height: '48px',
              borderRadius: '50%', 
              background: 'white', 
              color: currentPage === 0 ? '#cbd5e0' : 'var(--primary)',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              boxShadow: '0 4px 15px rgba(0,0,0,0.08)',
              opacity: currentPage === 0 ? 0.5 : 1,
              cursor: currentPage === 0 ? 'default' : 'pointer'
            }}
          >
            <ChevronLeft size={24} />
          </button>
          
          <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center' }}>
            <div style={{ fontSize: '1.2rem', fontWeight: 600, color: '#2d3436' }}>
              {currentPage + 1} <span style={{ color: '#a0aec0', fontWeight: 400, fontSize: '0.9rem' }}>/ {pages.length}</span>
            </div>
            <div style={{ height: '4px', width: '60px', background: '#edf2f7', borderRadius: '2px', marginTop: '8px', position: 'relative' }}>
              <div style={{ 
                position: 'absolute', 
                left: 0, 
                top: 0, 
                height: '100%', 
                width: `${((currentPage + 1) / pages.length) * 100}%`, 
                background: 'var(--primary)',
                borderRadius: '2px',
                transition: 'width 0.3s'
              }} />
            </div>
          </div>

          <button 
            disabled={currentPage === pages.length - 1}
            onClick={() => setCurrentPage(prev => prev + 1)}
            style={{ 
              width: '48px',
              height: '48px',
              borderRadius: '50%', 
              background: 'white', 
              color: currentPage === pages.length - 1 ? '#cbd5e0' : 'var(--primary)',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              boxShadow: '0 4px 15px rgba(0,0,0,0.08)',
              opacity: currentPage === pages.length - 1 ? 0.5 : 1,
              cursor: currentPage === pages.length - 1 ? 'default' : 'pointer'
            }}
          >
            <ChevronRight size={24} />
          </button>
        </div>

        {/* Narration Player */}
        <NarrationPlayer pages={pages} currentPage={currentPage} onPageChange={setCurrentPage} />
      </section>
    </main>
  );
}
