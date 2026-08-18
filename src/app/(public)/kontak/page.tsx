import { OriginalBanner, OriginalCta } from "@/components/original-sections";
import { getSettings } from "@/lib/data";
import { titikPeta, urlPeta } from "@/lib/peta";
import { sendMessage } from "./actions";

export const metadata = { title: "Kontak" };
const input = "w-full rounded-xl border border-pine/15 dark:border-cream/15 bg-cream dark:bg-pine-deep px-4 py-3 text-sm text-pine dark:text-cream outline-none focus:border-brass focus:ring-2 focus:ring-brass/20 transition";

export default async function ContactPage({ searchParams }: { searchParams: Promise<{ status?: string }> }) {
  const [settings, params] = await Promise.all([getSettings(), searchParams]);
  const messages: Record<string,string> = { success:"Pesan berhasil dikirim. Terima kasih telah menghubungi kami.", invalid:"Periksa kembali data yang Anda masukkan.", limited:"Terlalu banyak pesan dikirim. Silakan tunggu beberapa menit.", error:"Pesan belum berhasil dikirim. Silakan coba lagi.", demo:"Form aktif setelah Supabase dihubungkan." };
  const { src: petaSrc, tautan: petaTautan } = urlPeta(titikPeta(settings.peta_koordinat));
  return <>
    <OriginalBanner breadcrumb="Kontak" label="Hubungi Kami" title="Kami siap mendengar." description="Kunjungi sekolah, hubungi petugas, atau kirim pesan melalui formulir berikut." />
    <section className="relative z-10 max-w-6xl mx-auto px-5 py-20 sm:py-28"><div className="grid lg:grid-cols-12 gap-12"><div className="lg:col-span-5 reveal"><p className="text-xs font-semibold tracking-widest text-leaf dark:text-brass-light uppercase mb-4">Informasi Kontak</p><h2 className="font-serif text-3xl sm:text-4xl text-pine dark:text-cream leading-tight mb-8">Mari terhubung dengan kami.</h2><div className="space-y-6">{[["📍","Alamat",settings.alamat],["📞","Telepon",settings.telepon],["✉️","Email",settings.email],["🕐","Jam Operasional",settings.jam_operasional]].map(([icon,title,value]) => <div className="flex items-start gap-4" key={title}><div className="w-12 h-12 shrink-0 rounded-xl bg-gradient-to-br from-leaf to-pine flex items-center justify-center text-xl">{icon}</div><div><h3 className="font-serif text-lg text-pine dark:text-cream">{title}</h3><p className="text-sm text-pine/65 dark:text-cream/65 mt-1 leading-relaxed whitespace-pre-line">{value}</p></div></div>)}</div></div><div className="lg:col-span-7 reveal"><div className="bg-cream-deep dark:bg-pine rounded-[1.5rem] p-7 sm:p-9 ring-1 ring-pine/10 dark:ring-cream/10"><h2 className="font-serif text-2xl text-pine dark:text-cream mb-6">Kirim pesan</h2>{params.status && <p className="mb-5 rounded-xl bg-leaf/10 border border-leaf/20 text-leaf dark:text-brass-light p-4 text-sm">{messages[params.status]}</p>}<form action={sendMessage} className="space-y-5"><input name="website" tabIndex={-1} autoComplete="off" className="hidden" /><div><label className="block text-sm font-semibold text-pine dark:text-cream mb-2">Nama lengkap</label><input className={input} name="nama" required minLength={2} /></div><div className="grid sm:grid-cols-2 gap-5"><div><label className="block text-sm font-semibold text-pine dark:text-cream mb-2">Email</label><input className={input} name="email" type="email" required /></div><div><label className="block text-sm font-semibold text-pine dark:text-cream mb-2">Telepon</label><input className={input} name="telepon" /></div></div><div><label className="block text-sm font-semibold text-pine dark:text-cream mb-2">Subjek</label><input className={input} name="subjek" /></div><div><label className="block text-sm font-semibold text-pine dark:text-cream mb-2">Pesan</label><textarea className={`${input} min-h-36 resize-y`} name="pesan" required minLength={10} /></div><button className="bg-brass text-pine-deep font-semibold px-8 py-3.5 rounded-full hover:bg-brass-light transition" type="submit">Kirim Pesan</button></form></div></div></div></section>
    <section className="relative z-10 bg-cream-deep dark:bg-pine"><div className="max-w-6xl mx-auto px-5 py-20 sm:py-28">
      <div className="reveal text-center max-w-2xl mx-auto mb-12"><p className="text-xs font-semibold tracking-widest text-leaf dark:text-brass-light uppercase mb-4">Lokasi Sekolah</p><h2 className="font-serif text-3xl sm:text-4xl text-pine dark:text-cream">Kunjungi kami langsung.</h2><p className="text-pine/65 dark:text-cream/65 mt-4 text-sm leading-relaxed">{settings.alamat}</p></div>
      <div className="reveal rounded-[1.5rem] overflow-hidden bg-pine ring-1 ring-pine/10 dark:ring-cream/10 shadow-xl aspect-[16/9] sm:aspect-[16/7]">
        <iframe src={petaSrc} title={`Peta lokasi ${settings.nama_sekolah}`} loading="lazy" referrerPolicy="no-referrer-when-downgrade" className="w-full h-full border-0" />
      </div>
      <div className="reveal text-center mt-8">
        <a href={petaTautan} target="_blank" rel="noopener noreferrer" className="inline-flex items-center gap-2 bg-brass text-pine-deep font-semibold px-7 py-3 rounded-full hover:bg-brass-light transition text-sm">
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true"><path d="M12 21s7-6.3 7-11a7 7 0 1 0-14 0c0 4.7 7 11 7 11z" /><circle cx="12" cy="10" r="2.6" /></svg>
          Buka rute di Google Maps
        </a>
      </div>
    </div></section>
    <OriginalCta title="Kami menantikan kehadiran Anda." description="Datang dan lihat langsung lingkungan belajar SMAS Putra Persada Batam." button="Info PPDB" />
  </>;
}
