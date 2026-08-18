/* Titik peta sekolah.
 *
 * Teks alamat sengaja tidak dipakai sebagai sumber titik peta: hasil geocoding
 * Google atas alamat sekolah meleset sekitar 75 m ke timur dari gedung yang
 * sebenarnya. Alamat tetap ditampilkan sebagai teks di halaman.
 */

/** Koordinat sekolah. Dipakai bila `peta_koordinat` kosong atau tidak valid. */
export const KOORDINAT_BAWAAN = "1.14185,104.13783";

/** Sepasang angka desimal, misalnya "1.14185,104.13783". */
const POLA_KOORDINAT = /^\s*(-?\d{1,3}(?:\.\d+)?)\s*,\s*(-?\d{1,3}(?:\.\d+)?)\s*$/;

/**
 * Mengubah nilai pengaturan `peta_koordinat` menjadi titik peta yang aman.
 *
 * Nilai yang bukan pasangan koordinat -- teks alamat, isian kosong, atau
 * placeholder yang belum diganti seperti "LAT,LNG" -- diabaikan dan diganti
 * KOORDINAT_BAWAAN. Tanpa penjagaan ini teks apa pun ikut terkirim ke Google
 * Maps, lalu dicocokkan sebagai kata kunci pencarian dan bisa menghasilkan pin
 * di kota yang keliru.
 */
export function titikPeta(nilai?: string): string {
  const cocok = POLA_KOORDINAT.exec(nilai ?? "");
  if (!cocok) return KOORDINAT_BAWAAN;
  const lintang = Number(cocok[1]);
  const bujur = Number(cocok[2]);
  if (Math.abs(lintang) > 90 || Math.abs(bujur) > 180) return KOORDINAT_BAWAAN;
  return `${lintang},${bujur}`;
}

/** URL sematan dan URL rute Google Maps untuk satu titik koordinat. */
export function urlPeta(koordinat: string): { src: string; tautan: string } {
  const kueri = encodeURIComponent(koordinat);
  const host = "https:" + "//www.google.com";
  return {
    src: `${host}/maps?q=${kueri}&output=embed&z=17`,
    tautan: `${host}/maps/dir/?api=1&destination=${kueri}`,
  };
}
