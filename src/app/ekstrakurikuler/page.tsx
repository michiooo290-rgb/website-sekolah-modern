import { PageHero } from "@/components/page-hero";
import { getActivities } from "@/lib/data";
import { CalendarDays, UserRound } from "lucide-react";

export const metadata={title:"Ekstrakurikuler"};
export default async function ActivitiesPage(){const activities=await getActivities();const groups=Object.groupBy(activities,x=>x.kategori);return <><PageHero eyebrow="Temukan Potensimu" title="Lebih dari pelajaran di dalam kelas." description="Ruang untuk mencoba, berlatih, memimpin, berkarya, dan menemukan bakat terbaik."/><section className="section"><div className="container">{Object.entries(groups).map(([category,items])=><section key={category} style={{marginBottom:"4rem"}}><div className="section-head"><div><span className="eyebrow">Kategori</span><h2>{category}</h2></div></div><div className="grid three">{items?.map(item=><article className="card" key={item.id}><span className="tag">{item.kategori}</span><h3>{item.nama}</h3><p>{item.deskripsi}</p>{item.pembina&&<p><UserRound size={15}/> {item.pembina}</p>}{item.jadwal&&<p><CalendarDays size={15}/> {item.jadwal}</p>}</article>)}</div></section>)}</div></section></>}
