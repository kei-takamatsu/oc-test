import type { NextAuthConfig } from "next-auth";
import Twitter from "next-auth/providers/twitter";
import Facebook from "next-auth/providers/facebook";

export const authConfig = {
  providers: [
    Twitter({
      clientId: process.env.AUTH_TWITTER_ID,
      clientSecret: process.env.AUTH_TWITTER_SECRET,
    }),
    Facebook({
      clientId: process.env.AUTH_FACEBOOK_ID,
      clientSecret: process.env.AUTH_FACEBOOK_SECRET,
    }),
    // Credentials provider は Prisma を使用するため auth.ts で定義します。
  ],
  callbacks: {
    authorized({ auth, request: { nextUrl } }) {
      const isLoggedIn = !!auth?.user;
      const isMyPage = nextUrl.pathname.startsWith("/mypage");
      const isProjectCreate = nextUrl.pathname.startsWith("/projects/create");
      
      if (isMyPage || isProjectCreate) {
        if (isLoggedIn) return true;
        return false; // ログインページへリダイレクト
      }
      return true;
    },
  },
  pages: {
    signIn: "/login",
  },
} satisfies NextAuthConfig;
