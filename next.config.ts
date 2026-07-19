import type { NextConfig } from "next";

const nextConfig: NextConfig = {
  turbopack: {
    root: process.cwd(),
  },
  images: {
    remotePatterns: [
      { protocol: "https", hostname: "*.supabase.co" },
    ],
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
