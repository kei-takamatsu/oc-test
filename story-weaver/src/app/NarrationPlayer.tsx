'use client';
import React, { useState, useEffect, useRef, useCallback } from 'react';
import { Play, Pause, SkipForward, Volume2, VolumeX } from 'lucide-react';

interface Props {
  pages: string[];
  currentPage: number;
  onPageChange: (page: number) => void;
}

export default function NarrationPlayer({ pages, currentPage, onPageChange }: Props) {
  const [isPlaying, setIsPlaying] = useState(false);
  const [voices, setVoices] = useState<SpeechSynthesisVoice[]>([]);
  const [selectedVoice, setSelectedVoice] = useState('');
  const [rate, setRate] = useState(0.9);
  const [autoAdvance, setAutoAdvance] = useState(true);
  const utterRef = useRef<SpeechSynthesisUtterance | null>(null);

  useEffect(() => {
    const loadVoices = () => {
      const v = speechSynthesis.getVoices().filter(voice =>
        voice.lang.startsWith('ja') || voice.lang.startsWith('en')
      );
      setVoices(v);
      if (!selectedVoice && v.length > 0) {
        const ja = v.find(voice => voice.lang.startsWith('ja'));
        setSelectedVoice((ja || v[0]).name);
      }
    };
    loadVoices();
    speechSynthesis.onvoiceschanged = loadVoices;
    return () => { speechSynthesis.cancel(); };
  }, [selectedVoice]);

  const speak = useCallback((text: string, pageIndex: number) => {
    speechSynthesis.cancel();
    const utter = new SpeechSynthesisUtterance(text);
    const voice = voices.find(v => v.name === selectedVoice);
    if (voice) utter.voice = voice;
    utter.rate = rate;
    utter.pitch = 1.1;
    utter.onend = () => {
      if (autoAdvance && pageIndex < pages.length - 1) {
        setTimeout(() => {
          onPageChange(pageIndex + 1);
          speak(pages[pageIndex + 1], pageIndex + 1);
        }, 800);
      } else {
        setIsPlaying(false);
      }
    };
    utterRef.current = utter;
    speechSynthesis.speak(utter);
  }, [voices, selectedVoice, rate, autoAdvance, pages, onPageChange]);

  const handlePlay = () => {
    if (isPlaying) {
      speechSynthesis.cancel();
      setIsPlaying(false);
    } else {
      setIsPlaying(true);
      speak(pages[currentPage] || '', currentPage);
    }
  };

  const handleSkip = () => {
    speechSynthesis.cancel();
    if (currentPage < pages.length - 1) {
      const next = currentPage + 1;
      onPageChange(next);
      if (isPlaying) speak(pages[next], next);
    }
  };

  return (
    <div style={{
      background: 'rgba(45, 52, 54, 0.9)',
      backdropFilter: 'blur(12px)',
      borderRadius: '16px',
      padding: '1rem 1.5rem',
      display: 'flex',
      alignItems: 'center',
      gap: '1rem',
      boxShadow: '0 8px 32px rgba(0,0,0,0.3)',
      marginTop: '1.5rem',
      width: 'fit-content'
    }}>
      {/* Play/Pause */}
      <button onClick={handlePlay} style={{
        width: '44px', height: '44px', borderRadius: '50%',
        background: isPlaying ? 'var(--accent)' : 'var(--primary)',
        color: 'white', display: 'flex', alignItems: 'center', justifyContent: 'center'
      }}>
        {isPlaying ? <Pause size={20} /> : <Play size={20} style={{ marginLeft: '2px' }} />}
      </button>

      {/* Skip */}
      <button onClick={handleSkip} style={{
        width: '36px', height: '36px', borderRadius: '50%',
        background: 'rgba(255,255,255,0.15)', color: 'white',
        display: 'flex', alignItems: 'center', justifyContent: 'center'
      }}>
        <SkipForward size={16} />
      </button>

      {/* Voice selector */}
      <select
        value={selectedVoice}
        onChange={(e) => setSelectedVoice(e.target.value)}
        style={{
          background: 'rgba(255,255,255,0.1)', color: 'white',
          border: '1px solid rgba(255,255,255,0.2)', borderRadius: '8px',
          padding: '0.4rem 0.6rem', fontSize: '0.75rem', maxWidth: '140px'
        }}
      >
        {voices.map(v => (
          <option key={v.name} value={v.name} style={{ color: '#333' }}>
            {v.lang.startsWith('ja') ? '🇯🇵' : '🇬🇧'} {v.name.split(' ').slice(0, 2).join(' ')}
          </option>
        ))}
      </select>

      {/* Speed */}
      <div style={{ display: 'flex', alignItems: 'center', gap: '0.4rem' }}>
        <Volume2 size={14} style={{ color: 'rgba(255,255,255,0.6)' }} />
        <input type="range" min="0.5" max="1.5" step="0.1" value={rate}
          onChange={(e) => setRate(parseFloat(e.target.value))}
          style={{ width: '60px', accentColor: 'var(--secondary)' }}
        />
        <span style={{ color: 'rgba(255,255,255,0.5)', fontSize: '0.7rem', width: '30px' }}>
          {rate}x
        </span>
      </div>

      {/* Auto-advance toggle */}
      <label style={{ display: 'flex', alignItems: 'center', gap: '0.3rem', fontSize: '0.7rem', color: 'rgba(255,255,255,0.6)', cursor: 'pointer', whiteSpace: 'nowrap' }}>
        <input type="checkbox" checked={autoAdvance} onChange={(e) => setAutoAdvance(e.target.checked)}
          style={{ accentColor: 'var(--primary)' }} />
        自動めくり
      </label>
    </div>
  );
}
