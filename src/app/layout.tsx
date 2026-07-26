import type { Metadata } from "next";
import Script from "next/script";
import "./globals.css";

const SITE_URL = (process.env.NEXT_PUBLIC_SITE_URL ?? "https://website-sekolah-modern.vercel.app").replace(/\/+$/, "");
const NAMA = "SMA Putra Persada Batam";
const DESKRIPSI = "Website resmi SMA Putra Persada Batam — unggul, berkarakter, dan beriman. Informasi PPDB, berita, ekstrakurikuler, dan profil sekolah.";
const LOGO = "/assets/img/logo.jpeg";

export const metadata: Metadata = {
  metadataBase: new URL(SITE_URL),
  title: { default: NAMA, template: "%s | SMA Putra Persada" },
  description: DESKRIPSI,
  applicationName: NAMA,
  keywords: [
    "SMA Putra Persada",
    "SMA Putra Persada Batam",
    "SMA swasta Batam",
    "SMA islam Batam",
    "PPDB Batam",
    "PPDB 2026/2027",
    "sekolah menengah atas Batam",
  ],
  alternates: { canonical: "/" },
  /* Halaman publik boleh diindeks; larangan untuk dashboard diatur di robots.ts. */
  robots: { index: true, follow: true },
  openGraph: {
    type: "website",
    locale: "id_ID",
    url: SITE_URL,
    siteName: NAMA,
    title: NAMA,
    description: DESKRIPSI,
  },
  twitter: {
    card: "summary_large_image",
    title: NAMA,
    description: DESKRIPSI,
  },
  /* Logo sekolah dipakai sebagai ikon tab dan ikon layar utama. */
  icons: {
    icon: [{ url: LOGO, type: "image/jpeg" }],
    shortcut: [{ url: LOGO, type: "image/jpeg" }],
    apple: [{ url: LOGO, type: "image/jpeg" }],
  },
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
      <Script id="legacy-theme" strategy="beforeInteractive">{`try{var saved=localStorage.getItem('theme');var dark=saved?saved==='dark':matchMedia('(prefers-color-scheme: dark)').matches;document.documentElement.classList.toggle('dark',dark);document.documentElement.style.colorScheme=dark?'dark':'light'}catch(e){}`}</Script>
    </head>
    <body className="min-h-dvh antialiased bg-cream dark:bg-pine-deep text-pine dark:text-cream font-sans">{children}</body>
  </html>;
}
