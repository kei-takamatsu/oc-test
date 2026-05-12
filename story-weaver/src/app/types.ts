// Page types for picture book
export type PageType = 'cover' | 'content' | 'back-cover' | 'colophon';

export interface BookPage {
  id: string;
  type: PageType;
  text: string;
  imageUrl?: string;        // Generated or uploaded image
  customImageUrl?: string;  // User-uploaded override image
  hasImage: boolean;        // Whether this page should have an image
}

export const PAGE_TYPE_LABELS: Record<PageType, string> = {
  'cover': '表紙',
  'content': '本文',
  'back-cover': '背表紙',
  'colophon': '符丁',
};

// Output paper presets (for printing)
export const OUTPUT_PAPERS: Record<string, { label: string; width: number; height: number }> = {
  a4: { label: 'A4 (210×297mm)', width: 210, height: 297 },
  a3: { label: 'A3 (297×420mm)', width: 297, height: 420 },
  b5: { label: 'B5 (182×257mm)', width: 182, height: 257 },
  letter: { label: 'Letter (216×279mm)', width: 216, height: 279 },
};

export function createPageId(): string {
  return Math.random().toString(36).substring(2, 9);
}

export function parseStoryToPages(story: string): BookPage[] {
  const segments = story.split('P!').map(s => s.trim()).filter(s => s !== '');
  return segments.map((text, i) => ({
    id: createPageId(),
    type: i === 0 ? 'cover' : 'content',
    text,
    hasImage: true,
  }));
}
