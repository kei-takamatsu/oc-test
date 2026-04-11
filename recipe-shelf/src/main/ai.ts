import { GoogleGenAI, Type } from '@google/genai'
import { Recipe } from './db'

export const aiService = {
  extractRecipeFromText: async (text: string, apiKey: string): Promise<Partial<Recipe>> => {
    if (!apiKey) {
      throw new Error('Gemini API key is not set.')
    }

    const ai = new GoogleGenAI({ apiKey: apiKey })

    const schema = {
      type: Type.OBJECT,
      properties: {
        title: {
          type: Type.STRING,
          description: "レシピのタイトル"
        },
        description: {
          type: Type.STRING,
          description: "レシピの簡潔な説明（もしあれば）"
        },
        ingredients: {
          type: Type.ARRAY,
          items: {
            type: Type.STRING
          },
          description: "材料のリスト。"
        },
        instructions: {
          type: Type.ARRAY,
          items: {
            type: Type.STRING
          },
          description: "作り方・手順のリスト。順番通りに配列にすること。"
        },
        prepTime: {
            type: Type.STRING,
            description: "準備にかかる時間（例: '10分'）。不明な場合は空文字"
        },
        cookTime: {
            type: Type.STRING,
            description: "調理にかかる時間（例: '20分'）。不明な場合は空文字"
        },
        servings: {
            type: Type.STRING,
            description: "何人前か（例: '2人分'）。不明な場合は空文字"
        }
      },
      required: ["title", "ingredients", "instructions"]
    };

    const prompt = `以下のテキストからレシピ情報を抽出し、指定されたJSON構造で返してください。
SNSの投稿文（キャプション）などが含まれている場合がありますが、レシピに必要な情報（タイトル、材料、作り方、分量、時間）だけを抜き出してください。
もし入力テキスト内に明確な「レシピのタイトル」に該当する文言がない場合は、記載されている料理の内容からふさわしい料理名を自動で判断・生成して「title」に設定してください（例: 「鶏肉のトマト煮込み」など）。
材料や作り方は、人間が読みやすいように整理してリスト化してください。

元のテキスト:
${text}
`

    try {
      const response = await ai.models.generateContent({
        model: 'gemini-2.5-flash',
        contents: prompt,
        config: {
            responseMimeType: "application/json",
            responseSchema: schema,
            temperature: 0.1,
        }
      })

      const responseText = response.text
      if (!responseText) {
          throw new Error("AI did not return any text.")
      }

      const parsedData = JSON.parse(responseText)

      return {
        title: parsedData.title || 'タイトルなしのレシピ',
        description: parsedData.description || '',
        ingredients: JSON.stringify(parsedData.ingredients || []),
        instructions: JSON.stringify(parsedData.instructions || []),
        prepTime: parsedData.prepTime || undefined,
        cookTime: parsedData.cookTime || undefined,
        servings: parsedData.servings || undefined,
      }

    } catch (error) {
      console.error('Error extracting recipe with Gemini:', error)
      throw new Error('Failed to extract recipe using AI.')
    }
  }
}
