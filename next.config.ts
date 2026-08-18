import type { NextConfig } from "next";

// Diambil dari environment saja. Menanam project ref Supabase langsung di
// berkas ini membuatnya ikut terbaca siapa pun di repositori publik, dan
// mempermudah orang menembak REST API database tanpa lewat situs.
const supabaseHost = (process.env.NEXT_PUBLIC_SUPABASE_URL ?? "")
  .replace(/^https?:\/\//, "")
  .replace(/\/$/, "");

const contentSecurityPolicy = [
  "default-src 'self'",
  // 'unsafe-eval' sudah dicabut: Tailwind tidak lagi menyusun CSS di browser.
  // Jangan kembalikan izin ini tanpa alasan kuat.
  "script-src 'self' 'unsafe-inline'",
  "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
  "font-src 'self' data: https://fonts.gstatic.com",
  "img-src 'self' data: blob: https://*.supabase.co",
  "connect-src 'self' https://*.supabase.co wss://*.supabase.co",
  "media-src 'self' https://*.supabase.co",
  // Hanya untuk sematan peta lokasi sekolah. Dibatasi ke domain peta Google
  // saja; jangan diperluas menjadi https: agar situs lain tidak bisa dibingkai.
  "frame-src https://www.google.com https://maps.google.com",
  "object-src 'none'",
  "base-uri 'self'",
  "form-action 'self'",
  "frame-ancestors 'none'",
  "worker-src 'self' blob:",
  "upgrade-insecure-requests",
].join("; ");

const securityHeaders = [
  { key: "Content-Security-Policy", value: contentSecurityPolicy },
  { key: "Referrer-Policy", value: "strict-origin-when-cross-origin" },
  { key: "X-Content-Type-Options", value: "nosniff" },
  { key: "X-Frame-Options", value: "DENY" },
  { key: "X-DNS-Prefetch-Control", value: "off" },
  { key: "X-Permitted-Cross-Domain-Policies", value: "none" },
  { key: "Cross-Origin-Opener-Policy", value: "same-origin" },
  { key: "Permissions-Policy", value: "camera=(), microphone=(), geolocation=(), payment=(), usb=()" },
  { key: "Strict-Transport-Security", value: "max-age=63072000; includeSubDomains; preload" },
];

const nextConfig: NextConfig = {
  poweredByHeader: false,
  turbopack: {
    root: process.cwd(),
  },
  images: {
    // Dibatasi ke project Supabase sendiri saja. Dengan pola *.supabase.co,
    // project milik orang lain bisa ikut dioptimasi lewat domain ini.
    remotePatterns: supabaseHost
      ? [{ protocol: "https", hostname: supabaseHost, pathname: "/storage/v1/object/public/**" }]
      : [],
  },
  async headers() {
    return [
      { source: "/:path*", headers: securityHeaders },
      { source: "/admin/:path*", headers: [{ key: "Cache-Control", value: "private, no-store, max-age=0" }] },
      { source: "/login", headers: [{ key: "Cache-Control", value: "private, no-store, max-age=0" }] },
    ];
  },
  async redirects() {
    return [
      { source: "/index.php", destination: "/", permanent: true },
      { source: "/tentang.php", destination: "/tentang", permanent: true },
      { source: "/visi-misi.php", destination: "/visi-misi", permanent: true },
      { source: "/berita.php", destination: "/berita", permanent: true },
      { source: "/ekstrakurikuler.php", destination: "/ekstrakurikuler", permanent: true },
      { source: "/ppdb.php", destination: "/ppdb", permanent: true },
      { source: "/kontak.php", destination: "/kontak", permanent: true },
      { source: "/admin/login.php", destination: "/login", permanent: true },
    ];
  },
};

export default nextConfig;
