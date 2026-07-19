import Link from "next/link";

export function OriginalBanner({ breadcrumb, label, title, description }: { breadcrumb: string; label: string; title: string; description: string }) {
  return <section className="relative z-10 bg-gradient-to-br from-pine to-pine-deep text-cream overflow-hidden">
    <div className="absolute -top-10 -right-10 w-52 h-52 rounded-full bg-brass/15 blur-3xl" />
    <div className="max-w-6xl mx-auto px-5 py-16 sm:py-24 relative z-10">
      <nav className="text-xs text-cream/60 mb-5"><Link href="/" className="hover:text-brass-light">Beranda</Link> <span className="mx-1">/</span> <span className="text-brass-light">{breadcrumb}</span></nav>
      <p className="inline-flex items-center gap-2 text-xs font-semibold tracking-widest text-brass-light uppercase mb-4"><span className="h-px w-8 bg-brass" /> {label}</p>
      <h1 className="font-serif text-4xl sm:text-6xl leading-tight max-w-3xl">{title}</h1>
      <p className="text-cream/80 mt-5 max-w-xl">{description}</p>
    </div>
  </section>;
}

export function OriginalCta({ title, description, button = "Daftar PPDB" }: { title: string; description: string; button?: string }) {
  return <section className="relative z-10 max-w-6xl mx-auto px-5 py-20 sm:py-28">
    <div className="rounded-[2rem] bg-gradient-to-br from-leaf to-pine text-cream px-8 sm:px-14 py-14 text-center relative overflow-hidden reveal">
      <div className="absolute -top-10 -right-10 w-40 h-40 rounded-full bg-brass/20 blur-3xl" />
      <h2 className="font-serif text-3xl sm:text-5xl leading-tight max-w-2xl mx-auto">{title}</h2>
      <p className="text-cream/80 mt-5 max-w-lg mx-auto">{description}</p>
      <Link href="/ppdb" className="inline-block mt-8 bg-brass text-pine-deep font-semibold px-8 py-3.5 rounded-full hover:bg-brass-light transition">{button}</Link>
    </div>
  </section>;
}
