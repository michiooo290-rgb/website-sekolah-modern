"use server";
import { redirect } from "next/navigation";
import { createServerSupabase } from "@/lib/supabase";

export async function login(formData:FormData){const email=String(formData.get("email")??"");const password=String(formData.get("password")??"");const supabase=await createServerSupabase();if(!supabase)redirect("/login?error=config");const {error}=await supabase.auth.signInWithPassword({email,password});if(error)redirect("/login?error=credentials");redirect("/admin")}
