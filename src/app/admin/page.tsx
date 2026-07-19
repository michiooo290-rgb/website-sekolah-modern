import { requireAdmin } from "./actions";
import { resources } from "@/lib/admin-resources";
import Link from "next/link";

export const metadata={title:"Dashboard Admin"};
export default async function Dashboard(){const {supabase,profile}=await requireAdmin();const cards=await Promise.all(Object.entries(resources).slice(0,6).map(async([key,config])=>{const {count}=await supabase.from(config.table).select("*",{count:"exact",head:true});return {key,label:config.label,count:count??0}}));return <><span className="eyebrow">Dashboard</span><h1 style={{fontSize:"3rem"}}>Selamat datang, {profile.nama}.</h1><p>Kelola informasi sekolah yang tampil di website.</p><div className="grid three">{cards.map(card=><Link className="card" href={`/admin/${card.key}`} key={card.key}><strong style={{fontSize:"2.5rem",fontFamily:"Georgia"}}>{card.count}</strong><p>{card.label}</p></Link>)}</div></>}
