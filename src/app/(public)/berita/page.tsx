import Link from "next/link";
import Image from "next/image";
import { OriginalBanner } from "@/components/original-sections";
import { formatDate, getNews, imageUrl, plainText } from "@/lib/data";

export const metadata = { title: "Berita" };
const colors: Record<string, string> = { Prestasi: "bg-brass", Kegiatan: "bg-leaf", Pengumuman: "bg-pine" };

export default async function NewsPage() {
  const news = await getNews();
  return <>
    <OriginalBanner breadcrumb="Berita" label="Informasi Terkini" title="Berita & Kegiatan" description="Ikuti kabar terbaru seputar prestasi, kegiatan, dan perkembangan SMA Putra Persada Batam." />
    <section className="relative z-10 max-w-6xl mx-auto px-5 py-20 sm:py-28"><div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">{news.map((item, i) => <Link href={`/berita/${item.slug}`} className="group reveal lift flex flex-col bg-cream-deep dark:bg-pine rounded-[1.5rem] overflow-hidden ring-1 ring-pine/10 dark:ring-cream/10 hover:ring-brass/60" data-delay={(i % 3) + 1} key={item.id}><div className="zoomimg relative w-full pt-[62.5%]">{item.gambar ? <Image src={imageUrl(item.gambar)} alt={item.judul} fill className="object-cover" /> : <div className="absolute inset-0 bg-gradient-to-br from-leaf to-pine flex items-center justify-center"><span className="font-serif text-cream/90 text-2xl tracking-wide">{item.kategori}</span></div>}<span className={`absolute top-4 left-4 inline-block ${colors[item.kategori] ?? "bg-brass"} text-cream text-[11px] font-bold tracking-wide uppercase px-3 py-1 rounded-full shadow-lg`}>{item.kategori}</span></div><div className="flex flex-col flex-1 p-7"><p className="text-xs text-pine/50 dark:text-cream/50 mb-2">{formatDate(item.tanggal)} · {item.dilihat} dilihat</p><h3 className="font-serif text-xl text-pine dark:text-cream mb-3 leading-snug group-hover:text-leaf dark:group-hover:text-brass-light transition">{item.judul}</h3><p className="text-pine/70 dark:text-cream/70 text-sm leading-relaxed mb-5">{plainText(item.isi).slice(0, 130)}…</p><span className="elink mt-auto text-sm font-semibold text-leaf dark:text-brass-light">Baca selengkapnya →</span></div></Link>)}</div></section>
    <section className="relative z-10 max-w-6xl mx-auto px-5 pb-20 sm:pb-28"><div className="rounded-[2rem] bg-gradient-to-br from-leaf to-pine text-cream px-8 sm:px-14 py-14 text-center relative overflow-hidden reveal"><div className="absolute -top-10 -right-10 w-40 h-40 rounded-full bg-brass/20 blur-3xl" /><h2 className="font-serif text-3xl sm:text-5xl leading-tight max-w-2xl mx-auto">Ingin tahu lebih banyak?</h2><p className="text-cream/80 mt-5 max-w-lg mx-auto">Kunjungi sekolah kami atau hubungi langsung untuk informasi lebih lanjut.</p><Link href="/kontak" className="inline-block mt-8 bg-brass text-pine-deep font-semibold px-8 py-3.5 rounded-full hover:bg-brass-light transition">Hubungi Kami</Link></div></section>
  </>;
}
