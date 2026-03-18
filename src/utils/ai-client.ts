import { GoogleGenerativeAI } from '@google/generative-ai';

export class AIClient {
  private static ai: GoogleGenerativeAI | null = null;
  private static model: any = null;

  static init() {
    const apiKey = process.env.GEMINI_API_KEY;
    if (!apiKey) {
      console.warn('GEMINI_API_KEY is not set. AI features will be disabled.');
      return;
    }
    this.ai = new GoogleGenerativeAI(apiKey);
    this.model = this.ai.getGenerativeModel({ model: 'gemini-2.5-flash' });
    console.log('Gemini AI integrated successfully.');
  }

  static async ask(query: string, context?: string): Promise<string> {
    if (!this.model) {
      return 'AI機能が有効ではありません。\`.env\`の \`GEMINI_API_KEY\` を設定してください。';
    }

    try {
      const prompt = context 
        ? `以下のプロジェクトコンテキストを踏まえて質問に答えてください。\n\n【コンテキスト】\n${context}\n\n【質問】\n${query}`
        : query;

      const result = await this.model.generateContent(prompt);
      return result.response.text();
    } catch (error: any) {
      console.error('Error generating AI response:', error);
      return `AI応答の生成中にエラーが発生しました: ${error.message}`;
    }
  }

  static async determineIntent(text: string): Promise<{ isInstruction: boolean; response: string }> {
    if (!this.model) {
      // AIが無効な場合のフォールバック
      const isInstructionLike = /指示|やって|作って|作成|追加|変更|修正|調べて|削除|プッシュ|push|インストール|設定|終わらせて|完了|進めて/i.test(text);
      return { isInstruction: isInstructionLike, response: '' };
    }

    try {
      // ユーザーの意図をAIに判定させるプロンプト
      const systemPrompt = `
あなたは有能なシステム管理・開発アシスタントです。
ユーザーの発言が「システムへの指示・タスク依頼」なのか、それとも「単なる会話や質問」なのかを判定してください。
以下のJSONフォーマットのみで回答してください。その他の文章は不要です。

{
  "isInstruction": boolean,
  "response": "「単なる会話や質問」の場合は、アシスタントとしての返答を書いてください。「指示・タスク依頼」の場合は空文字で構いません。"
}

ユーザーの発言: "${text}"
`;
      const result = await this.model.generateContent(systemPrompt);
      const textResponse = result.response.text();
      // JSONの抽出
      const match = textResponse.match(/\\{.*\\}/s);
      if (match) {
        return JSON.parse(match[0]);
      } else {
        // パース失敗時のフォールバック
        return { isInstruction: true, response: '' };
      }
    } catch (error) {
      console.error('Error determining intent:', error);
      return { isInstruction: true, response: '' }; // エラー時は安全側に倒して指示扱い
    }
  }
}
