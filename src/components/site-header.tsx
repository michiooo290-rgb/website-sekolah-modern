"use client";

import Link from "next/link";
import Image from "next/image";
import { Menu, X } from "lucide-react";
import { useState } from "react";

const links = [
  ["Beranda", "/"], ["Tentang", "/tentang"], ["Visi & Misi", "/visi-misi"],
  ["Berita", "/berita"], ["Ekstrakurikuler", "/ekstrakurikuler"], ["PPDB", "/ppdb"], ["Kontak", "/kontak"],
];

export function SiteHeader({ schoolName = "SMA Putra Persada" }: { schoolName?: string }) {
  const [open, setOpen] = useState(false);
  return (
    <header className="site-header">
      <div className="container nav-wrap">
        <Link href="/" className="brand" onClick={() => setOpen(false)}>
          <Image src="/assets/img/logo.jpeg" alt="Logo sekolah" width={38} height={38} />
          <span><strong>{schoolName}</strong><small>Batam · Kepulauan Riau</small></span>
        </Link>
        <button className="menu-button" onClick={() => setOpen(!open)} aria-label="Buka navigasi" aria-expanded={open}>
          {open ? <X /> : <Menu />}
        </button>
        <nav className={open ? "nav-links open" : "nav-links"}>
          {links.map(([label, href]) => <Link key={href} href={href} onClick={() => setOpen(false)}>{label}</Link>)}
          <Link className="nav-cta" href="/ppdb" onClick={() => setOpen(false)}>Daftar PPDB</Link>
        </nav>
      </div>
    </header>
  );
}
