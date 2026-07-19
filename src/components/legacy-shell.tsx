"use client";

import Script from "next/script";
import { usePathname } from "next/navigation";
import { useCallback, useEffect, useState } from "react";

declare global { interface Window { wireHeaderFooter?: (active: string, open: boolean) => void } }

const activeMap: Record<string,string> = {"/":"index.php","/tentang":"tentang.php","/visi-misi":"visi-misi.php","/ekstrakurikuler":"ekstrakurikuler.php","/berita":"berita.php","/ppdb":"ppdb.php","/kontak":"kontak.php"};

export function LegacyShell({children,ppdbOpen=true}:{children:React.ReactNode;ppdbOpen?:boolean}){
  const pathname=usePathname();
  const [ready,setReady]=useState(false);
  const wire=useCallback(()=>{window.wireHeaderFooter?.(activeMap[pathname]??(pathname.startsWith("/berita/")?"berita.php":""),ppdbOpen)},[pathname,ppdbOpen]);
  useEffect(()=>{if(ready)wire()},[ready,wire]);
  return <><div id="site-header" className="sticky top-0 z-30"/><main>{children}</main><div id="site-footer"/><Script src="/assets/js/shared.js" strategy="afterInteractive" onLoad={()=>setReady(true)}/></>;
}
