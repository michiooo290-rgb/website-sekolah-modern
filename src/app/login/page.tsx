import { redirect } from "next/navigation";
import { createServerSupabase } from "@/lib/supabase";
import { login } from "./actions";

export const metadata={title:"Login Admin"};
export default async function LoginPage({searchParams}:{searchParams:Promise<{error?:string}>}){const [supabase,params]=await Promise.all([createServerSupabase(),searchParams]);if(supabase){const {data}=await supabase.auth.getUser();if(data.user)redirect("/admin")}return <section className="section"><div className="container" style={{maxWidth:480}}><article className="card"><span className="eyebrow">Area Pengelola</span><h1 style={{fontSize:"2.8rem"}}>Login admin</h1><p>Gunakan akun yang telah ditambahkan sebagai admin di Supabase.</p>{params.error&&<p className="notice error">{params.error==="config"?"Supabase belum dikonfigurasi.":"Email atau password tidak valid."}</p>}<form action={login} className="form"><div className="field"><label>Email</label><input type="email" name="email" required/></div><div className="field"><label>Password</label><input type="password" name="password" required/></div><button className="button">Masuk ke dashboard</button></form></article></div></section>}
