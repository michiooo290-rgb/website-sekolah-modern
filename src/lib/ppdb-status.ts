import type { SettingMap } from "./types";

/** Kunci pada tabel pengaturan. */
export const PPDB_STATUS_KEY = "ppdb_status";
export const PPDB_CLOSING_KEY = "ppdb_tanggal_tutup";

export type PpdbStatus = {
  /** Keadaan efektif yang dilihat pengunjung. */
  open: boolean;
  /** Sakelar manual di dashboard, tanpa memperhitungkan tanggal. */
  manualOpen: boolean;
  /** Tanggal tutup otomatis dalam format YYYY-MM-DD, jika diisi. */
  closingDate: string | null;
  /** Benar bila tanggal tutup sudah terlewat. */
  expired: boolean;
  /** Tanggal tutup dalam bahasa Indonesia, misalnya 30 Juni 2026. */
  closingLabel: string | null;
};

const ISO_DATE = /^\d{4}-\d{2}-\d{2}$/;

/** Tanggal hari ini menurut waktu Jakarta, tidak bergantung zona waktu server. */
export function todayInJakarta() {
  return new Date(Date.now() + 7 * 60 * 60 * 1000).toISOString().slice(0, 10);
}

export function formatIndonesianDate(value: string | null) {
  if (!value || !ISO_DATE.test(value)) return null;
  return new Intl.DateTimeFormat("id-ID", {
    day: "numeric",
    month: "long",
    year: "numeric",
    timeZone: "UTC",
  }).format(new Date(`${value}T00:00:00Z`));
}

/**
 * Menentukan status PPDB dari pengaturan.
 *
 * Nilai status dinormalkan lebih dulu, sehingga "Tutup", " tutup ", dan
 * "TUTUP" diperlakukan sama. Selain kata tutup dan tanggal yang terlewat,
 * pendaftaran dianggap terbuka.
 */
export function resolvePpdbStatus(
  settings: SettingMap | Record<string, string | null | undefined>,
): PpdbStatus {
  const raw = String(settings[PPDB_STATUS_KEY] ?? "").trim().toLowerCase();
  const manualOpen = raw !== "tutup" && raw !== "ditutup" && raw !== "closed";

  const rawDate = String(settings[PPDB_CLOSING_KEY] ?? "").trim();
  const closingDate = ISO_DATE.test(rawDate) ? rawDate : null;
  const expired = closingDate !== null && todayInJakarta() > closingDate;

  return {
    open: manualOpen && !expired,
    manualOpen,
    closingDate,
    expired,
    closingLabel: formatIndonesianDate(closingDate),
  };
}
