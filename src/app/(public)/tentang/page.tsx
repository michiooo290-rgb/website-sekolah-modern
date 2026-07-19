import Image from "next/image";
import sanitizeHtml from "sanitize-html";
import { OriginalBanner, OriginalCta } from "@/components/original-sections";
import { getAbout, getTeachers, imageUrl } from "@/lib/data";

export const metadata = { title: "Tentang Kami" };

const facilityDefaults = [
  ["Ruang Kelas Modern", "Ruang belajar nyaman dengan perangkat presentasi."],
  ["Laboratorium IPA", "Fasilitas praktikum Fisika, Kimia, dan Biologi."],
  ["Laboratorium Komputer", "Pembelajaran digital dengan koneksi internet."],
  ["Perpustakaan", "Koleksi literasi dan ruang baca yang nyaman."],
  ["Mushola", "Tempat ibadah dan pembinaan karakter islami."],
  ["Lapangan Olahraga", "Ruang kegiatan olahraga dan ekstrakurikuler."],
];

export default async function AboutPage() {
  const [items, teachers] = await Promise.all([getAbout(), getTeachers()]);
  const history = items.find((x) => x.bagian === "sejarah");
  const greeting = items.find((x) => x.bagian === "sambutan");
  const principal = teachers[0];
  const facilities = items.filter((x) => x.bagian === "fasilitas");
  const shownFacilities = facilities.length ? facilities : facilityDefaults.map((x, id) => ({ id, bagian: "fasilitas", judul: x[0], isi: x[1] }));
  const icons = ["🏫", "🔬", "💻", "📚", "🕌", "⚽"];
  const gradients = ["from-leaf to-pine", "from-emerald-600 to-pine", "from-teal-600 to-pine", "from-amber-500 to-brass", "from-leaf to-pine", "from-pine to-pine-deep"];
  return <>
    <OriginalBanner breadcrumb="Tentang" label="Mengenal Kami" title="Tentang SMA Putra Persada" description="Mengenal lebih dekat sejarah, visi, dan dedikasi kami dalam membentuk generasi unggul." />
    <section className="relative z-10 max-w-6xl mx-auto px-5 py-20 sm:py-28"><div className="grid lg:grid-cols-2 gap-14 items-center"><div className="reveal"><div className="rounded-[2rem] overflow-hidden bg-gradient-to-br from-pine to-pine-deep aspect-[4/3] flex items-center justify-center shadow-xl"><Image src="/assets/img/logo.jpeg" alt="Logo SMA Putra Persada" width={160} height={160} className="w-40 h-40 object-contain opacity-80" /></div></div><div className="reveal"><p className="text-xs font-semibold tracking-widest text-leaf dark:text-brass-light uppercase mb-4">Sejarah</p><h2 className="font-serif text-3xl sm:text-4xl text-pine dark:text-cream leading-tight mb-5">{history?.judul ?? "Dari mimpi sederhana menjadi sekolah terpercaya."}</h2><div className="text-pine/70 dark:text-cream/70 leading-relaxed space-y-4" dangerouslySetInnerHTML={{ __html: sanitizeHtml(history?.isi ?? "<p>Sejak berdiri, SMA Putra Persada Batam terus bertumbuh menjadi sekolah yang mengintegrasikan keunggulan akademik dan karakter islami.</p>") }} /></div></div></section>
    <section className="relative z-10 bg-pine text-cream"><div className="max-w-6xl mx-auto px-5 py-20 sm:py-28"><div className="reveal max-w-xl mb-14"><p className="text-xs font-semibold tracking-widest text-brass-light uppercase mb-4">Sambutan</p><h2 className="font-serif text-3xl sm:text-4xl leading-tight">Kata Kepala Sekolah.</h2></div><div className="grid lg:grid-cols-12 gap-10 items-start reveal"><div className="lg:col-span-4"><div className="relative overflow-hidden rounded-2xl aspect-[3/4] ring-1 ring-cream/10 bg-pine-deep"><Image src={imageUrl(principal?.foto, "/assets/img/placeholder-guru.svg")} alt={principal?.nama ?? "Kepala Sekolah"} fill className="object-cover object-top" /><div className="absolute inset-x-0 bottom-0 h-1/3 bg-gradient-to-t from-pine-deep/70 to-transparent" /></div><div className="mt-4"><p className="font-serif text-lg text-cream">{principal?.nama}</p><p className="text-xs text-brass-light tracking-wide">{principal?.jabatan}</p></div></div><div className="lg:col-span-8"><p className="font-serif text-6xl text-brass-light leading-none mb-2">&quot;</p><blockquote className="font-serif text-xl sm:text-2xl leading-snug mb-6">{greeting?.judul ?? "Assalamu'alaikum Warahmatullahi Wabarakatuh."}</blockquote><div className="text-cream/75 leading-relaxed space-y-4" dangerouslySetInnerHTML={{ __html: sanitizeHtml(greeting?.isi ?? "<p>Kami percaya setiap anak memiliki potensi besar yang perlu dibimbing dengan ilmu, keteladanan, dan lingkungan belajar yang baik.</p>") }} /></div></div></div></section>
    <section className="relative z-10 max-w-6xl mx-auto px-5 py-20 sm:py-28"><div className="reveal text-center max-w-2xl mx-auto mb-14"><p className="text-xs font-semibold tracking-widest text-leaf dark:text-brass-light uppercase mb-4">Profil</p><h2 className="font-serif text-3xl sm:text-4xl text-pine dark:text-cream">Sekolah dalam angka.</h2></div><div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 reveal">{[["25+","Tahun Berdiri"],["750+","Peserta Didik"],["45+","Guru & Staf"],["25+","Ekstrakurikuler"]].map(([n,l]) => <div className="rounded-2xl border border-pine/10 dark:border-cream/10 p-7 text-center bg-cream-deep/40 dark:bg-pine/40" key={l}><p className="font-serif text-4xl text-brass dark:text-brass-light">{n}</p><p className="text-xs text-pine/60 dark:text-cream/60 mt-2 uppercase tracking-wide">{l}</p></div>)}</div></section>
    <section className="relative z-10 bg-cream-deep dark:bg-pine"><div className="max-w-6xl mx-auto px-5 py-20 sm:py-28"><div className="reveal max-w-xl mb-14"><p className="text-xs font-semibold tracking-widest text-leaf dark:text-brass-light uppercase mb-4">Fasilitas</p><h2 className="font-serif text-3xl sm:text-4xl text-pine dark:text-cream leading-tight">Lingkungan belajar yang mendukung.</h2><p className="text-pine/70 dark:text-cream/70 mt-4 text-sm">Fasilitas modern untuk mendukung proses pembelajaran yang optimal.</p></div><div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 reveal">{shownFacilities.map((f, i) => <div className="bg-cream dark:bg-pine-deep rounded-2xl p-6 ring-1 ring-pine/10 dark:ring-cream/10" key={f.id}><div className={`w-12 h-12 rounded-xl bg-gradient-to-br ${gradients[i] ?? gradients[0]} flex items-center justify-center text-xl mb-4`}>{icons[i] ?? "🏫"}</div><h3 className="font-serif text-lg text-pine dark:text-cream mb-2">{f.judul}</h3><p className="text-sm text-pine/70 dark:text-cream/70 leading-relaxed">{f.isi.replace(/<[^>]+>/g, "")}</p></div>)}</div></div></section>
    <OriginalCta title="Bergabunglah bersama kami." description="SMA Putra Persada Batam — tempat terbaik untuk memulai perjalanan pendidikan putra-putri Anda." />
  </>;
}
