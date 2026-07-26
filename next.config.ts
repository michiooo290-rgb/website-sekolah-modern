import type { NextConfig } from "next";

const supabaseHost = (process.env.NEXT_PUBLIC_SUPABASE_URL ?? "https://uvxwkkythmfedmhnzkax.supabase.co")
  .replace(/^https?:\/\//, "")
  .replace(/\/$/, "");

const contentSecurityPolicy = [
  "default-src 'self'",
  "script-src 'self' 'unsafe-inline' 'unsafe-eval'",
  "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
  "font-src 'self' data: https://fonts.gstatic.com",
  "img-src 'self' data: blob: https://*.supabase.co",
  "connect-src 'self' https://*.supabase.co wss://*.supabase.co",
  "media-src 'self' https://*.supabase.co",
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
    remotePatterns: [
      { protocol: "https", hostname: supabaseHost, pathname: "/storage/v1/object/public/**" },
    ],
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
