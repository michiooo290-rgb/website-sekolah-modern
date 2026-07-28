import { OriginalBanner, OriginalCta } from "@/components/original-sections";
import { getVision } from "@/lib/data";

export const metadata = { title: "Visi & Misi" };

export default async function VisionPage() {
  const items = await getVision();
  const visi = items.filter((x) => x.tipe === "visi");
  const misi = items.filter((x) => x.tipe === "misi");
  const tujuan = items.filter((x) => x.tipe === "tujuan");
  const nilai = items.filter((x) => x.tipe === "nilai");
  return <>
    <OriginalBanner breadcrumb="Visi & Misi" label="Arah & Tujuan Kami" title="Visi & Misi Sekolah" description="Landasan dan arah yang memandu setiap langkah SMAS Putra Persada Batam dalam mendidik generasi." />
    <section className="relative z-10"><div className="max-w-6xl mx-auto px-5 py-20 sm:py-28"><div className="reveal max-w-xl mb-12"><p className="text-xs font-semibold tracking-widest text-leaf dark:text-brass-light uppercase mb-4">Visi</p><h2 className="font-serif text-3xl sm:text-4xl text-pine dark:text-cream leading-tight">Landasan utama arah sekolah.</h2></div>{visi.length === 1
      /* Visi satu kalimat: tampilkan sebagai pernyataan, bukan butir bernomor. */
      ? <blockquote className="reveal max-w-4xl border-l-4 border-brass pl-6 sm:pl-8"><p className="font-serif text-2xl sm:text-4xl leading-snug text-pine dark:text-cream">&ldquo;{visi[0].isi}&rdquo;</p></blockquote>
      : <div className="grid sm:grid-cols-2 gap-x-10 gap-y-8 reveal">{visi.map((v, i) => <div className="flex gap-5" key={v.id}><span className="numdot text-brass text-3xl leading-none shrink-0">{String(i + 1).padStart(2, "0")}</span><p className="text-pine/85 dark:text-cream/85 leading-relaxed pt-1">{v.isi}</p></div>)}</div>}</div></section>
    <section className="relative z-10 bg-pine text-cream"><div className="max-w-6xl mx-auto px-5 py-20 sm:py-28"><div className="reveal max-w-xl mb-12"><p className="text-xs font-semibold tracking-widest text-brass-light uppercase mb-4">Misi</p><h2 className="font-serif text-3xl sm:text-4xl leading-tight">Langkah nyata mewujudkan visi.</h2></div><div className="grid sm:grid-cols-2 gap-x-10 gap-y-8 reveal">{misi.map((m, i) => <div className="flex gap-5" key={m.id}><span className="numdot text-brass-light text-3xl leading-none shrink-0">{String(i + 1).padStart(2, "0")}</span><p className="text-cream/85 leading-relaxed pt-1">{m.isi}</p></div>)}</div></div></section>
    <section className="relative z-10 max-w-6xl mx-auto px-5 py-20 sm:py-28"><div className="reveal text-center max-w-2xl mx-auto mb-14"><p className="text-xs font-semibold tracking-widest text-leaf dark:text-brass-light uppercase mb-4">Tujuan</p><h2 className="font-serif text-3xl sm:text-4xl text-pine dark:text-cream">Hasil yang ingin kami capai.</h2></div><div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 reveal">{tujuan.map((t, i) => <div className="rounded-2xl border border-pine/10 dark:border-cream/10 p-7 hover:border-brass transition bg-cream-deep/40 dark:bg-pine/40" key={t.id}><p className="numdot text-brass text-3xl mb-3">{String(i + 1).padStart(2, "0")}</p><h3 className="font-serif text-lg text-pine dark:text-cream mb-2">{t.judul}</h3><p className="text-sm text-pine/70 dark:text-cream/70 leading-relaxed">{t.isi}</p></div>)}</div></section>
    {nilai.length > 0 && <section className="relative z-10 bg-cream-deep dark:bg-pine"><div className="max-w-6xl mx-auto px-5 py-20 sm:py-28"><div className="reveal text-center max-w-2xl mx-auto mb-14"><p className="text-xs font-semibold tracking-widest text-leaf dark:text-brass-light uppercase mb-4">Nilai-Nilai</p><h2 className="font-serif text-3xl sm:text-4xl text-pine dark:text-cream">Karakter yang kami tanamkan.</h2></div><div className="flex flex-wrap justify-center gap-4 reveal">{nilai.map((n) => <div className="bg-cream dark:bg-pine-deep rounded-2xl px-6 py-5 ring-1 ring-pine/10 dark:ring-cream/10 max-w-xs" key={n.id}><p className="font-serif text-xl text-pine dark:text-brass-light mb-1">{n.judul}</p><p className="text-xs text-pine/65 dark:text-cream/65 leading-relaxed">{n.isi}</p></div>)}</div></div></section>}
    <OriginalCta title="Jadilah bagian dari visi kami." description="Bergabunglah bersama SMAS Putra Persada Batam pada Tahun Ajaran 2026/2027." />
  </>;
}
