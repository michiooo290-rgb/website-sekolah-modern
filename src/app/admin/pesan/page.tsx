import { markMessage, requireAdmin } from "../actions";
import Toast from "../toast";

export const metadata = { title: "Pesan Masuk" };

export default async function MessagesPage({
  searchParams,
}: {
  searchParams: Promise<{ read?: string; deleted?: string; error?: string }>;
}) {
  const [query, { supabase }] = await Promise.all([searchParams, requireAdmin()]);
  const { data } = await supabase
    .from("pesan_kontak")
    .select("*")
    .order("tanggal", { ascending: false });

  const notifikasi = query.error
    ? { tone: "error" as const, message: query.error }
    : query.deleted
      ? { tone: "ok" as const, message: "Pesan berhasil dihapus." }
      : query.read
        ? { tone: "ok" as const, message: "Pesan ditandai sudah dibaca." }
        : null;

  return (
    <>
      {notifikasi && <Toast message={notifikasi.message} tone={notifikasi.tone} />}
      <span className="eyebrow">Kotak Masuk</span>
      <h1 style={{ fontSize: "3rem" }}>Pesan pengunjung</h1>
      {data?.length ? (
        <div className="grid two">
          {data.map((item) => (
            <article className="card" key={item.id} style={{ opacity: item.dibaca ? 0.8 : 1 }}>
              <span className="tag">{item.dibaca ? "Sudah dibaca" : "Baru"}</span>
              <h3>{item.subjek || "Pesan dari website"}</h3>
              <p>
                <strong>{item.nama}</strong>
                {[item.email, item.telepon].filter(Boolean).map((detail) => (
                  <span key={String(detail)}> &middot; {String(detail)}</span>
                ))}
              </p>
              <p>{item.pesan}</p>
              <div className="actions">
                {!item.dibaca && (
                  <form action={markMessage.bind(null, item.id, false)}>
                    <button className="button secondary">Tandai dibaca</button>
                  </form>
                )}
                <form action={markMessage.bind(null, item.id, true)}>
                  <button className="button danger">Hapus</button>
                </form>
              </div>
            </article>
          ))}
        </div>
      ) : (
        <p className="empty">Belum ada pesan dari pengunjung.</p>
      )}
    </>
  );
}
