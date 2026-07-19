"use server";
import { redirect } from "next/navigation";
import { z } from "zod";
import { createServerSupabase } from "@/lib/supabase";

const schema=z.object({nama:z.string().min(2).max(100),email:z.string().email(),telepon:z.string().max(30).optional(),subjek:z.string().max(100).optional(),pesan:z.string().min(10).max(3000),website:z.string().max(0)});
export async function sendMessage(formData:FormData){const parsed=schema.safeParse(Object.fromEntries(formData));if(!parsed.success)redirect("/kontak?status=invalid");const supabase=await createServerSupabase();if(!supabase)redirect("/kontak?status=demo");const {website,...payload}=parsed.data;void website;const {error}=await supabase.from("pesan_kontak").insert(payload);redirect(error?"/kontak?status=error":"/kontak?status=success")}
