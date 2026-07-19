import type { Metadata } from "next";
import Script from "next/script";
import "./globals.css";

export const metadata: Metadata = {
  title: { default: "SMA Putra Persada Batam", template: "%s | SMA Putra Persada" },
  description: "Website resmi SMA Putra Persada Batam — unggul, berkarakter, dan beriman.",
};

export default function RootLayout({ children }: Readonly<{ children: React.ReactNode }>) {
  return <html lang="id" suppressHydrationWarning>
    <head>
      <link rel="preconnect" href="https://fonts.googleapis.com" />
      <link rel="preconnect" href="https://fonts.gstatic.com" crossOrigin="anonymous" />
      <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />
      <link rel="stylesheet" href="/assets/css/styles.css" />
      <style type="text/tailwindcss">{`@custom-variant dark (&:where(.dark, .dark *)); @theme { --color-pine:#0E3B2E; --color-pine-deep:#08291F; --color-leaf:#2F7D52; --color-cream:#F7F3E9; --color-cream-deep:#EFE8D6; --color-brass:#C9A227; --color-brass-light:#E0BC45; --font-serif:'Fraunces',Georgia,serif; --font-sans:'Plus Jakarta Sans',system-ui,sans-serif; }`}</style>
      <Script src="/assets/js/tailwind.js" strategy="beforeInteractive" />
      <Script id="legacy-theme" strategy="beforeInteractive">{`try{var saved=localStorage.getItem('theme');var dark=saved?saved==='dark':matchMedia('(prefers-color-scheme: dark)').matches;document.documentElement.classList.toggle('dark',dark)}catch(e){}`}</Script>
      <Script src="/assets/js/lenis.min.js" strategy="beforeInteractive" />
    </head>
    <body className="min-h-dvh antialiased bg-cream dark:bg-pine-deep text-pine dark:text-cream font-sans">{children}</body>
  </html>;
}
