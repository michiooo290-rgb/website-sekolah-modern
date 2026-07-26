import type { MetadataRoute } from "next";
import { getNews } from "@/lib/data";

/** Segarkan tiap jam supaya berita baru cepat masuk peta situs. */
export const revalidate = 3600;

export const BASE_URL = (process.env.NEXT_PUBLIC_SITE_URL ?? "https://website-sekolah-modern.vercel.app").replace(/\/+$/, "");

/** Halaman tetap beserta bobot pentingnya bagi mesin pencari. */
const HALAMAN: Array<{ path: string; priority: number; changeFrequency: "daily" | "weekly" | "monthly" }> = [
  { path: "", priority: 1, changeFrequency: "weekly" },
  { path: "/ppdb", priority: 0.9, changeFrequency: "weekly" },
  { path: "/berita", priority: 0.8, changeFrequency: "daily" },
  { path: "/tentang", priority: 0.7, changeFrequency: "monthly" },
  { path: "/visi-misi", priority: 0.6, changeFrequency: "monthly" },
  { path: "/ekstrakurikuler", priority: 0.6, changeFrequency: "monthly" },
  { path: "/kontak", priority: 0.6, changeFrequency: "monthly" },
];

export default async function sitemap(): Promise<MetadataRoute.Sitemap> {
  const sekarang = new Date();
  const statis: MetadataRoute.Sitemap = HALAMAN.map((item) => ({
    url: `${BASE_URL}${item.path}`,
    lastModified: sekarang,
    changeFrequency: item.changeFrequency,
    priority: item.priority,
  }));

  let berita: MetadataRoute.Sitemap = [];
  try {
    const items = await getNews();
    berita = items
      .filter((item) => Boolean(item.slug))
      .map((item) => {
        const tanggal = item.tanggal ? new Date(item.tanggal) : sekarang;
        return {
          url: `${BASE_URL}/berita/${item.slug}`,
          lastModified: Number.isNaN(tanggal.getTime()) ? sekarang : tanggal,
          changeFrequency: "monthly" as const,
          priority: 0.5,
        };
      });
  } catch {
    /* Bila database sedang tidak dapat dihubungi, peta situs tetap terbit
       dengan halaman statis saja daripada gagal seluruhnya. */
  }

  return [...statis, ...berita];
}
