import Link from "next/link";
import { PageHero } from "@/components/page-hero";
import { formatDate, getNews, plainText } from "@/lib/data";

export const metadata={title:"Berita"};
export default async function NewsPage(){const news=await getNews();return <><PageHero eyebrow="Kabar Sekolah" title="Berita, prestasi, dan cerita kami." description="Ikuti kegiatan, pencapaian siswa, dan perkembangan terbaru sekolah."/><section className="section"><div className="container grid three">{news.map(item=><Link href={`/berita/${item.slug}`} className="card news-card" key={item.id}><span className="tag">{item.kategori}</span><div className="meta">{formatDate(item.tanggal)} · {item.dilihat} dilihat</div><h3>{item.judul}</h3><p>{plainText(item.isi).slice(0,150)}…</p><span className="read-more">Baca berita →</span></Link>)}</div></section></>}
