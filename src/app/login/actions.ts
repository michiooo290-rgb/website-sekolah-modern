"use server";

import { redirect } from "next/navigation";
import { z } from "zod";
import { createServerSupabase } from "@/lib/supabase";

const loginSchema = z.object({
  email: z.string().trim().email().max(254),
  password: z.string().min(8).max(128),
});

export async function login(formData: FormData) {
  const parsed = loginSchema.safeParse({
    email: formData.get("email"),
    password: formData.get("password"),
  });
  if (!parsed.success) redirect("/login?error=credentials");

  const supabase = await createServerSupabase();
  if (!supabase) redirect("/login?error=config");

  const { data, error } = await supabase.auth.signInWithPassword(parsed.data);
  if (error || !data.user) redirect("/login?error=credentials");

  const { data: profile } = await supabase
    .from("profiles")
    .select("role")
    .eq("id", data.user.id)
    .maybeSingle();

  if (profile?.role !== "admin") {
    await supabase.auth.signOut();
    redirect("/login?error=forbidden");
  }
  redirect("/admin");
}
