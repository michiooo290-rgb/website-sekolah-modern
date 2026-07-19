export type SettingMap = Record<string, string>;

export type News = {
  id: number;
  judul: string;
  slug: string;
  kategori: string;
  isi: string;
  gambar: string | null;
  tanggal: string;
  dilihat: number;
};

export type Teacher = {
  id: number;
  nama: string;
  jabatan: string;
  mapel: string | null;
  foto: string | null;
  urutan: number;
};

export type Activity = {
  id: number;
  nama: string;
  kategori: string;
  deskripsi: string;
  pembina: string | null;
  jadwal: string | null;
};

export type VisionItem = {
  id: number;
  tipe: "visi" | "misi" | "tujuan" | "nilai";
  judul: string | null;
  isi: string;
  urutan: number;
};

export type AboutItem = {
  id: number;
  bagian: string;
  judul: string | null;
  isi: string;
};

export type PpdbItem = {
  id: number;
  bagian: "syarat" | "jadwal" | "alur" | "lokasi" | "faq";
  judul: string | null;
  isi: string;
  tanggal: string | null;
  file_formulir: string | null;
  urutan: number;
};
