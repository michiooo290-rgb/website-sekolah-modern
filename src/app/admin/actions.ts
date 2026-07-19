"use server";

import { revalidatePath } from "next/cache";
import { redirect } from "next/navigation";
import sanitizeHtml from "sanitize-html";
import { createServerSupabase } from "@/lib/supabase";
import { resources, slugify, type FieldConfig } from "@/lib/admin-resources";

const MAX_IMAGE_SIZE = 5 * 1024 * 1024;
const MAX_PDF_SIZE = 10 * 1024 * 1024;
const SAFE_HTML_TAGS = ["p", "br", "strong", "b", "em", "i", "ul", "ol", "li", "blockquote", "h2", "h3", "a"];

function positiveId(value: unknown) {
  const id = Number(value);
  if (!Number.isSafeInteger(id) || id <= 0) throw new Error("Permintaan tidak valid");
  return id;
}

function cleanText(value: string, max: number) {
  const cleaned = value.replace(/[\u0000-\u0008\u000B\u000C\u000E-\u001F\u007F]/g, "").trim();
  if (cleaned.length > max) throw new Error("Isian terlalu panjang");
  return cleaned;
}

function cleanField(field: FieldConfig, raw: FormDataEntryValue | null) {
  const value = cleanText(String(raw ?? ""), field.type === "textarea" ? 20_000 : 500);
  if (field.required && !value) throw new Error(`${field.label} wajib diisi`);
  if (field.type === "select" && value && !field.options?.includes(value)) throw new Error("Pilihan tidak valid");
  if (field.type === "date" && value && !/^\d{4}-\d{2}-\d{2}$/.test(value)) throw new Error("Tanggal tidak valid");
  if (field.type === "number") {
    if (!value) return 0;
    const number = Number(value);
    if (!Number.isSafeInteger(number) || Math.abs(number) > 1_000_000) throw new Error("Angka tidak valid");
    return number;
  }
  if (field.type === "textarea" && field.name === "isi") {
    return sanitizeHtml(value, {
      allowedTags: SAFE_HTML_TAGS,
      allowedAttributes: { a: ["href", "target", "rel"] },
      allowedSchemes: ["http", "https", "mailto"],
      transformTags: { a: sanitizeHtml.simpleTransform("a", { rel: "noopener noreferrer" }) },
    });
  }
  return value || null;
}

async function inspectUpload(file: File, fieldName: string) {
  const bytes = new Uint8Array(await file.slice(0, 12).arrayBuffer());
  const isJpeg = bytes[0] === 0xff && bytes[1] === 0xd8 && bytes[2] === 0xff;
  const isPng = bytes.length >= 8 && [0x89,0x50,0x4e,0x47,0x0d,0x0a,0x1a,0x0a].every((byte, index) => bytes[index] === byte);
  const isWebp = bytes.length >= 12 && String.fromCharCode(...bytes.slice(0, 4)) === "RIFF" && String.fromCharCode(...bytes.slice(8, 12)) === "WEBP";
  const isPdf = bytes.length >= 5 && String.fromCharCode(...bytes.slice(0, 5)) === "%PDF-";

  if (fieldName === "file_formulir") {
    if (!isPdf || file.size > MAX_PDF_SIZE) throw new Error("Formulir harus PDF dan maksimal 10 MB");
    return { extension: "pdf", contentType: "application/pdf" };
  }
  if (file.size > MAX_IMAGE_SIZE) throw new Error("Gambar maksimal 5 MB");
  if (isJpeg) return { extension: "jpg", contentType: "image/jpeg" };
  if (isPng) return { extension: "png", contentType: "image/png" };
  if (isWebp) return { extension: "webp", contentType: "image/webp" };
  throw new Error("Gambar harus berupa JPEG, PNG, atau WebP yang valid");
}

export async function requireAdmin() {
  const supabase = await createServerSupabase();
  if (!supabase) redirect("/login?error=config");
  const { data: { user } } = await supabase.auth.getUser();
  if (!user) redirect("/login");
  const { data: profile } = await supabase.from("profiles").select("role,nama").eq("id", user.id).maybeSingle();
  if (!profile || profile.role !== "admin") {
    await supabase.auth.signOut();
    redirect("/login?error=forbidden");
  }
  return { supabase, user, profile };
}

export async function logout() {
  const supabase = await createServerSupabase();
  await supabase?.auth.signOut();
  redirect("/login");
}

export async function saveResource(resource: string, formData: FormData) {
  const config = resources[resource];
  if (!config) throw new Error("Resource tidak valid");
  const { supabase, user } = await requireAdmin();
  const rawId = cleanText(String(formData.get("id") ?? ""), 20);
  const id = rawId ? positiveId(rawId) : null;
  const payload: Record<string, string | number | null> = {};

  for (const field of config.fields) {
    const raw = formData.get(field.name);
    if (field.type === "file") {
      if (raw instanceof File && raw.size > 0) {
        const safeFile = await inspectUpload(raw, field.name);
        const path = `${config.table}/${crypto.randomUUID()}.${safeFile.extension}`;
        const { error } = await supabase.storage.from("media").upload(path, raw, { contentType: safeFile.contentType, upsert: false });
        if (error) throw new Error("File gagal diunggah");
        payload[field.name] = path;
      }
      continue;
    }
    payload[field.name] = cleanField(field, raw);
  }

  if (resource === "berita") {
    payload.slug = slugify(String(payload.slug || payload.judul || "berita")).slice(0, 180);
    if (!payload.slug) throw new Error("Slug tidak valid");
  }

  const mutation = id
    ? supabase.from(config.table).update(payload).eq("id", id)
    : supabase.from(config.table).insert(payload);
  const { data: saved, error } = await mutation.select("id").single();
  if (error || !saved) throw new Error("Data gagal disimpan");

  await supabase.from("audit_log").insert({
    user_id: user.id,
    action: id ? "update" : "insert",
    table_name: config.table,
    record_id: saved.id,
  });
  revalidatePath("/");
  revalidatePath(`/admin/${resource}`);
  redirect(`/admin/${resource}?saved=1`);
}

export async function deleteResource(resource: string, rawId: number) {
  const config = resources[resource];
  if (!config) throw new Error("Resource tidak valid");
  const id = positiveId(rawId);
  const { supabase, user } = await requireAdmin();
  const { error } = await supabase.from(config.table).delete().eq("id", id);
  if (error) throw new Error("Data gagal dihapus");
  await supabase.from("audit_log").insert({ user_id: user.id, action: "delete", table_name: config.table, record_id: id });
  revalidatePath("/");
  revalidatePath(`/admin/${resource}`);
}

export async function markMessage(rawId: number, remove = false) {
  const id = positiveId(rawId);
  const { supabase, user } = await requireAdmin();
  const query = remove
    ? supabase.from("pesan_kontak").delete().eq("id", id)
    : supabase.from("pesan_kontak").update({ dibaca: true }).eq("id", id);
  const { error } = await query;
  if (error) throw new Error("Pesan gagal diperbarui");
  await supabase.from("audit_log").insert({ user_id: user.id, action: remove ? "delete_message" : "read_message", table_name: "pesan_kontak", record_id: id });
  revalidatePath("/admin/pesan");
}
