import { notFound } from "next/navigation";
import { resources } from "@/lib/admin-resources";
import { PPDB_CLOSING_KEY, PPDB_STATUS_KEY, resolvePpdbStatus } from "@/lib/ppdb-status";
import { deleteResources, requireAdmin, saveResource } from "../actions";
import { savePpdbStatus } from "../ppdb-actions";
import PpdbPanel from "../ppdb-panel";
import Toast from "../toast";
import BulkTable, { type BulkRow } from "./bulk-table";

function ringkasNilai(value: unknown) {
  const teks = String(value ?? "").replace(/<[^>]+>/g, "").trim();
  if (!teks) return "\u2014";
  return teks.length > 90 ? `${teks.slice(0, 90)}\u2026` : teks;
}

function pesanPpdb(kode: string) {
  if (kode === "tutup") return "PPDB ditutup. Halaman publik kini menampilkan pemberitahuan.";
  if (kode === "buka-berjadwal") return "PPDB dibuka dengan tanggal tutup otomatis.";
  return "PPDB dibuka untuk pendaftar baru.";
}

export default async function ResourcePage({
  params,
  searchParams,
}: {
  params: Promise<{ resource: string }>;
  searchParams: Promise<{ edit?: string; saved?: string; deleted?: string; ppdb?: string; error?: string }>;
}) {
  const [{ resource }, query] = await Promise.all([params, searchParams]);
  const config = resources[resource];
  if (!config) notFound();

  const { supabase } = await requireAdmin();
  let request = supabase.from(config.table).select("*");
  if (config.order) request = request.order(config.order, { ascending: resource !== "berita" });
  const { data: rows, error } = await request;
  if (error) throw new Error(error.message);

  // Hanya menu PPDB yang memerlukan pengaturan status pendaftaran.
  let ppdbStatus = null;
  if (resource === "ppdb") {
    const { data: pengaturan } = await supabase
      .from("pengaturan")
      .select("kunci,nilai")
      .in("kunci", [PPDB_STATUS_KEY, PPDB_CLOSING_KEY]);
    const map = Object.fromEntries(
      ((pengaturan ?? []) as Array<{ kunci: string; nilai: string | null }>).map((row) => [row.kunci, row.nilai]),
    );
    ppdbStatus = resolvePpdbStatus(map);
  }

  const editing = query.edit ? rows?.find((row) => String(row.id) === query.edit) : null;
  const action = saveResource.bind(null, resource);
  const bulkAction = deleteResources.bind(null, resource);

  const bulkRows: BulkRow[] = (rows ?? []).map((row) => ({
    id: Number(row.id),
    cells: config.fields.slice(0, 3).map((field) => ({
      label: field.label,
      value: ringkasNilai(row[field.name]),
    })),
  }));

  const jumlahDihapus = Number(query.deleted ?? 0);
  const notifikasi = query.error
    ? { tone: "error" as const, message: query.error }
    : query.ppdb
      ? { tone: "ok" as const, message: pesanPpdb(query.ppdb) }
      : query.saved
        ? { tone: "ok" as const, message: "Perubahan berhasil disimpan." }
        : query.deleted
          ? {
              tone: "ok" as const,
              message:
                jumlahDihapus > 1 ? `${jumlahDihapus} data berhasil dihapus.` : "Data berhasil dihapus.",
            }
          : null;

  return (
    <>
      {notifikasi && <Toast message={notifikasi.message} tone={notifikasi.tone} />}
      <span className="eyebrow">Kelola Konten</span>
      <h1 style={{ fontSize: "3rem" }}>{config.label}</h1>
      {ppdbStatus && (
        <PpdbPanel
          manualOpen={ppdbStatus.manualOpen}
          closingDate={ppdbStatus.closingDate}
          effectiveOpen={ppdbStatus.open}
          expired={ppdbStatus.expired}
          closingLabel={ppdbStatus.closingLabel}
          action={savePpdbStatus}
        />
      )}
      <div className="grid two" style={{ alignItems: "start" }}>
        <article className="card">
          <h2>{editing ? "Edit data" : "Tambah data"}</h2>
          <form action={action} className="form">
            <input type="hidden" name="id" value={editing?.id ?? ""} />
            {config.fields.map((field) => (
              <div className="field" key={field.name}>
                <label>{field.label}</label>
                {field.type === "textarea" ? (
                  <textarea name={field.name} required={field.required} defaultValue={editing?.[field.name] ?? ""} />
                ) : field.type === "select" ? (
                  <select name={field.name} required={field.required} defaultValue={editing?.[field.name] ?? ""}>
                    {field.options?.map((option) => (
                      <option key={option}>{option}</option>
                    ))}
                  </select>
                ) : (
                  <input
                    name={field.name}
                    type={field.type ?? "text"}
                    required={field.required && !editing}
                    defaultValue={field.type === "file" ? undefined : (editing?.[field.name] ?? "")}
                  />
                )}
              </div>
            ))}
            <div className="actions">
              <button className="button">{editing ? "Simpan perubahan" : "Tambah data"}</button>
              {editing && (
                <a className="button secondary" href={`/admin/${resource}`}>
                  Batal
                </a>
              )}
            </div>
          </form>
        </article>
        <BulkTable resource={resource} label={config.label} rows={bulkRows} action={bulkAction} />
      </div>
    </>
  );
}
