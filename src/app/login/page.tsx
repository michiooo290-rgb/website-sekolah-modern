import { redirect } from "next/navigation";
import { createServerSupabase } from "@/lib/supabase";
import { login } from "./actions";

export const metadata = { title: "Login Admin" };

export default async function LoginPage({ searchParams }: { searchParams: Promise<{ error?: string }> }) {
  const [supabase, params] = await Promise.all([createServerSupabase(), searchParams]);
  if (supabase) {
    const { data: { user } } = await supabase.auth.getUser();
    if (user) {
      const { data: profile } = await supabase.from("profiles").select("role").eq("id", user.id).maybeSingle();
      if (profile?.role === "admin") redirect("/admin");
    }
  }
  const message = params.error === "config"
    ? "Supabase belum dikonfigurasi."
    : params.error === "forbidden"
      ? "Akun ini tidak memiliki akses admin."
      : "Email atau password tidak valid.";
  return <section className="section"><div className="container" style={{ maxWidth: 480 }}><article className="card"><span className="eyebrow">Area Pengelola</span><h1 style={{ fontSize: "2.8rem" }}>Login admin</h1><p>Gunakan akun yang telah ditambahkan sebagai admin di Supabase.</p>{params.error && <p className="notice error">{message}</p>}<form action={login} className="form"><div className="field"><label>Email</label><input type="email" name="email" autoComplete="username" required maxLength={254} /></div><div className="field"><label>Password</label><input type="password" name="password" autoComplete="current-password" required minLength={8} maxLength={128} /></div><button className="button">Masuk ke dashboard</button></form></article></div></section>;
}
