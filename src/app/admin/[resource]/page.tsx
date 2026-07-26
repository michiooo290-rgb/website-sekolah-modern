import { notFound } from "next/navigation";
import { resources } from "@/lib/admin-resources";
import { deleteResource, requireAdmin, saveResource } from "../actions";

export default async function ResourcePage({
  params,
  searchParams,
}: {
  params: Promise<{ resource: string }>;
  searchParams: Promise<{ edit?: string; saved?: string; deleted?: string; error?: string }>;
}) {
  const [{ resource }, query] = await Promise.all([params, searchParams]);
  const config = resources[resource];
  if (!config) notFound();

  const { supabase } = await requireAdmin();
  let request = supabase.from(config.table).select("*");
  if (config.order) request = request.order(config.order, { ascending: resource !== "berita" });
  const { data: rows, error } = await request;
  if (error) throw new Error(error.message);

  const editing = query.edit ? rows?.find((row) => String(row.id) === query.edit) : null;
  const action = saveResource.bind(null, resource);

  return (
    <>
      <span className="eyebrow">Kelola Konten</span>
      <h1 style={{ fontSize: "3rem" }}>{config.label}</h1>
      {query.saved && <p className="notice">Perubahan berhasil disimpan.</p>}
      {query.deleted && <p className="notice">Data berhasil dihapus.</p>}
      {query.error && <p className="notice error">{query.error}</p>}
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
        <div className="table-wrap">
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Data</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              {rows?.length ? (
                rows.map((row) => (
                  <tr key={row.id}>
                    <td>{row.id}</td>
                    <td>
                      {config.fields.slice(0, 3).map((field) => (
                        <div key={field.name}>
                          <strong>{field.label}:</strong>{" "}
                          {String(row[field.name] ?? "").replace(/<[^>]+>/g, "").slice(0, 90)}
                        </div>
                      ))}
                    </td>
                    <td>
                      <a className="button secondary" href={`/admin/${resource}?edit=${row.id}`}>
                        Edit
                      </a>
                      <form action={deleteResource.bind(null, resource, row.id)} style={{ marginTop: 8 }}>
                        <button className="button danger">Hapus</button>
                      </form>
                    </td>
                  </tr>
                ))
              ) : (
                <tr>
                  <td colSpan={3}>
                    <p className="empty">Belum ada data.</p>
                  </td>
                </tr>
              )}
            </tbody>
          </table>
        </div>
      </div>
    </>
  );
}
