"use server";

import { headers } from "next/headers";
import { redirect } from "next/navigation";
import { z } from "zod";
import { createServerSupabase } from "@/lib/supabase";

const optionalText = (max: number) => z.string().trim().max(max).transform((value) => value || null);
const schema = z.object({
  nama: z.string().trim().min(2).max(100),
  email: z.string().trim().email().max(254).transform((value) => value.toLowerCase()),
  telepon: optionalText(30),
  subjek: optionalText(100),
  pesan: z.string().trim().min(10).max(3000),
  website: z.string().max(0),
});

/**
 * Alamat IP pengirim, dipakai submit_contact_message sebagai pembatas laju
 * kedua. Pembatas per-email saja tidak cukup karena pengirim spam cukup
 * mengganti alamat email pada tiap kiriman.
 *
 * Di belakang Vercel, x-forwarded-for ditulis ulang oleh platform sehingga
 * pengunjung tidak bisa memalsukannya.
 */
async function clientIp() {
  const h = await headers();
  const forwarded = h.get("x-forwarded-for");
  if (forwarded) {
    const first = forwarded.split(",")[0];
    if (first && first.trim()) return first.trim().slice(0, 60);
  }
  const real = h.get("x-real-ip");
  if (real && real.trim()) return real.trim().slice(0, 60);
  return null;
}

/** Fungsi RPC versi lama belum mengenal argumen p_ip. */
function butuhVersiLama(message: string) {
  return /p_ip|PGRST202|schema cache|does not exist/i.test(message);
}

export async function sendMessage(formData: FormData) {
  const parsed = schema.safeParse({
    nama: formData.get("nama"),
    email: formData.get("email"),
    telepon: formData.get("telepon") ?? "",
    subjek: formData.get("subjek") ?? "",
    pesan: formData.get("pesan"),
    website: formData.get("website") ?? "",
  });
  if (!parsed.success) redirect("/kontak?status=invalid");
  const supabase = await createServerSupabase();
  if (!supabase) redirect("/kontak?status=demo");

  const { website: _honeypot, ...payload } = parsed.data;
  void _honeypot;
  const dasar = {
    p_nama: payload.nama,
    p_email: payload.email,
    p_telepon: payload.telepon,
    p_subjek: payload.subjek,
    p_pesan: payload.pesan,
  };

  let { error } = await supabase.rpc("submit_contact_message", { ...dasar, p_ip: await clientIp() });

  // Selama supabase/schema.sql versi baru belum dijalankan, database masih
  // memakai fungsi berargumen lima. Kirim ulang tanpa p_ip supaya formulir
  // kontak tetap hidup dan tidak diam-diam menolak semua pesan.
  if (error && butuhVersiLama(error.message)) {
    ({ error } = await supabase.rpc("submit_contact_message", dasar));
  }

  if (error?.message.includes("CONTACT_RATE_LIMITED")) redirect("/kontak?status=limited");
  redirect(error ? "/kontak?status=error" : "/kontak?status=success");
}
