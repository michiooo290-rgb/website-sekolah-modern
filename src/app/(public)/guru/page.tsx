import Image from "next/image";
import { OriginalBanner, OriginalCta } from "@/components/original-sections";
import { getSettings, getTeachers, imageUrl } from "@/lib/data";
import type { Teacher } from "@/lib/types";

export const metadata = { title: "Guru & Staf" };

/* Pemisahan pimpinan memakai kata "Kepala" pada jabatan, sehingga Kepala
   Sekolah maupun Wakil Kepala Sekolah ikut masuk kelompok atas. Bila nanti ada
   jabatan struktural lain (misalnya Kepala Tata Usaha) ia otomatis ikut. */
function pimpinanSekolah(jabatan: string) {
  return /kepala/i.test(jabatan);
}

/* Mapel boleh ditulis dengan pemisah koma, titik tengah, atau garis miring.
   Masing-masing ditampilkan sebagai label terpisah agar guru dengan banyak
   mata pelajaran tetap terbaca rapi. */
function daftarMapel(mapel: string | null) {
  if (!mapel) return [];
  return mapel.split(/[,\u00b7/]/).map((x) => x.trim()).filter(Boolean);
}

function KartuGuru({ guru, besar = false }: { guru: Teacher; besar?: boolean }) {
  const mapel = daftarMapel(guru.mapel);
  return <figure className="group">
    <div className={`relative overflow-hidden rounded-2xl bg-pine ring-1 ring-pine/10 dark:ring-cream/10 ${besar ? "aspect-[3/4]" : "aspect-[4/5]"}`}>
      <Image src={imageUrl(guru.foto, "/assets/img/placeholder-guru.svg")} alt={guru.nama} fill sizes={besar ? "(max-width: 640px) 100vw, 320px" : "(max-width: 640px) 50vw, 240px"} className="object-cover object-top group-hover:scale-105 transition duration-500" />
    </div>
    <figcaption className="mt-4">
      <p className={`font-serif text-pine dark:text-cream leading-snug ${besar ? "text-xl" : "text-base"}`}>{guru.nama}</p>
      <p className="text-xs text-leaf dark:text-brass-light tracking-wide mt-1">{guru.jabatan}</p>
      {mapel.length > 0 && <div className="flex flex-wrap gap-1.5 mt-3">
        {mapel.map((m) => <span className="text-[11px] rounded-full bg-pine/[0.07] dark:bg-cream/10 text-pine/75 dark:text-cream/75 px-2.5 py-1" key={m}>{m}</span>)}
      </div>}
    </figcaption>
  </figure>;
}

export default async function GuruPage() {
  const [teachers, settings] = await Promise.all([getTeachers(), getSettings()]);
  const pimpinan = teachers.filter((x) => pimpinanSekolah(x.jabatan));
  const pengajar = teachers.filter((x) => !pimpinanSekolah(x.jabatan));
  const jumlahMapel = new Set(teachers.flatMap((x) => daftarMapel(x.mapel))).size;
  return <>
    <OriginalBanner breadcrumb="Guru" label="Tenaga Pendidik" title="Guru & Staf Pengajar" description={`Mengenal para pendidik ${settings.nama_sekolah} yang membersamai siswa setiap hari, beserta mata pelajaran yang diampu.`} />

    <section className="relative z-10 border-b border-pine/10 dark:border-cream/10"><div className="max-w-6xl mx-auto px-5 grid grid-cols-3 divide-x divide-pine/10 dark:divide-cream/10">
      {[[String(teachers.length), "Guru & Staf"], [String(pimpinan.length), "Pimpinan"], [String(jumlahMapel), "Mata Pelajaran"]].map(([nilai, label]) => <div className="py-7 text-center" key={label}>
        <p className="font-serif text-3xl sm:text-4xl text-pine dark:text-brass-light">{nilai}</p>
        <p className="text-[11px] sm:text-xs tracking-wide text-pine/60 dark:text-cream/60 mt-1 uppercase">{label}</p>
      </div>)}
    </div></section>

    {pimpinan.length > 0 && <section className="relative z-10 max-w-6xl mx-auto px-5 py-20 sm:py-28">
      <div className="reveal max-w-xl mb-12">
        <p className="text-xs font-semibold tracking-widest text-leaf dark:text-brass-light uppercase mb-4">Pimpinan</p>
        <h2 className="font-serif text-3xl sm:text-4xl text-pine dark:text-cream leading-tight">Pimpinan sekolah.</h2>
        <p className="text-pine/70 dark:text-cream/70 mt-4 text-sm leading-relaxed">Mereka yang mengarahkan kebijakan akademik, kesiswaan, dan pembinaan karakter di sekolah.</p>
      </div>
      <div className="grid grid-cols-2 lg:grid-cols-3 gap-8 sm:gap-10 reveal">
        {pimpinan.map((guru) => <KartuGuru guru={guru} besar key={guru.id} />)}
      </div>
    </section>}

    {pengajar.length > 0 && <section className="relative z-10 bg-cream-deep dark:bg-pine"><div className="max-w-6xl mx-auto px-5 py-20 sm:py-28">
      <div className="reveal max-w-xl mb-12">
        <p className="text-xs font-semibold tracking-widest text-leaf dark:text-brass-light uppercase mb-4">Pengajar</p>
        <h2 className="font-serif text-3xl sm:text-4xl text-pine dark:text-cream leading-tight">Guru mata pelajaran.</h2>
        <p className="text-pine/70 dark:text-cream/70 mt-4 text-sm leading-relaxed">Setiap guru mendampingi siswa pada mata pelajaran yang tertera di bawah namanya.</p>
      </div>
      <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-7 sm:gap-9 reveal">
        {pengajar.map((guru) => <KartuGuru guru={guru} key={guru.id} />)}
      </div>
    </div></section>}

    {teachers.length === 0 && <section className="relative z-10 max-w-3xl mx-auto px-5 py-24 text-center">
      <p className="text-pine/60 dark:text-cream/60">Data tenaga pendidik belum tersedia.</p>
    </section>}

    <OriginalCta title="Ingin bertemu langsung dengan guru kami?" description={`Silakan berkunjung ke ${settings.nama_sekolah} pada hari dan jam kerja, atau hubungi kami lebih dahulu.`} button="Info PPDB" />
  </>;
}
