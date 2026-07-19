import type { Activity, News, SettingMap, Teacher, VisionItem } from "./types";

export const fallbackSettings: SettingMap = {
  nama_sekolah: "SMA Putra Persada Batam",
  tagline: "Unggul & Berakhlak",
  akreditasi: "B",
  label_akreditasi: "Akreditasi",
  peserta_didik: "750+",
  label_peserta: "Peserta Didik",
  pengajar: "45+",
  label_pengajar: "Pengajar",
  alamat: "Jl. Raya Persada, Batam Center, Kota Batam, Kepulauan Riau",
  telepon: "(0778) XXX-XXXX",
  email: "info@smaputrapersada.sch.id",
  jam_operasional: "Senin–Jumat, 07.00–15.00 WIB",
  ppdb_status: "buka",
};

export const fallbackNews: News[] = [
  { id: 1, judul: "Siswa Raih Emas Olimpiade Sains Provinsi Kepri", slug: "osn-emas", kategori: "Prestasi", isi: "Alhamdulillah, sekolah kembali meraih prestasi membanggakan di Olimpiade Sains Provinsi Kepulauan Riau.", gambar: null, tanggal: "2026-06-12", dilihat: 245 },
  { id: 2, judul: "Pentas Seni & Perpisahan Kelas XII Berlangsung Meriah", slug: "pentas-seni", kategori: "Kegiatan", isi: "Suasana haru bercampur bahagia menyelimuti acara pentas seni dan perpisahan kelas XII.", gambar: null, tanggal: "2026-06-08", dilihat: 189 },
  { id: 3, judul: "Shalat Dhuha & Tahfiz Rutin Setiap Pagi", slug: "shalat-dhuha", kategori: "Keagamaan", isi: "Pembinaan karakter islami dilakukan melalui shalat dhuha berjamaah dan tahfiz setiap pagi.", gambar: null, tanggal: "2026-06-03", dilihat: 156 },
  { id: 4, judul: "Siswa Berprestasi Raih Beasiswa Penuh", slug: "beasiswa", kategori: "Beasiswa", isi: "Tiga siswa berhasil meraih beasiswa penuh bina lingkungan.", gambar: null, tanggal: "2026-04-20", dilihat: 134 },
];

export const fallbackTeachers: Teacher[] = [
  [1, "Budi Santoso, M.Pd.", "Kepala Sekolah", "Manajemen Pendidikan", "/assets/img/guru1.jpeg"],
  [2, "Siti Aminah, S.Pd.", "Guru Matematika", "Matematika", "/assets/img/guru2.jpeg"],
  [3, "Ahmad Fauzi, M.Pd.", "Guru Fisika", "Fisika", "/assets/img/guru3.jpeg"],
  [4, "Maya Kartika, S.Pd.", "Guru Bahasa Inggris", "Bahasa Inggris", "/assets/img/guru4.jpeg"],
].map(([id, nama, jabatan, mapel, foto], index) => ({ id: id as number, nama: nama as string, jabatan: jabatan as string, mapel: mapel as string, foto: foto as string, urutan: index + 1 }));

export const fallbackActivities: Activity[] = [
  [1, "Rohis & Tahfiz", "Keagamaan", "Kajian, tahfiz, dan pembinaan keislaman siswa.", "Ustadz Abdulloh", "Senin & Rabu 15.00"],
  [2, "Futsal", "Olahraga", "Latihan rutin dan turnamen antar-pelajar tingkat kota.", "Ahmad Fauzi, M.Pd.", "Senin & Rabu 15.30"],
  [3, "English Club", "Akademik & Sains", "Debat, percakapan, dan public speaking.", "Maya Kartika, S.Pd.", "Kamis 15.00"],
  [4, "Pramuka", "Organisasi & Kepanduan", "Pembinaan kemandirian, disiplin, dan kerja sama.", "Dedi Kurniawan", "Sabtu 08.00"],
].map(([id, nama, kategori, deskripsi, pembina, jadwal]) => ({ id: id as number, nama: nama as string, kategori: kategori as string, deskripsi: deskripsi as string, pembina: pembina as string, jadwal: jadwal as string }));

export const fallbackVision: VisionItem[] = [
  { id: 1, tipe: "visi", judul: null, isi: "Mewujudkan generasi yang cerdas, berkarakter, beriman, dan berdaya saing global.", urutan: 1 },
  { id: 2, tipe: "misi", judul: null, isi: "Menyelenggarakan pembelajaran aktif, kreatif, dan berkualitas berbasis Kurikulum Merdeka.", urutan: 1 },
  { id: 3, tipe: "misi", judul: null, isi: "Membina karakter islami melalui pembiasaan ibadah, tahfiz, dan keteladanan akhlak mulia.", urutan: 2 },
  { id: 4, tipe: "misi", judul: null, isi: "Mengembangkan potensi akademik dan non-akademik siswa secara optimal.", urutan: 3 },
  { id: 5, tipe: "nilai", judul: "Integritas", isi: "Menjunjung kejujuran dan tanggung jawab dalam segala hal.", urutan: 1 },
  { id: 6, tipe: "nilai", judul: "Peduli", isi: "Menumbuhkan empati terhadap sesama dan lingkungan.", urutan: 2 },
];
