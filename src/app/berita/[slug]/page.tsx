import { notFound } from "next/navigation";
import Link from "next/link";
import Image from "next/image";
import sanitizeHtml from "sanitize-html";
import { formatDate, getNewsBySlug, imageUrl } from "@/lib/data";

export async function generateMetadata({params}:{params:Promise<{slug:string}>}){const {slug}=await params;const item=await getNewsBySlug(slug);return {title:item?.judul??"Berita"}}
export default async function NewsDetail({params}:{params:Promise<{slug:string}>}){const {slug}=await params;const item=await getNewsBySlug(slug);if(!item)notFound();const safe=sanitizeHtml(item.isi,{allowedTags:sanitizeHtml.defaults.allowedTags.concat(["img"]),allowedAttributes:{a:["href","target"],img:["src","alt"]}});return <article><header className="page-hero"><div className="container narrow"><span className="tag">{item.kategori}</span><h1>{item.judul}</h1><p>{formatDate(item.tanggal)} · {item.dilihat} kali dilihat</p></div></header><section className="section"><div className="container narrow">{item.gambar&&<Image src={imageUrl(item.gambar)} alt={item.judul} width={1100} height={650} style={{width:"100%",height:"auto",borderRadius:24,marginBottom:"2rem"}}/>}<div className="prose" dangerouslySetInnerHTML={{__html:safe}}/><Link className="button secondary" href="/berita">← Kembali ke berita</Link></div></section></article>}
