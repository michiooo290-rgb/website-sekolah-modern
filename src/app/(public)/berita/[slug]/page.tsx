import Link from "next/link";
import Image from "next/image";
import { notFound } from "next/navigation";
import sanitizeHtml from "sanitize-html";
import { formatDate, getNewsBySlug, imageUrl } from "@/lib/data";

export async function generateMetadata({ params }: { params: Promise<{ slug: string }> }) { const { slug } = await params; const item = await getNewsBySlug(slug); return { title: item?.judul ?? "Berita" }; }
export default async function NewsDetail({ params }: { params: Promise<{ slug: string }> }) {
  const { slug } = await params; const item = await getNewsBySlug(slug); if (!item) notFound();
  const safe = sanitizeHtml(item.isi, { allowedTags: sanitizeHtml.defaults.allowedTags.concat(["img"]), allowedAttributes: { a: ["href", "target"], img: ["src", "alt"] } });
  return <>
    <section className="relative z-10 bg-gradient-to-br from-pine to-pine-deep text-cream overflow-hidden"><div className="absolute -top-10 -right-10 w-52 h-52 rounded-full bg-brass/15 blur-3xl" /><div className="max-w-6xl mx-auto px-5 py-16 sm:py-24 relative z-10"><nav className="text-xs text-cream/60 mb-5"><Link href="/" className="hover:text-brass-light">Beranda</Link> <span className="mx-1">/</span> <Link href="/berita" className="hover:text-brass-light">Berita</Link> <span className="mx-1">/</span> <span className="text-brass-light">{item.kategori}</span></nav><p className="inline-flex items-center gap-2 text-xs font-semibold tracking-widest text-brass-light uppercase mb-4"><span className="h-px w-8 bg-brass" /> {item.kategori}</p><h1 className="font-serif text-3xl sm:text-5xl leading-tight max-w-3xl">{item.judul}</h1><p className="text-cream/60 mt-4 text-sm">{formatDate(item.tanggal)} · {item.dilihat} dilihat</p></div></section>
    {item.gambar && <section className="relative z-10 max-w-4xl mx-auto px-5 -mt-10 sm:-mt-16"><figure className="reveal rounded-[1.75rem] overflow-hidden shadow-2xl ring-1 ring-pine/10 dark:ring-cream/10"><Image src={imageUrl(item.gambar)} alt={item.judul} width={1100} height={650} className="w-full h-auto object-cover" /></figure></section>}
    <section className={`relative z-10 max-w-3xl mx-auto px-5 ${item.gambar ? "pt-12 sm:pt-16" : "pt-16 sm:pt-20"} pb-12 sm:pb-16`}><article className="reveal artikel text-pine/80 dark:text-cream/75" dangerouslySetInnerHTML={{ __html: safe }} /></section>
    <section className="relative z-10 max-w-3xl mx-auto px-5 pb-20 sm:pb-28"><div className="flex items-center justify-between border-t border-pine/10 dark:border-cream/10 pt-8"><Link href="/berita" className="elink text-sm font-semibold text-leaf dark:text-brass-light">← Semua Berita</Link><Link href="/" className="elink text-sm font-semibold text-pine/60 dark:text-cream/60">Beranda →</Link></div></section>
  </>;
}
