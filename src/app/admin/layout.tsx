import Link from "next/link";
import { logout, requireAdmin } from "./actions";
import { resources } from "@/lib/admin-resources";

export default async function AdminLayout({children}:{children:React.ReactNode}){const {profile}=await requireAdmin();return <div className="admin-shell"><aside className="admin-nav"><Link href="/admin"><strong>Dashboard</strong></Link>{Object.entries(resources).map(([key,value])=><Link key={key} href={`/admin/${key}`}>{value.label}</Link>)}<Link href="/admin/pesan">Pesan Masuk</Link><form action={logout}><button className="button" style={{marginTop:12}}>Keluar</button></form><small style={{display:"block",padding:12,opacity:.6}}>{profile.nama}</small></aside><section className="admin-main">{children}</section></div>}
