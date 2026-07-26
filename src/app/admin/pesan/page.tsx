import { deleteMessages, readMessages, requireAdmin } from "../actions";
import Toast from "../toast";
import BulkMessages, { type MessageRow } from "./bulk-messages";

export const metadata = { title: "Pesan Masuk" };

/** "3" menjadi 3; nilai lama seperti "1" tetap bekerja. */
function jumlahDari(value: string | undefined) {
  const angka = Number(value);
  return Number.isSafeInteger(angka) && angka > 0 ? angka : 1;
}

function waktuPesan(value: string | null | undefined) {
  if (!value) return "";
  const tanggal = new Date(value);
  if (Number.isNaN(tanggal.getTime())) return "";
  return new Intl.DateTimeFormat("id-ID", {
    day: "numeric",
    month: "long",
    year: "numeric",
    hour: "2-digit",
    minute: "2-digit",
    timeZone: "Asia/Jakarta",
  }).format(tanggal);
}

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
      ? { tone: "ok" as const, message: `${jumlahDari(query.deleted)} pesan berhasil dihapus.` }
      : query.read
        ? { tone: "ok" as const, message: `${jumlahDari(query.read)} pesan ditandai sudah dibaca.` }
        : null;

  const rows: MessageRow[] = (data ?? []).map((item) => ({
    id: item.id,
    subjek: item.subjek || "Pesan dari website",
    nama: item.nama,
    kontak: [item.email, item.telepon].filter(Boolean).map((detail) => String(detail)),
    pesan: item.pesan,
    dibaca: Boolean(item.dibaca),
    waktu: waktuPesan(item.tanggal),
  }));

  return (
    <>
      {notifikasi && <Toast message={notifikasi.message} tone={notifikasi.tone} />}
      <span className="eyebrow">Kotak Masuk</span>
      <h1 style={{ fontSize: "3rem" }}>Pesan pengunjung</h1>
      <BulkMessages rows={rows} deleteAction={deleteMessages} readAction={readMessages} />
    </>
  );
}
