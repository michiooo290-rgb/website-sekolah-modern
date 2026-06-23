-- ============================================================
-- SMA Putra Persada Batam — Database Schema
-- ============================================================

DROP DATABASE IF EXISTS sma_putra_persada;
CREATE DATABASE sma_putra_persada
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE sma_putra_persada;

-- ─────────────────────────────────────────────
-- 1. admin
-- ─────────────────────────────────────────────
CREATE TABLE admin (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  username      VARCHAR(50)  NOT NULL UNIQUE,
  password      VARCHAR(255) NOT NULL,
  nama          VARCHAR(100) NOT NULL,
  role          ENUM('superadmin','editor') NOT NULL DEFAULT 'editor'
) ENGINE=InnoDB;

-- default admin — jalankan admin/setup.php untuk membuat password
INSERT INTO admin (username, password, nama, role) VALUES
  ('admin', 'CHANGE_ME', 'Administrator', 'superadmin');

-- ─────────────────────────────────────────────
-- 2. pengaturan
-- ─────────────────────────────────────────────
CREATE TABLE pengaturan (
  id    INT AUTO_INCREMENT PRIMARY KEY,
  kunci VARCHAR(100) NOT NULL UNIQUE,
  nilai TEXT NOT NULL
) ENGINE=InnoDB;

INSERT INTO pengaturan (kunci, nilai) VALUES
  ('nama_sekolah',   'SMA Putra Persada Batam'),
  ('tagline',        'Unggul & Berakhlak'),
  ('akreditasi',     'B.'),
  ('label_akreditasi','Akreditasi'),
  ('peserta_didik',  '750+'),
  ('label_peserta',  'Peserta Didik'),
  ('pengajar',       '45+'),
  ('label_pengajar', 'Pengajar'),
  ('alamat',         'Jl. Raya Persada No. 123, Batam Center, Kota Batam, Kepulauan Riau 29461'),
  ('telepon',        '(0778) XXX-XXXX'),
  ('email',          'info@smaputrapersada.sch.id'),
  ('jam_operasional','Senin–Jumat, 07.00–15.00 WIB'),
  ('maps_embed',     'https://maps.google.com/maps?q=1.1707668,104.1001968&z=16&output=embed'),
  ('maps_lat',       '1.1707668'),
  ('maps_lng',       '104.1001968'),
  ('ig_url',         '#'),
  ('yt_url',         '#'),
  ('tiktok_url',     '#'),
  ('fb_url',         '#');

-- ─────────────────────────────────────────────
-- 3. berita
-- ─────────────────────────────────────────────
CREATE TABLE berita (
  id       INT AUTO_INCREMENT PRIMARY KEY,
  judul    VARCHAR(255) NOT NULL,
  slug     VARCHAR(255) NOT NULL UNIQUE,
  kategori VARCHAR(50)  NOT NULL,
  isi      LONGTEXT     NOT NULL,
  gambar   VARCHAR(255) DEFAULT NULL,
  tanggal  DATE         NOT NULL,
  dilihat  INT          NOT NULL DEFAULT 0
) ENGINE=InnoDB;

INSERT INTO berita (judul, slug, kategori, isi, tanggal, dilihat) VALUES
('Siswa Raih Emas Olimpiade Sains Provinsi Kepri', 'osn-emas', 'Prestasi',
'<p>Alhamdulillah, SMA Putra Persada Batam kembali meraih prestasi membanggakan.</p>',
'2026-06-12', 245),
('Pentas Seni & Perpisahan Kelas XII Berlangsung Meriah', 'pentas-seni', 'Kegiatan',
'<p>Suasana haru bercampur bahagia menyelimuti acara Pentas Seni dan Perpisahan Kelas XII.</p>',
'2026-06-08', 189),
('Shalat Dhuha & Tahfiz Rutin Setiap Pagi', 'shalat-dhuha', 'Keagamaan',
'<p>Sebagai bagian dari pembinaan karakter islami, SMA Putra Persada Batam melaksanakan program Shalat Dhuha berjamaah.</p>',
'2026-06-03', 156),
('Siswa Berprestasi Raih Beasiswa Penuh Bina Lingkungan', 'beasiswa', 'Beasiswa',
'<p>Tiga siswa SMA Putra Persada Batam berhasil meraih Beasiswa Penuh Bina Lingkungan.</p>',
'2026-04-20', 134),
('Study Tour ke Museum Nasional dan Taman Mini', 'study-tour', 'Kegiatan',
'<p>SMA Putra Persada Batam mengadakan study tour ke Jakarta bagi siswa kelas X dan XI.</p>',
'2026-03-15', 98),
('Aksi Penanaman 100 Pohon di Lingkungan Sekolah', 'penanaman-pohon', 'Lingkungan',
'<p>Dalam rangka memperingati Hari Lingkungan Hidup Sedunia, SMA Putra Persada Batam mengadakan aksi penanaman 100 pohon.</p>',
'2026-02-10', 87);

-- ─────────────────────────────────────────────
-- 4. guru
-- ─────────────────────────────────────────────
CREATE TABLE guru (
  id      INT AUTO_INCREMENT PRIMARY KEY,
  nama    VARCHAR(100) NOT NULL,
  jabatan VARCHAR(100) NOT NULL,
  mapel   VARCHAR(100) DEFAULT NULL,
  foto    VARCHAR(255) DEFAULT 'placeholder.jpeg',
  urutan  INT          NOT NULL DEFAULT 0
) ENGINE=InnoDB;

INSERT INTO guru (nama, jabatan, mapel, foto, urutan) VALUES
('Budi Santoso, M.Pd.',       'Kepala Sekolah',      'Manajemen Pendidikan', 'assets/img/guru1.jpeg', 1),
('Siti Aminah, S.Pd.',        'Guru Matematika',      'Matematika',          'assets/img/guru2.jpeg', 2),
('Ahmad Fauzi, M.Pd.',        'Guru Fisika',          'Fisika',              'assets/img/guru3.jpeg', 3),
('Maya Kartika, S.Pd.',       'Guru Bahasa Inggris',  'Bahasa Inggris',      'assets/img/guru4.jpeg', 4),
('Rina Wulandari, S.Pd.',     'Guru Kimia',           'Kimia',               'placeholder.jpeg', 5),
('Dedi Kurniawan, M.Pd.',     'Guru Biologi',         'Biologi',             'placeholder.jpeg', 6),
('Lestari Putri, S.E., M.Pd.','Guru Ekonomi',         'Ekonomi',             'placeholder.jpeg', 7),
('Hendra Gunawan, S.Pd.',     'Guru Bahasa Indonesia','Bahasa Indonesia',     'placeholder.jpeg', 8),
('Nurhayati, S.Ag., M.Pd.',   'Guru PAI',             'Pendidikan Agama Islam','placeholder.jpeg', 9);

-- ─────────────────────────────────────────────
-- 5. ekstrakurikuler
-- ─────────────────────────────────────────────
CREATE TABLE ekstrakurikuler (
  id        INT AUTO_INCREMENT PRIMARY KEY,
  nama      VARCHAR(100) NOT NULL,
  kategori  VARCHAR(50)  NOT NULL,
  deskripsi TEXT         NOT NULL,
  pembina   VARCHAR(100) DEFAULT NULL,
  jadwal    VARCHAR(100) DEFAULT NULL
) ENGINE=InnoDB;

INSERT INTO ekstrakurikuler (nama, kategori, deskripsi, pembina, jadwal) VALUES
('Rohis (Rohani Islam)',   'Keagamaan',       'Kajian, kegiatan dakwah, dan pembinaan keislaman siswa.',           'Ustadz Abdulloh',   'Senin & Rabu 15.00'),
('Tahfiz Al-Quran',       'Keagamaan',       'Program menghafal dan memperbaiki bacaan Al-Quran.',                'Ustadzah Fatimah',  'Selasa & Kamis 15.00'),
('Nasyid & Marawis',      'Keagamaan',       'Seni musik islami dan paduan suara religi.',                        'Nurhayati, S.Ag.',  'Jumat 14.00'),
('Futsal',                'Olahraga',        'Latihan rutin dan turnamen antar-pelajar tingkat kota.',            'Ahmad Fauzi, M.Pd.','Senin & Rabu 15.30'),
('Basket',                'Olahraga',        'Pembinaan tim putra dan putri menuju kompetisi daerah.',            'Dedi Kurniawan',    'Selasa & Kamis 15.30'),
('Bulu Tangkis',          'Olahraga',        'Latihan teknik dan sparring rutin setiap pekan.',                   'Hendra Gunawan',    'Rabu & Jumat 15.30'),
('Bela Diri (Pencak Silat)','Olahraga',      'Pembinaan fisik, mental, dan sportivitas.',                         'Pak Sukirman',      'Selasa & Kamis 15.30'),
('Paduan Suara',          'Seni & Budaya',   'Latihan vokal dan tampil pada acara sekolah.',                      'Maya Kartika, S.Pd.','Senin 15.00'),
('Tari Tradisional',      'Seni & Budaya',   'Melestarikan budaya melalui seni tari Nusantara.',                  'Ibu Ratna',         'Rabu 15.00'),
('Teater & Musik',        'Seni & Budaya',   'Pementasan drama dan band sekolah.',                                'Hendra Gunawan',    'Kamis 15.00'),
('Klub Olimpiade (KIR)',  'Akademik & Sains','Pembinaan menuju OSN dan kompetisi sains.',                         'Siti Aminah, S.Pd.','Selasa 15.00'),
('English Club',          'Akademik & Sains','Debat, percakapan, dan public speaking Bahasa Inggris.',             'Maya Kartika, S.Pd.','Kamis 15.00'),
('Jurnalistik',           'Akademik & Sains','Pengelolaan mading, buletin, dan media sekolah.',                    'Lestari Putri',     'Jumat 14.00'),
('OSIS',                  'Organisasi & Kepanduan','Organisasi Siswa Intra Sekolah sebagai wadah kepemimpinan.',  'Budi Santoso',      'Jumat 13.00'),
('Pramuka',               'Organisasi & Kepanduan','Pembinaan kemandirian, disiplin, dan kerja sama.',              'Dedi Kurniawan',    'Sabtu 08.00'),
('Paskibra',              'Organisasi & Kepanduan','Pasukan Pengibar Bendera dan latihan baris-berbaris.',          'Ahmad Fauzi',       'Rabu 15.00'),
('PMR (Palang Merah Remaja)','Organisasi & Kepanduan','Pelatihan pertolongan pertama dan kepedulian sosial.',        'Rina Wulandari',    'Kamis 14.00');

-- ─────────────────────────────────────────────
-- 6. visi_misi
-- ─────────────────────────────────────────────
CREATE TABLE visi_misi (
  id    INT AUTO_INCREMENT PRIMARY KEY,
  tipe  ENUM('visi','misi','tujuan','nilai') NOT NULL,
  judul VARCHAR(100) DEFAULT NULL,
  isi   TEXT         NOT NULL,
  urutan INT         NOT NULL DEFAULT 0
) ENGINE=InnoDB;

INSERT INTO visi_misi (tipe, judul, isi, urutan) VALUES
('visi',  NULL,                   'Mewujudkan generasi yang cerdas, berkarakter, beriman, dan berdaya saing global.', 1),
('misi',  NULL,                   'Menyelenggarakan pembelajaran aktif, kreatif, dan berkualitas berbasis Kurikulum Merdeka.', 1),
('misi',  NULL,                   'Membina karakter islami melalui pembiasaan ibadah, tahfiz, dan keteladanan akhlak mulia.', 2),
('misi',  NULL,                   'Mengembangkan potensi akademik dan non-akademik siswa secara optimal dan seimbang.', 3),
('misi',  NULL,                   'Mempersiapkan lulusan yang siap bersaing menuju perguruan tinggi favorit.', 4),
('misi',  NULL,                   'Menumbuhkan budaya disiplin, jujur, dan peduli terhadap sesama dan lingkungan.', 5),
('misi',  NULL,                   'Menjalin kerja sama yang harmonis antara sekolah, orang tua, dan masyarakat.', 6),
('tujuan','Lulusan Berkualitas',  'Menghasilkan lulusan yang unggul secara akademik dan berakhlak mulia.', 1),
('tujuan','Daya Saing Tinggi',   'Meningkatkan jumlah lulusan yang diterima di PTN dan PTS ternama.', 2),
('tujuan','Karakter Kuat',       'Membentuk pribadi yang religius, mandiri, dan bertanggung jawab.', 3),
('tujuan','Prestasi Berkelanjutan','Meraih prestasi akademik dan non-akademik tingkat daerah hingga nasional.', 4),
('nilai', 'Religius',            'Menjadikan nilai keimanan sebagai dasar setiap perilaku.', 1),
('nilai', 'Integritas',          'Menjunjung kejujuran dan tanggung jawab dalam segala hal.', 2),
('nilai', 'Disiplin',            'Membiasakan ketertiban dan komitmen pada aturan.', 3),
('nilai', 'Peduli',              'Menumbuhkan empati terhadap sesama dan lingkungan.', 4),
('nilai', 'Kreatif',             'Mendorong inovasi dan semangat belajar tanpa henti.', 5);

-- ─────────────────────────────────────────────
-- 7. tentang
-- ─────────────────────────────────────────────
CREATE TABLE tentang (
  id     INT AUTO_INCREMENT PRIMARY KEY,
  bagian VARCHAR(50) NOT NULL,
  judul  VARCHAR(200) DEFAULT NULL,
  isi    TEXT         NOT NULL
) ENGINE=InnoDB;

INSERT INTO tentang (bagian, judul, isi) VALUES
('sejarah', 'Dari mimpi sederhana hingga sekolah terpercaya.',
'<p>SMA Putra Persada Batam didirikan pada tahun 2001 dengan visi menjadi lembaga pendidikan menengah yang mengintegrasikan keunggulan akademik dan pembentukan karakter islami.</p>'),
('sambutan', 'Assalamu''alaikum Warahmatullahi Wabarakatuh.',
'<p>Puji syukur kami panjatkan ke hadirat Allah SWT atas segala rahmat dan karunia-Nya. SMA Putra Persada Batam hadir sebagai wadah pendidikan yang tidak hanya mengutamakan kecerdasan akademik, tetapi juga pembentukan karakter dan akhlak mulia.</p>'),
('fasilitas', 'Ruang Kelas Modern',
'Ruang kelas ber-AC dengan proyektor dan papan interaktif untuk pembelajaran yang nyaman.'),
('fasilitas', 'Laboratorium IPA',
'Lab Fisika, Kimia, dan Biologi dengan peralatan lengkap untuk praktikum siswa.'),
('fasilitas', 'Laboratorium Komputer',
'Lab komputer dengan spesifikasi terkini dan akses internet cepat untuk pembelajaran digital.'),
('fasilitas', 'Perpustakaan',
'Koleksi buku lengkap, ruang baca nyaman, dan akses e-book untuk literasi siswa.'),
('fasilitas', 'Musholla',
'Musholla luas untuk shalat berjamaah, tahfiz, dan kegiatan keagamaan siswa.'),
('fasilitas', 'Lapangan & Fasilitas Olahraga',
'Lapangan futsal, basket, dan area olahraga untuk pembinaan fisik dan mental siswa.');

-- ─────────────────────────────────────────────
-- 8. ppdb_info
-- ─────────────────────────────────────────────
CREATE TABLE ppdb_info (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  bagian        ENUM('syarat','jadwal','alur','lokasi','faq') NOT NULL,
  judul         VARCHAR(200) DEFAULT NULL,
  isi           TEXT         NOT NULL,
  tanggal       VARCHAR(50)  DEFAULT NULL,
  file_formulir VARCHAR(255) DEFAULT NULL,
  urutan        INT          NOT NULL DEFAULT 0
) ENGINE=InnoDB;

INSERT INTO ppdb_info (bagian, judul, isi, tanggal, urutan) VALUES
('syarat', 'Dokumen yang Diperlukan',
'<ul><li>Fotokopi rapor kelas 7, 8, dan 9 (semester 1–5) yang dilegalisir</li><li>Fotokopi akta kelahiran</li><li>Fotokopi kartu keluarga (KK)</li><li>Pas foto 3×4 sebanyak 4 lembar (latar biru)</li><li>Surat keterangan lulus dari SMP asal</li><li>Fotokopi NISN</li></ul>',
NULL, 1),
('syarat', 'Persyaratan Umum',
'<ul><li>Lulusan SMP/MTs/sederajat tahun 2025/2026 atau 2026/2027</li><li>Usia maksimal 18 tahun pada saat pendaftaran</li><li>Sehat jasmani dan rohani</li><li>Bersedia mematuhi tata tertib sekolah</li><li>Mengisi formulir pendaftaran yang disediakan sekolah</li><li>Membayar biaya pendaftaran sebesar Rp 250.000</li></ul>',
NULL, 2),
('jadwal', 'Pembukaan Pendaftaran',
'Pendaftaran jalur prestasi & reguler resmi dibuka.',
'15 Jun 2026', 1),
('jadwal', 'Tes Seleksi',
'Tes akademik dan wawancara calon siswa baru.',
'28 Jun 2026', 2),
('jadwal', 'Pengumuman',
'Pengumuman hasil seleksi dan daftar ulang siswa diterima.',
'10 Jul 2026', 3),
('jadwal', 'MPLS',
'Masa Pengenalan Lingkungan Sekolah bagi siswa baru.',
'15 Jul 2026', 4),
('jadwal', 'Hari Pertama Masuk Sekolah',
'Kegiatan belajar mengajar resmi dimulai.',
'21 Jul 2026', 5),
('alur', 'Datang ke Sekolah',
'Kunjungi SMA Putra Persada Batam pada hari dan jam kerja untuk mengambil formulir.',
NULL, 1),
('alur', 'Isi Formulir',
'Lengkapi formulir pendaftaran dan serahkan bersama dokumen persyaratan.',
NULL, 2),
('alur', 'Ikuti Tes',
'Hadiri tes seleksi akademik dan wawancara sesuai jadwal yang ditentukan.',
NULL, 3),
('alur', 'Daftar Ulang',
'Lakukan daftar ulang setelah dinyatakan diterima dan ikuti MPLS.',
NULL, 4),
('faq', 'Apakah bisa mendaftar secara online?',
'Saat ini pendaftaran dilakukan secara offline. Silakan datang langsung ke sekolah pada hari dan jam kerja untuk mengambil formulir dan mendaftar.',
NULL, 1),
('faq', 'Berapa biaya pendaftaran?',
'Biaya pendaftaran sebesar Rp 250.000,- dan dapat dibayarkan langsung di sekolah saat pendaftaran.',
NULL, 2),
('faq', 'Apakah ada jalur beasiswa?',
'Ya, tersedia jalur beasiswa bagi siswa berprestasi dengan ketentuan khusus. Silakan hubungi bagian PPDB untuk informasi lebih lanjut.',
NULL, 3),
('faq', 'Kapan batas akhir pendaftaran?',
'Pendaftaran dibuka hingga kuota terpenuhi. Disarankan mendaftar segera karena kuota terbatas.',
NULL, 4);

-- ─────────────────────────────────────────────
-- 9. pesan_kontak
-- ─────────────────────────────────────────────
CREATE TABLE pesan_kontak (
  id      INT AUTO_INCREMENT PRIMARY KEY,
  nama    VARCHAR(100) NOT NULL,
  email   VARCHAR(150) NOT NULL,
  telepon VARCHAR(30)  DEFAULT NULL,
  subjek  VARCHAR(100) DEFAULT NULL,
  pesan   TEXT         NOT NULL,
  tanggal DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  dibaca  TINYINT(1)   NOT NULL DEFAULT 0
) ENGINE=InnoDB;

-- ─────────────────────────────────────────────
-- 10. audit_log
-- ─────────────────────────────────────────────
CREATE TABLE audit_log (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  user_id    INT          DEFAULT NULL,
  action     VARCHAR(50)  NOT NULL,
  table_name VARCHAR(50)  DEFAULT NULL,
  record_id  INT          DEFAULT NULL,
  ip_address VARCHAR(45)  DEFAULT NULL,
  created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_user (user_id),
  INDEX idx_action (action),
  INDEX idx_created (created_at)
) ENGINE=InnoDB;
