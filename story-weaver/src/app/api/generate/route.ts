import { NextRequest, NextResponse } from 'next/server';

/**
 * POST /api/generate
 * 
 * Receives story pages + style preferences and returns generated image URLs.
 * 
 * Currently uses pre-generated sample images for demo.
 * To connect to a real AI API (Gemini Imagen, DALL-E, etc.),
 * replace the demo logic with actual API calls.
 * 
 * Expected body:
 * {
 *   pages: string[],
 *   style: string,
 *   referenceImages?: string[]  // base64 data URLs for style analysis
 * }
 */
export async function POST(request: NextRequest) {
  try {
    const body = await request.json();
    const { pages, style, referenceImages } = body as {
      pages: string[];
      style: string;
      referenceImages?: string[];
    };

    if (!pages || pages.length === 0) {
      return NextResponse.json(
        { error: 'ストーリーが入力されていません。' },
        { status: 400 }
      );
    }

    // Simulate generation delay (1.5s per page)
    await new Promise(resolve => setTimeout(resolve, pages.length * 1500));

    // Demo: return pre-generated images
    // In production, this is where you'd call:
    //   - Google Gemini Imagen API
    //   - OpenAI DALL-E API  
    //   - Stability AI API
    // 
    // The prompt for each page would be constructed from:
    //   1. The page text (scene description)
    //   2. The selected art style
    //   3. Analysis of reference images (if uploaded)
    //   4. Consistency instructions (same character, same palette)
    
    const demoImages = [
      '/generated-1.png',
      '/generated-2.png', 
      '/generated-3.png',
    ];

    const generatedPages = pages.map((text, index) => ({
      pageIndex: index,
      text,
      imageUrl: demoImages[index % demoImages.length],
      // In production, this would contain the actual prompt used
      prompt: `[${style}] ${text}`,
    }));

    return NextResponse.json({
      success: true,
      pages: generatedPages,
      meta: {
        style,
        totalPages: pages.length,
        hasReferenceImages: (referenceImages?.length ?? 0) > 0,
      }
    });

  } catch (error) {
    console.error('Generation error:', error);
    return NextResponse.json(
      { error: '生成中にエラーが発生しました。' },
      { status: 500 }
    );
  }
}
