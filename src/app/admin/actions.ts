"use server";
import { revalidatePath } from "next/cache";
import { redirect } from "next/navigation";
import { createServerSupabase } from "@/lib/supabase";
import { resources, slugify } from "@/lib/admin-resources";

export async function requireAdmin(){const supabase=await createServerSupabase();if(!supabase)redirect("/login?error=config");const {data:{user}}=await supabase.auth.getUser();if(!user)redirect("/login");const {data:profile}=await supabase.from("profiles").select("role,nama").eq("id",user.id).maybeSingle();if(!profile||profile.role!=="admin")redirect("/login?error=forbidden");return {supabase,user,profile}}
export async function logout(){const supabase=await createServerSupabase();await supabase?.auth.signOut();redirect("/login")}

export async function saveResource(resource:string,formData:FormData){const config=resources[resource];if(!config)throw new Error("Resource tidak valid");const {supabase}=await requireAdmin();const id=String(formData.get("id")??"");const payload:Record<string,string|number|null>={};for(const field of config.fields){const raw=formData.get(field.name);if(field.type==="file"){if(raw instanceof File&&raw.size>0){const ext=raw.name.split(".").pop()?.replace(/[^a-z0-9]/gi,"")||"bin";const path=`${config.table}/${crypto.randomUUID()}.${ext}`;const {error}=await supabase.storage.from("media").upload(path,raw,{contentType:raw.type,upsert:false});if(error)throw new Error(error.message);payload[field.name]=path}continue}const value=String(raw??"").trim();payload[field.name]=field.type==="number"?(Number(value)||0):(value||null)}if(resource==="berita"&&!payload.slug)payload.slug=slugify(String(payload.judul??"berita"));const query=id?supabase.from(config.table).update(payload).eq("id",Number(id)):supabase.from(config.table).insert(payload);const {error}=await query;if(error)throw new Error(error.message);revalidatePath("/");revalidatePath(`/admin/${resource}`);redirect(`/admin/${resource}?saved=1`)}

export async function deleteResource(resource:string,id:number){const config=resources[resource];if(!config)throw new Error("Resource tidak valid");const {supabase}=await requireAdmin();const {error}=await supabase.from(config.table).delete().eq("id",id);if(error)throw new Error(error.message);revalidatePath("/");revalidatePath(`/admin/${resource}`)}

export async function markMessage(id:number,remove=false){const {supabase}=await requireAdmin();const query=remove?supabase.from("pesan_kontak").delete().eq("id",id):supabase.from("pesan_kontak").update({dibaca:true}).eq("id",id);const {error}=await query;if(error)throw new Error(error.message);revalidatePath("/admin/pesan")}
