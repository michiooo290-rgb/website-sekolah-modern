"use server";

import { revalidatePath } from "next/cache";
import { redirect } from "next/navigation";
import { loadAdminSession } from "@/lib/admin-guard";
import { PPDB_CLOSING_KEY, PPDB_STATUS_KEY } from "@/lib/ppdb-status";

const ISO_DATE = /^\d{4}-\d{2}-\d{2}$/;

/** redirect() bekerja dengan melempar error berdigest; error itu harus lewat. */
function isFrameworkError(error: unknown) {
  const digest = (error as { digest?: unknown } | null)?.digest;
  return typeof digest === "string" && (digest.startsWith("NEXT_REDIRECT") || digest === "NEXT_NOT_FOUND");
}

function failureMessage(error: unknown) {
  if (error instanceof Error && error.message) return error.message;
  return "Terjadi kesalahan tak terduga";
}

async function persistPpdbStatus(formData: FormData) {
  const status = String(formData.get("status") ?? "").trim().toLowerCase() === "tutup" ? "tutup" : "buka";
  const rawDate = String(formData.get("tanggal_tutup") ?? "").trim();

  if (rawDate && !ISO_DATE.test(rawDate)) throw new Error("Tanggal tutup tidak valid");
  if (rawDate) {
    const parsed = new Date(`${rawDate}T00:00:00Z`);
    if (Number.isNaN(parsed.getTime())) throw new Error("Tanggal tutup tidak valid");
    const tahun = Number(rawDate.slice(0, 4));
    if (tahun < 2000 || tahun > 2100) throw new Error("Tahun pada tanggal tutup tidak wajar");
  }

  const { supabase, user } = await loadAdminSession();
  const { error } = await supabase
    .from("pengaturan")
    .upsert(
      [
        { kunci: PPDB_STATUS_KEY, nilai: status },
        { kunci: PPDB_CLOSING_KEY, nilai: rawDate },
      ],
      { onConflict: "kunci" },
    );
  if (error) throw new Error(`Status PPDB gagal disimpan: ${error.message}`);

  await supabase.from("audit_log").insert({
    user_id: user.id,
    action: "update",
    table_name: "pengaturan",
  });

  // Header publik, halaman PPDB, beranda, dan dashboard sama-sama membaca status ini.
  revalidatePath("/", "layout");
  revalidatePath("/ppdb");
  revalidatePath("/admin");
  revalidatePath("/admin/ppdb");

  return status === "tutup" ? "tutup" : rawDate ? "buka-berjadwal" : "buka";
}

export async function savePpdbStatus(formData: FormData) {
  let hasil = "buka";
  try {
    hasil = await persistPpdbStatus(formData);
  } catch (error) {
    if (isFrameworkError(error)) throw error;
    console.error("savePpdbStatus gagal", error);
    redirect(`/admin/ppdb?error=${encodeURIComponent(failureMessage(error))}`);
  }
  redirect(`/admin/ppdb?ppdb=${hasil}`);
}
