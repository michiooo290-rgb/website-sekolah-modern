-- =====================================================================
-- Visi, Misi, dan Tujuan SMAS Putra Persada Batam
-- Sumber: papan "Profil dan Visi Misi" yang terpasang di sekolah.
--
-- Cara pakai: buka Supabase > SQL Editor > New query, tempel seluruh isi
-- berkas ini, lalu tekan Run. Baris visi/misi/tujuan yang lama akan
-- diganti. Data bertipe 'nilai' tidak disentuh.
-- =====================================================================

begin;

delete from public.visi_misi where tipe in ('visi', 'misi', 'tujuan');

-- VISI ----------------------------------------------------------------
insert into public.visi_misi (tipe, judul, isi, urutan) values
  ('visi', 'Visi Sekolah', 'Menciptakan Insan Berprestasi, Berbudaya dan Bertaqwa', 1);

-- MISI ----------------------------------------------------------------
insert into public.visi_misi (tipe, judul, isi, urutan) values
  ('misi', null, 'Menjalankan nilai-nilai agama dan berperilaku akhlakul karimah dalam kehidupan sehari-hari', 1),
  ('misi', null, 'Melaksanakan pembelajaran aktif, kreatif, efektif, dan menyenangkan untuk mengembangkan potensi keilmuan peserta didik', 2),
  ('misi', null, 'Menumbuhkan semangat berprestasi kepada seluruh warga sekolah', 3),
  ('misi', null, 'Membimbing dan mengembangkan bakat dan minat peserta didik', 4),
  ('misi', null, 'Melaksanakan program ekstrakurikuler untuk menghasilkan peserta didik yang berprestasi dan bermanfaat dalam kehidupan sehari-hari', 5),
  ('misi', null, 'Menerapkan manajemen berbasis sekolah yang partisipatif dengan melibatkan seluruh warga sekolah', 6),
  ('misi', null, 'Meningkatkan kesadaran untuk memelihara lingkungan sekolah', 7);

-- TUJUAN --------------------------------------------------------------
-- Judul dipakai sebagai kepala kartu di halaman publik, isi sebagai uraian.
insert into public.visi_misi (tipe, judul, isi, urutan) values
  ('tujuan', 'Siap ke Jenjang Lebih Tinggi', 'Meningkatkan pengetahuan peserta didik untuk melanjutkan pendidikan pada jenjang yang lebih tinggi dan mengembangkan diri sejalan dengan perkembangan ilmu pengetahuan, teknologi, dan seni budaya.', 1),
  ('tujuan', 'Bekal Akademik yang Memadai', 'Memberikan bekal yang memadai kepada peserta didik yang akan melanjutkan pendidikan pada jenjang yang lebih tinggi.', 2),
  ('tujuan', 'Kecakapan Hidup', 'Memberikan pengetahuan life skill kepada peserta didik yang tidak dapat langsung melanjutkan pendidikan pada jenjang yang lebih tinggi.', 3),
  ('tujuan', 'Peduli Lingkungan Hidup', 'Meningkatkan pengetahuan dan kesadaran warga sekolah dalam upaya pelestarian lingkungan hidup dan pembangunan yang berkelanjutan berdasarkan norma dasar kehidupan yang meliputi kebersamaan, keterbukaan, kejujuran, keadilan, dan kelestarian lingkungan hidup dan sumber daya alam.', 4),
  ('tujuan', 'Budaya Literasi & Kreativitas', 'Memberikan ruang seluas-luasnya tumbuhnya kreativitas peserta didik melalui pembiasaan gerakan literasi sekolah pada awal tatap muka setiap harinya.', 5);

commit;

-- Sesudah dijalankan, periksa hasilnya:
-- select tipe, urutan, judul, isi from public.visi_misi order by tipe, urutan;
