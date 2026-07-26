import { cache } from "react";
import { redirect } from "next/navigation";
import { createServerSupabase } from "@/lib/supabase";

/**
 * Penjaga area admin.
 *
 * Dibungkus React cache() sehingga verifikasi token dan pembacaan profil
 * hanya menembak Supabase sekali per request, meskipun layout dan page
 * sama-sama memanggilnya. Cache ini hidup selama satu request saja, jadi
 * sesi satu pengguna tidak pernah terbawa ke pengguna lain.
 *
 * Pengecekan tetap wajib dipanggil di setiap page area admin: layout tidak
 * selalu dieksekusi ulang pada navigasi sisi klien antar rute bersaudara,
 * sehingga layout sendirian bukan batas keamanan yang cukup.
 */
export const loadAdminSession = cache(async () => {
  const supabase = await createServerSupabase();
  if (!supabase) redirect("/login?error=config");

  // getUser() memverifikasi token ke server Supabase, bukan sekadar membaca
  // cookie, sehingga cookie yang dipalsukan tetap ditolak.
  const { data: { user } } = await supabase.auth.getUser();
  if (!user) redirect("/login");

  const { data: profile } = await supabase
    .from("profiles")
    .select("role,nama")
    .eq("id", user.id)
    .maybeSingle();

  if (!profile || profile.role !== "admin") {
    await supabase.auth.signOut();
    redirect("/login?error=forbidden");
  }

  return { supabase, user, profile };
});
