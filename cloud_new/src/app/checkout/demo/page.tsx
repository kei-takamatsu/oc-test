import StripePaymentForm from "@/components/checkout/StripePaymentForm";

export default async function CheckoutDemoPage() {
  // 本来はサーバーサイドで SetupIntent を作成して clientSecret を取得するが
  // デモ用に適当な文字列を渡す（SDKによりエラーになるが、UIは確認可能）
  const clientSecret = "pi_123_secret_456"; 

  return (
    <div className="container mx-auto px-4 py-20 max-w-lg space-y-8">
      <div className="text-center space-y-2">
        <h1 className="text-3xl font-bold">決済デモ</h1>
        <p className="text-slate-500">Stripe によるセキュアなカード保存のプレビューです。</p>
      </div>
      
      {/* 
        実際には Stripe Elements を使うために 
        <Elements stripe={stripePromise} options={{clientSecret}}> で囲む必要があるが
        ここでは構造のみ示す 
      */}
      <div className="glass p-1 rounded-3xl">
        <div className="bg-slate-900/50 p-8 rounded-[22px] border border-white/5 space-y-6">
           <div className="h-40 bg-slate-800/50 rounded-xl flex items-center justify-center text-sm text-slate-500 italic">
              Stripe Elements UI (本番環境ではここにカード入力フォームが表示されます)
           </div>
           <button className="w-full py-4 bg-primary text-white font-bold rounded-xl shadow-lg shadow-indigo-500/20">
             カード情報を安全に保存する
           </button>
        </div>
      </div>
    </div>
  );
}
