import Stripe from "stripe";

export const stripe = new Stripe(process.env.STRIPE_SECRET_KEY!, {
  apiVersion: "2025-01-27-preview", // 最新バージョンまたは安定版の指定
  typescript: true,
});
