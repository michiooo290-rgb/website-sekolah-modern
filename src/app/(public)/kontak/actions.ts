"use server";

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
  const { error } = await supabase.rpc("submit_contact_message", {
    p_nama: payload.nama,
    p_email: payload.email,
    p_telepon: payload.telepon,
    p_subjek: payload.subjek,
    p_pesan: payload.pesan,
  });
  if (error?.message.includes("CONTACT_RATE_LIMITED")) redirect("/kontak?status=limited");
  redirect(error ? "/kontak?status=error" : "/kontak?status=success");
}
