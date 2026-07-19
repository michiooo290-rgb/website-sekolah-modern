import type { Metadata } from "next";
import "./globals.css";
import { SiteHeader } from "@/components/site-header";
import { SiteFooter } from "@/components/site-footer";
import { getSettings } from "@/lib/data";

export const metadata: Metadata = {
  title: { default: "SMA Putra Persada Batam", template: "%s | SMA Putra Persada" },
  description: "Website resmi SMA Putra Persada Batam — unggul, berkarakter, dan beriman.",
};

export default async function RootLayout({ children }: Readonly<{ children: React.ReactNode }>) {
  const settings = await getSettings();
  return <html lang="id"><body><SiteHeader schoolName={settings.nama_sekolah} /><main>{children}</main><SiteFooter settings={settings} /></body></html>;
}
