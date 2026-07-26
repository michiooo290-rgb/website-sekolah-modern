import { requireAdmin } from "../actions";

export const metadata = { title: "Aktivitas" };

type LoginRow = {
  id: number;
  email: string;
  ip: string;
  success: boolean;
  created_at: string;
};

type AuditRow = {
  id: number;
  action: string;
  table_name: string | null;
  record_id: number | null;
  created_at: string;
  user_id: string | null;
};

const actionLabels: Record<string, string> = {
  insert: "Menambah data",
  update: "Memperbarui data",
  delete: "Menghapus data",
  read_message: "Menandai pesan dibaca",
  delete_message: "Menghapus pesan",
};

const tableLabels: Record<string, string> = {
  berita: "Berita",
  guru: "Guru & Staf",
  ekstrakurikuler: "Ekstrakurikuler",
  visi_misi: "Visi & Misi",
  tentang: "Tentang",
  ppdb_info: "PPDB",
  pengaturan: "Pengaturan",
  pesan_kontak: "Pesan Masuk",
};

function waktu(value: string) {
  return new Date(value).toLocaleString("id-ID", {
    dateStyle: "medium",
    timeStyle: "short",
    timeZone: "Asia/Jakarta",
  });
}

export default async function AktivitasPage() {
  const { supabase } = await requireAdmin();

  const [loginResult, auditResult] = await Promise.all([
    supabase
      .from("login_attempts")
      .select("id, email, ip, success, created_at")
      .order("created_at", { ascending: false })
      .limit(50),
    supabase
      .from("audit_log")
      .select("id, action, table_name, record_id, created_at, user_id")
      .order("created_at", { ascending: false })
      .limit(50),
  ]);

  // Tabel login_attempts dipasang lewat supabase/login-rate-limit.sql. Kalau
  // belum dijalankan, halaman ini tetap tampil dan hanya memberi petunjuk.
  const belumDipasang = Boolean(loginResult.error);
  const logins = (loginResult.data ?? []) as LoginRow[];
  const audit = (auditResult.data ?? []) as AuditRow[];

  const userIds = [...new Set(audit.map((row) => row.user_id).filter((value): value is string => Boolean(value)))];
  const profilesResult = userIds.length
    ? await supabase.from("profiles").select("id, nama").in("id", userIds)
    : { data: [] };
  const nama = new Map(
    ((profilesResult.data ?? []) as { id: string; nama: string }[]).map((row) => [row.id, row.nama]),
  );

  const sehariLalu = Date.now() - 24 * 60 * 60 * 1000;
  const gagalSehari = logins.filter(
    (row) => !row.success && new Date(row.created_at).getTime() > sehariLalu,
  ).length;

  return (
    <>
      <h1 style={{ fontSize: "3rem" }}>Aktivitas</h1>
      <p>Rekaman percobaan masuk ke panel dan riwayat perubahan data.</p>

      <article className="card mb-lg">
        <span className="eyebrow">Keamanan</span>
        <h2>Percobaan login</h2>
        {belumDipasang ? (
          <p className="notice error">
            Tabel pencatat percobaan login belum dipasang. Jalankan isi berkas
            supabase/login-rate-limit.sql di SQL Editor Supabase, lalu muat ulang halaman ini.
          </p>
        ) : (
          <>
            <p>
              {gagalSehari === 0
                ? "Tidak ada percobaan login yang gagal dalam 24 jam terakhir."
                : `${gagalSehari} percobaan login gagal dalam 24 jam terakhir.`}
            </p>
            {logins.length === 0 ? (
              <p className="empty">Belum ada percobaan login yang tercatat.</p>
            ) : (
              <div className="table-wrap">
                <table>
                  <thead>
                    <tr>
                      <th>Waktu</th>
                      <th>Email</th>
                      <th>Alamat IP</th>
                      <th>Hasil</th>
                    </tr>
                  </thead>
                  <tbody>
                    {logins.map((row) => (
                      <tr key={row.id}>
                        <td>{waktu(row.created_at)}</td>
                        <td>{row.email}</td>
                        <td>{row.ip}</td>
                        <td>
                          <span className="tag">{row.success ? "Berhasil" : "Gagal"}</span>
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            )}
          </>
        )}
      </article>

      <article className="card">
        <span className="eyebrow">Riwayat</span>
        <h2>Perubahan data</h2>
        {audit.length === 0 ? (
          <p className="empty">Belum ada perubahan data yang tercatat.</p>
        ) : (
          <div className="table-wrap">
            <table>
              <thead>
                <tr>
                  <th>Waktu</th>
                  <th>Tindakan</th>
                  <th>Bagian</th>
                  <th>ID</th>
                  <th>Oleh</th>
                </tr>
              </thead>
              <tbody>
                {audit.map((row) => (
                  <tr key={row.id}>
                    <td>{waktu(row.created_at)}</td>
                    <td>{actionLabels[row.action] ?? row.action}</td>
                    <td>{row.table_name ? tableLabels[row.table_name] ?? row.table_name : "-"}</td>
                    <td>{row.record_id ?? "-"}</td>
                    <td>{row.user_id ? nama.get(row.user_id) ?? "Pengguna terhapus" : "-"}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}
      </article>
    </>
  );
}
