"use server";

import { headers } from "next/headers";
import { redirect } from "next/navigation";
import { z } from "zod";
import { createServerSupabase } from "@/lib/supabase";

const loginSchema = z.object({
  email: z.string().trim().email().max(254),
  password: z.string().min(8).max(128),
});

async function clientIp() {
  const h = await headers();
  const forwarded = h.get("x-forwarded-for");
  if (forwarded) {
    const first = forwarded.split(",")[0];
    if (first && first.trim()) return first.trim().slice(0, 60);
  }
  const real = h.get("x-real-ip");
  if (real && real.trim()) return real.trim().slice(0, 60);
  return "unknown";
}

export async function login(formData: FormData) {
  const parsed = loginSchema.safeParse({
    email: formData.get("email"),
    password: formData.get("password"),
  });
  if (!parsed.success) redirect("/login?error=credentials");

  const supabase = await createServerSupabase();
  if (!supabase) redirect("/login?error=config");

  const { email, password } = parsed.data;
  const ip = await clientIp();

  // Gerbang pembatas. Sengaja fail-open: kalau fungsi RPC belum dipasang di
  // Supabase, login tetap bisa berjalan agar admin tidak terkunci di luar.
  const gateResult = await supabase.rpc("check_login_allowed", { p_email: email, p_ip: ip });
  const gate = gateResult.data as { allowed?: boolean } | null;
  if (gate && gate.allowed === false) redirect("/login?error=locked");

  const { data, error } = await supabase.auth.signInWithPassword({ email, password });
  if (error || !data.user) {
    await supabase.rpc("record_login_attempt", { p_email: email, p_ip: ip, p_success: false });
    redirect("/login?error=credentials");
  }

  const { data: profile } = await supabase
    .from("profiles")
    .select("role")
    .eq("id", data.user.id)
    .maybeSingle();

  if (profile?.role !== "admin") {
    await supabase.rpc("record_login_attempt", { p_email: email, p_ip: ip, p_success: false });
    await supabase.auth.signOut();
    redirect("/login?error=forbidden");
  }

  await supabase.rpc("record_login_attempt", { p_email: email, p_ip: ip, p_success: true });
  redirect("/admin");
}
