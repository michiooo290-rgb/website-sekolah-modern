import Link from "next/link";
import Image from "next/image";
import type { SettingMap } from "@/lib/types";

export function SiteFooter({ settings }: { settings: SettingMap }) {
  return (
    <footer className="site-footer">
      <div className="container footer-grid">
        <div>
          <div className="footer-brand"><Image src="/assets/img/logo.jpeg" alt="" width={42} height={42} /><strong>{settings.nama_sekolah}</strong></div>
          <p>Sekolah yang menyeimbangkan prestasi, karakter, dan iman untuk menyiapkan generasi masa depan.</p>
        </div>
        <div><h3>Navigasi</h3><Link href="/tentang">Tentang</Link><Link href="/visi-misi">Visi & Misi</Link><Link href="/berita">Berita</Link><Link href="/ppdb">PPDB</Link></div>
        <div><h3>Kontak</h3><p>{settings.alamat}</p><p>{settings.telepon}</p><p>{settings.email}</p></div>
      </div>
      <div className="container copyright">© {new Date().getFullYear()} {settings.nama_sekolah}. Seluruh hak dilindungi.</div>
    </footer>
  );
}
