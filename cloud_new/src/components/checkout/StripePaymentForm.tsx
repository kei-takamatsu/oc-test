"use client";

import { useState } from "react";
import { PaymentElement, useStripe, useElements } from "@stripe/react-stripe-js";

export default function StripePaymentForm({ clientSecret }: { clientSecret: string }) {
  const stripe = useStripe();
  const elements = useElements();
  const [message, setMessage] = useState<string | null>(null);
  const [isLoading, setIsLoading] = useState(false);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();

    if (!stripe || !elements) return;

    setIsLoading(true);

    const { error } = await stripe.confirmSetup({
      elements,
      confirmParams: {
        return_url: `${window.location.origin}/checkout/success`,
      },
    });

    if (error) {
      setMessage(error.message ?? "An error occurred.");
    } else {
      setMessage("Success!");
    }

    setIsLoading(false);
  };

  return (
    <form onSubmit={handleSubmit} className="space-y-6">
      <div className="glass p-6 rounded-2xl shadow-xl">
        <PaymentElement />
      </div>
      
      <button
        disabled={isLoading || !stripe || !elements}
        className="w-full py-4 bg-primary text-white font-bold rounded-xl shadow-lg shadow-indigo-500/20 hover-subtle disabled:opacity-50"
      >
        {isLoading ? "処理中..." : "カード情報を安全に保存する"}
      </button>

      {message && <div className="text-red-500 text-sm font-medium">{message}</div>}
      
      <p className="text-xs text-slate-400 text-center">
        当プラットフォームでは目標達成時まで課金は発生しません。決済はStripeにより安全に処理されます。
      </p>
    </form>
  );
}
