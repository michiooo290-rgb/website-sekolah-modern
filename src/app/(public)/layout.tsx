import { LegacyShell, type KontakSekolah } from "@/components/legacy-shell";
import { getSettings } from "@/lib/data";
import { resolvePpdbStatus } from "@/lib/ppdb-status";

export default async function PublicLayout({ children }: { children: React.ReactNode }) {
  const settings = await getSettings();
  const ppdb = resolvePpdbStatus(settings);
  /* Urutan sosial harus sama dengan urutan ikon di footer shared.js, karena
     ikon tanpa aria-label dicocokkan berdasarkan posisinya. */
  const kontak: KontakSekolah = {
    nama: settings.nama_sekolah ?? "",
    alamat: settings.alamat ?? "",
    telepon: settings.telepon ?? "",
    email: settings.email ?? "",
    jam: settings.jam_operasional ?? "",
    sosial: [
      { label: "instagram", url: settings.instagram ?? "" },
      { label: "youtube", url: settings.youtube ?? "" },
      { label: "tiktok", url: settings.tiktok ?? "" },
      { label: "facebook", url: settings.facebook ?? "" },
    ],
  };
  return <LegacyShell ppdbOpen={ppdb.open} kontak={kontak}>{children}</LegacyShell>;
}
