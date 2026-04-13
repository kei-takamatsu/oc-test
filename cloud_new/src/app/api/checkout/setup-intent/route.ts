import { NextResponse } from "next/server";
import { stripe } from "@/lib/stripe";
import { auth } from "@/auth";

export async function POST(req: Request) {
  try {
    const session = await auth();
    if (!session?.user) {
      return new NextResponse("Unauthorized", { status: 401 });
    }

    // Stripe 顧客の作成（または既存の取得）
    // 本来は DB の User モデルと連携させる
    const customer = await stripe.customers.create({
      email: session.user.email!,
      name: session.user.name!,
    });

    // 支援受付用の SetupIntent 作成
    // usage: "off_session" を指定することで、後日（目標達成時）の決済を可能にする
    const setupIntent = await stripe.setupIntents.create({
      customer: customer.id,
      usage: "off_session",
      payment_method_types: ["card"],
    });

    return NextResponse.json({ clientSecret: setupIntent.client_secret });
  } catch (error) {
    console.error("[STRIPE_SETUP_INTENT]", error);
    return new NextResponse("Internal Error", { status: 500 });
  }
}
