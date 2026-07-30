"use client";

import Script from "next/script";
import { usePathname } from "next/navigation";
import { useCallback, useEffect, useState } from "react";

declare global { interface Window { wireHeaderFooter?: (active: string, open: boolean) => void } }

export type SosialItem = { label: string; url: string };
export type KontakSekolah = {
  nama: string;
  alamat: string;
  telepon: string;
  email: string;
  jam: string;
  sosial: SosialItem[];
};

const activeMap: Record<string,string> = {"/":"index.php","/tentang":"tentang.php","/guru":"/guru","/visi-misi":"visi-misi.php","/ekstrakurikuler":"ekstrakurikuler.php","/berita":"berita.php","/ppdb":"ppdb.php","/kontak":"kontak.php"};

/* Baris kontak di footer dikenali dari emoji pembukanya, bukan dari kelas CSS,
   supaya tambalan ini tetap bekerja walau tata letak footer diubah. */
const ikonBaris = ["\u{1F4CD}", "\u{1F4DE}", "\u2709\ufe0f", "\u{1F550}"];

function tambalFooter(kontak: KontakSekolah) {
  const footer = document.getElementById("site-footer");
  if (!footer) return;

  const nilai = [kontak.alamat, kontak.telepon, kontak.email, kontak.jam];
  footer.querySelectorAll("li").forEach((li) => {
    const teks = (li.textContent ?? "").trim();
    const indeks = ikonBaris.findIndex((ikon) => teks.startsWith(ikon));
    if (indeks >= 0 && nilai[indeks]) li.textContent = `${ikonBaris[indeks]} ${nilai[indeks]}`;
  });

  /* Nama sekolah sempat berubah dari SMA menjadi SMAS. Penggantian dilakukan
     pada simpul teks saja agar tidak merusak markup di dalamnya. */
  const jalan = document.createTreeWalker(footer, NodeFilter.SHOW_TEXT);
  for (let simpul = jalan.nextNode(); simpul; simpul = jalan.nextNode()) {
    const isi = simpul.nodeValue ?? "";
    if (/\bSMA\s+Putra Persada/.test(isi)) simpul.nodeValue = isi.replace(/\bSMA(?=\s+Putra Persada)/g, "SMAS");
  }

  const tautan = Array.from(footer.querySelectorAll<HTMLAnchorElement>('a[href="#"]'));
  tautan.forEach((a, i) => {
    const penanda = (a.getAttribute("aria-label") || a.getAttribute("title") || a.textContent || "").toLowerCase();
    const cocok = kontak.sosial.find((s) => penanda.includes(s.label.toLowerCase()));
    const pilihan = cocok ?? kontak.sosial[i];
    if (pilihan?.url) {
      a.href = pilihan.url;
      a.target = "_blank";
      a.rel = "noopener noreferrer";
      a.style.display = "";
    } else {
      a.style.display = "none";
    }
  });
}

export function LegacyShell({children,ppdbOpen=true,kontak}:{children:React.ReactNode;ppdbOpen?:boolean;kontak?:KontakSekolah}){
  const pathname=usePathname();
  const [ready,setReady]=useState(false);
  const wire=useCallback(()=>{
    window.wireHeaderFooter?.(activeMap[pathname]??(pathname.startsWith("/berita/")?"berita.php":""),ppdbOpen);
    if(kontak)tambalFooter(kontak);
  },[pathname,ppdbOpen,kontak]);
  useEffect(()=>{if(ready)wire()},[ready,wire]);
  return <><div id="site-header" className="sticky top-0 z-30"/><main>{children}</main><div id="site-footer"/><Script src="/assets/js/shared.js" strategy="afterInteractive" onLoad={()=>setReady(true)}/></>;
}
