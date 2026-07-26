import Link from "next/link";
import sanitizeHtml from "sanitize-html";
import { OriginalBanner } from "@/components/original-sections";
import { getPpdb, getSettings } from "@/lib/data";
import { resolvePpdbStatus } from "@/lib/ppdb-status";

export const metadata = { title: "PPDB" };
export default async function PpdbPage() {
  const [items, settings] = await Promise.all([getPpdb(), getSettings()]);
  const syarat = items.filter((x) => x.bagian === "syarat"); const jadwal = items.filter((x) => x.bagian === "jadwal"); const alur = items.filter((x) => x.bagian === "alur"); const faq = items.filter((x) => x.bagian === "faq");
  const requirements = syarat.length ? syarat : [{id:1,judul:"Dokumen Identitas",isi:"Fotokopi kartu keluarga, akta kelahiran, dan NISN."},{id:2,judul:"Dokumen Sekolah",isi:"Fotokopi rapor dan surat keterangan lulus."}];
  const schedules = jadwal.length ? jadwal : [{id:1,tanggal:"15 Jun 2026",judul:"Pembukaan Pendaftaran",isi:"Jalur reguler dan prestasi resmi dibuka."},{id:2,tanggal:"28 Jun 2026",judul:"Tes Seleksi",isi:"Tes akademik dan wawancara calon siswa."},{id:3,tanggal:"10 Jul 2026",judul:"Pengumuman",isi:"Hasil seleksi dan proses daftar ulang."}];
  const flows = alur.length ? alur : [{id:1,judul:"Datang ke Sekolah",isi:"Ambil formulir pendaftaran."},{id:2,judul:"Lengkapi Dokumen",isi:"Isi formulir dan lampirkan berkas."},{id:3,judul:"Ikuti Seleksi",isi:"Hadir sesuai jadwal tes."},{id:4,judul:"Daftar Ulang",isi:"Selesaikan administrasi penerimaan."}];
  const faqs = faq.length ? faq : [{id:1,judul:"Apakah pendaftaran dilakukan secara online?",isi:"Pendaftaran dilakukan secara offline dengan datang langsung ke sekolah."},{id:2,judul:"Apakah tersedia jalur prestasi?",isi:"Ya, tersedia jalur prestasi, reguler, dan beasiswa sesuai ketentuan."}];
  const ppdb = resolvePpdbStatus(settings);
  const open = ppdb.open;
  const deskripsiBanner = open
    ? ppdb.closingLabel
      ? `Informasi lengkap pendaftaran siswa baru SMA Putra Persada Batam. Pendaftaran dibuka sampai ${ppdb.closingLabel} dan dilakukan secara offline (datang langsung ke sekolah).`
      : "Informasi lengkap pendaftaran siswa baru SMA Putra Persada Batam. Pendaftaran dilakukan secara offline (datang langsung ke sekolah)."
    : "Informasi PPDB tahun ajaran 2026/2027. Pendaftaran telah ditutup.";
  /* Kalimat sisa waktu untuk spanduk pengingat. */
  const sisaHari = ppdb.daysLeft;
  const sisaLabel = sisaHari === null ? null : sisaHari <= 0 ? "Hari terakhir" : sisaHari === 1 ? "Tinggal 1 hari" : `Tinggal ${sisaHari} hari`;
  return <>
    <OriginalBanner breadcrumb="PPDB" label="Penerimaan Peserta Didik Baru" title="PPDB 2026/2027" description={deskripsiBanner} />

    {!open && <section className="relative z-10 max-w-6xl mx-auto px-5 pt-14 sm:pt-20">
      <div className="reveal relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-pine to-pine-deep text-cream shadow-2xl">
        <span aria-hidden="true" className="pointer-events-none absolute -top-28 -right-20 w-80 h-80 rounded-full bg-brass/20 blur-3xl" />
        <span aria-hidden="true" className="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-brass/60 to-transparent" />
        <div className="relative grid lg:grid-cols-[1.5fr_1fr] gap-10 lg:gap-14 px-8 sm:px-12 py-12 sm:py-14">
          <div>
            <span className="inline-flex items-center gap-2.5 rounded-full bg-cream/10 ring-1 ring-cream/20 pl-2.5 pr-4 py-1.5 text-[0.7rem] font-semibold tracking-[0.15em] uppercase text-brass-light">
              <span className="w-6 h-6 rounded-full bg-brass text-pine-deep flex items-center justify-center">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true"><rect x="4" y="11" width="16" height="10" rx="2" /><path d="M8 11V8a4 4 0 0 1 8 0v3" /></svg>
              </span>
              Pendaftaran Ditutup
            </span>
            <h2 className="font-serif text-[1.75rem] sm:text-4xl lg:text-[2.6rem] leading-[1.15] mt-6">Pendaftaran peserta didik baru sudah ditutup.</h2>
            <p className="text-cream/70 mt-5 text-sm sm:text-base leading-relaxed max-w-xl">Informasi di halaman ini tetap dapat dibaca sebagai gambaran PPDB tahun ini. Untuk menanyakan pendaftaran tahun ajaran berikutnya, silakan hubungi sekolah.</p>
            <div className="flex flex-wrap gap-3 mt-8">
              <Link href="/kontak" className="bg-brass text-pine-deep font-semibold px-7 py-3 rounded-full hover:bg-brass-light transition">Hubungi Sekolah</Link>
              <Link href="/berita" className="ring-1 ring-cream/25 text-cream font-semibold px-7 py-3 rounded-full hover:bg-cream/10 transition">Lihat Berita Sekolah</Link>
            </div>
          </div>
          <div className="rounded-3xl bg-cream/[0.06] ring-1 ring-cream/15 p-7 flex flex-col justify-center gap-6">
            <div>
              <p className="text-[0.68rem] font-semibold tracking-[0.15em] uppercase text-cream/45">{ppdb.closingLabel ? "Pendaftaran berakhir" : "Status saat ini"}</p>
              <p className="font-serif text-2xl sm:text-[1.7rem] text-brass-light mt-2 leading-snug">{ppdb.closingLabel ?? "Sedang tidak menerima pendaftaran"}</p>
            </div>
            <span aria-hidden="true" className="h-px bg-cream/15" />
            <div>
              <p className="text-[0.68rem] font-semibold tracking-[0.15em] uppercase text-cream/45">Tanya lewat telepon</p>
              <p className="text-cream/85 mt-2 text-lg">{settings.telepon}</p>
              <p className="text-cream/50 mt-2 text-xs leading-relaxed">Kantor sekolah tetap melayani pertanyaan pada hari dan jam kerja.</p>
            </div>
          </div>
        </div>
      </div>
    </section>}

    {open && ppdb.closingLabel && <section className="relative z-10 max-w-6xl mx-auto px-5 pt-14 sm:pt-20">
      <div className="reveal rounded-[2rem] bg-cream-deep dark:bg-pine ring-1 ring-brass/40 px-7 sm:px-10 py-7 flex flex-col sm:flex-row sm:items-center gap-6">
        <span className="w-12 h-12 shrink-0 rounded-2xl bg-brass text-pine-deep flex items-center justify-center">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9" /><path d="M12 7v5l3 2" /></svg>
        </span>
        <div className="flex-1">
          <p className="text-[0.68rem] font-semibold tracking-[0.15em] uppercase text-leaf dark:text-brass-light">Batas Waktu</p>
          <p className="font-serif text-xl sm:text-2xl text-pine dark:text-cream mt-1.5 leading-snug">Pendaftaran dibuka sampai {ppdb.closingLabel}.</p>
          <p className="text-sm text-pine/65 dark:text-cream/65 mt-2 leading-relaxed">Setelah tanggal tersebut pendaftaran ditutup otomatis, jadi sebaiknya jangan menunggu hari terakhir.</p>
        </div>
        {sisaLabel && <span className="shrink-0 self-start sm:self-center rounded-full bg-pine text-cream font-semibold text-sm px-5 py-2.5 dark:bg-brass dark:text-pine-deep">{sisaLabel}</span>}
      </div>
    </section>}

    <section className="relative z-10 max-w-6xl mx-auto px-5 py-20 sm:py-28"><div className="reveal text-center max-w-2xl mx-auto mb-14"><p className="text-xs font-semibold tracking-widest text-leaf dark:text-brass-light uppercase mb-4">Persyaratan</p><h2 className="font-serif text-3xl sm:text-4xl text-pine dark:text-cream">Syarat Pendaftaran.</h2></div><div className="grid sm:grid-cols-2 gap-6 reveal">{requirements.map((x) => <div className="bg-cream-deep dark:bg-pine rounded-2xl p-7 ring-1 ring-pine/10 dark:ring-cream/10" key={x.id}><div className="w-12 h-12 rounded-xl bg-gradient-to-br from-leaf to-pine flex items-center justify-center text-xl mb-4">📋</div><h3 className="font-serif text-lg text-pine dark:text-cream mb-2">{x.judul}</h3><div className="text-sm text-pine/70 dark:text-cream/70 leading-relaxed" dangerouslySetInnerHTML={{__html:sanitizeHtml(x.isi)}} /></div>)}</div></section>
    <section className="relative z-10 bg-cream-deep dark:bg-pine"><div className="max-w-6xl mx-auto px-5 py-20 sm:py-28 grid lg:grid-cols-3 gap-12"><div className="reveal"><p className="text-xs font-semibold tracking-widest text-leaf dark:text-brass-light uppercase mb-4">Jadwal</p><h2 className="font-serif text-3xl sm:text-4xl text-pine dark:text-cream leading-tight">Jadwal penting PPDB.</h2><p className="text-pine/70 dark:text-cream/70 mt-4 text-sm">{open ? "Catat tanggal-tanggal penting berikut agar tidak terlewat." : "Jadwal berikut adalah jadwal periode pendaftaran yang sudah ditutup, ditampilkan sebagai arsip informasi."}</p></div><div className="lg:col-span-2 reveal"><div className="relative pl-8 border-l-2 border-pine/15 dark:border-cream/15 space-y-8">{schedules.map((x) => <div className="relative" key={x.id}><span className="absolute -left-[41px] top-1 w-4 h-4 rounded-full bg-brass ring-4 ring-cream-deep dark:ring-pine" /><p className="font-serif text-brass dark:text-brass-light text-lg">{x.tanggal}</p><h3 className="font-semibold text-pine dark:text-cream mt-0.5">{x.judul}</h3><p className="text-sm text-pine/60 dark:text-cream/60">{x.isi}</p></div>)}</div></div></div></section>
    <section className="relative z-10 max-w-6xl mx-auto px-5 py-20 sm:py-28"><div className="reveal text-center max-w-2xl mx-auto mb-14"><p className="text-xs font-semibold tracking-widest text-leaf dark:text-brass-light uppercase mb-4">Alur</p><h2 className="font-serif text-3xl sm:text-4xl text-pine dark:text-cream">Langkah pendaftaran.</h2></div><div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 reveal">{flows.map((x,i) => <div className="text-center" key={x.id}><div className="w-16 h-16 rounded-2xl bg-gradient-to-br from-leaf to-pine flex items-center justify-center text-2xl text-cream mx-auto mb-4 shadow-lg">{i+1}</div><h3 className="font-serif text-lg text-pine dark:text-cream mb-2">{x.judul}</h3><p className="text-sm text-pine/70 dark:text-cream/70 leading-relaxed">{x.isi}</p></div>)}</div></section>
    <section className="relative z-10 bg-pine text-cream"><div className="max-w-6xl mx-auto px-5 py-20 sm:py-28"><div className="reveal max-w-xl mb-14"><p className="text-xs font-semibold tracking-widest text-brass-light uppercase mb-4">Lokasi</p><h2 className="font-serif text-3xl sm:text-4xl leading-tight">Datang langsung ke sekolah.</h2><p className="text-cream/70 mt-4 text-sm">{open ? "Pendaftaran dilakukan secara offline. Silakan kunjungi kami pada hari dan jam kerja." : "Pendaftaran sedang ditutup, namun sekolah tetap dapat dikunjungi pada hari dan jam kerja untuk bertanya."}</p></div><ul className="space-y-4 text-cream/80 text-sm"><li><b className="text-cream">📍 Alamat</b><p className="text-cream/70">{settings.alamat}</p></li><li><b className="text-cream">📞 Telepon</b><p className="text-cream/70">{settings.telepon}</p></li><li><b className="text-cream">✉️ Email</b><p className="text-cream/70">{settings.email}</p></li></ul></div></section>
    <section className="relative z-10 max-w-3xl mx-auto px-5 py-20 sm:py-28"><div className="reveal text-center mb-12"><p className="text-xs font-semibold tracking-widest text-leaf dark:text-brass-light uppercase mb-3">FAQ PPDB</p><h2 className="font-serif text-3xl sm:text-4xl text-pine dark:text-cream">Pertanyaan seputar pendaftaran.</h2></div><div className="reveal divide-y divide-pine/10 dark:divide-cream/10 border-y border-pine/10 dark:border-cream/10">{faqs.map((x) => <details className="py-5" key={x.id}><summary className="font-serif text-lg text-pine dark:text-cream cursor-pointer">{x.judul}</summary><p className="pt-3 text-pine/70 dark:text-cream/70 text-sm leading-relaxed">{x.isi}</p></details>)}</div></section>
    <section className="relative z-10 max-w-6xl mx-auto px-5 pb-20 sm:pb-28"><div className="rounded-[2rem] bg-gradient-to-br from-leaf to-pine text-cream px-8 sm:px-14 py-14 text-center relative overflow-hidden reveal"><h2 className="font-serif text-3xl sm:text-5xl leading-tight max-w-2xl mx-auto">{open ? "Masih punya pertanyaan?" : "Ingin tahu info PPDB tahun depan?"}</h2><p className="text-cream/80 mt-5 max-w-lg mx-auto">Hubungi kami langsung atau kunjungi sekolah untuk informasi lebih lanjut.</p><Link href="/kontak" className="inline-block mt-8 bg-brass text-pine-deep font-semibold px-8 py-3.5 rounded-full hover:bg-brass-light transition">Hubungi Kami</Link></div></section>
  </>;
}
