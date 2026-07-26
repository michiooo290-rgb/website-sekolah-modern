import Image from "next/image";
import Link from "next/link";
import { redirect } from "next/navigation";
import { createServerSupabase } from "@/lib/supabase";
import { login } from "./actions";
import { SubmitButton } from "./submit-button";

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
    ? "Supabase belum dikonfigurasi. Hubungi pengelola server."
    : params.error === "forbidden"
      ? "Akun ini tidak memiliki akses admin."
      : "Email atau password tidak valid.";

  return (
    <section className="section">
      <div className="container">
        <p className="login-brand">
          <Image src="/assets/img/logo.jpeg" alt="" width={36} height={36} />
          SMA Putra Persada Batam
        </p>
        <article className="card">
          <span className="eyebrow">Area Pengelola</span>
          <h1>Login admin</h1>
          <p>Gunakan akun yang telah ditambahkan sebagai admin di Supabase.</p>
          {params.error && <p className="notice error">{message}</p>}
          <form action={login} className="form">
            <div className="field">
              <label htmlFor="email">Email</label>
              <input id="email" type="email" name="email" autoComplete="username" placeholder="nama@sekolah.sch.id" required maxLength={254} />
            </div>
            <div className="field">
              <label htmlFor="password">Password</label>
              <input id="password" type="password" name="password" autoComplete="current-password" placeholder="Minimal 8 karakter" required minLength={8} maxLength={128} />
            </div>
            <SubmitButton />
          </form>
        </article>
        <p className="login-foot">
          <Link href="/">&larr; Kembali ke website sekolah</Link>
        </p>
      </div>
    </section>
  );
}
