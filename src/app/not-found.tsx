import Link from "next/link";

export const metadata = { title: "Halaman Tidak Ditemukan", robots: { index: false, follow: false } };

const TAUTAN = [
  { href: "/", label: "Beranda" },
  { href: "/ppdb", label: "Informasi PPDB" },
  { href: "/berita", label: "Berita Sekolah" },
  { href: "/kontak", label: "Kontak" },
];

export default function NotFound() {
  return <main className="min-h-dvh grid place-items-center px-5 py-20">
    <div className="w-full max-w-xl text-center">
      <p className="font-serif text-[5rem] sm:text-[7rem] leading-none text-brass">404</p>
      <h1 className="font-serif text-3xl sm:text-4xl text-pine dark:text-cream mt-2 leading-tight">Halaman ini tidak ditemukan.</h1>
      <p className="text-pine/70 dark:text-cream/70 mt-4 text-[15px] leading-relaxed">Alamat yang kamu buka mungkin salah tulis, atau halamannya sudah dipindahkan. Silakan pilih tujuan di bawah ini.</p>
      <div className="mt-8 flex flex-wrap justify-center gap-3">
        <Link href="/" className="bg-brass text-pine-deep font-semibold px-7 py-3 rounded-full hover:bg-brass-light transition">Kembali ke Beranda</Link>
        <Link href="/kontak" className="ring-1 ring-pine/20 dark:ring-cream/25 text-pine dark:text-cream font-semibold px-7 py-3 rounded-full hover:bg-pine/5 dark:hover:bg-cream/10 transition">Hubungi Sekolah</Link>
      </div>
      <ul className="mt-10 flex flex-wrap justify-center gap-x-6 gap-y-2 text-sm">
        {TAUTAN.map((item) => <li key={item.href}>
          <Link href={item.href} className="elink font-semibold text-leaf dark:text-brass-light">{item.label}</Link>
        </li>)}
      </ul>
    </div>
  </main>;
}
