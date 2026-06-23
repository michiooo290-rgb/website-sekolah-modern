# SMA Putra Persada Batam — Website Sekolah Modern

Website sekolah modern dengan desain editorial hijau-krem-emas, dilengkapi panel admin untuk mengelola seluruh konten.

## Spesifikasi Teknis

| Komponen | Teknologi |
|----------|-----------|
| Backend | PHP 8.x + PDO |
| Database | MySQL / MariaDB |
| Frontend | Tailwind CSS v4 (browser CDN) |
| Font | Fraunces (serif) + Plus Jakarta Sans (sans) |
| Warna | Pine `#0E3B2E`, Cream `#F7F3E9`, Brass `#C9A227`, Leaf `#2F7D52` |

## Instalasi di XAMPP

### 1. Salin Project

Letakkan folder `website_sekolah_modern` ke dalam `C:\xampp\htdocs\` (XAMPP) atau direktori root Laragon.

### 2. Buat Database

Buka **phpMyAdmin** (`http://localhost/phpmyadmin`), lalu:

1. Klik tab **Import** (atau buat database baru bernama `sma_putra_persada` terlebih dahulu).
2. Pilih file `database.sql` dari folder project.
3. Klik **Go** / **Import** untuk menjalankan.

**Atau via terminal MySQL:**
```bash
mysql -u root < database.sql
```

### 3. Konfigurasi Koneksi Database

Salin file `.env.example` menjadi `.env` lalu sesuaikan nilainya:

```bash
cp .env.example .env
```

Edit file `.env`:

```env
DB_HOST=127.0.0.1
DB_NAME=sma_putra_persada
DB_USER=root
DB_PASS=
DB_CHARSET=utf8mb4
```

> **Laragon:** Biasanya tidak perlu diubah. Jika menggunakan Laragon dengan password MySQL, isi `DB_PASS`.

### 4. Jalankan Setup Admin

Buka di browser:

```
http://localhost/website_sekolah_modern/admin/setup.php
```

Script ini akan meng-hash ulang password admin default agar bisa digunakan untuk login.

> **Penting:** Hapus file `admin/setup.php` setelah selesai demi keamanan.

### 5. Buka Website

**Website publik:**
```
http://localhost/website_sekolah_modern/
```

**Panel admin:**
```
http://localhost/website_sekolah_modern/admin/login.php
```

## Login Admin

Buka `http://localhost/website_sekolah_modern/admin/login.php` setelah menjalankan setup di langkah 4.

> **Wajib ganti password** setelah login pertama kali untuk keamanan.

## Struktur Folder

```
website_sekolah_modern/
├── admin/                      ← Panel admin
│   ├── auth.php                ← Session guard, CSRF, helper
│   ├── login.php               ← Halaman login
│   ├── logout.php              ← Handler logout
│   ├── setup.php               ← Setup awal (HAPUS setelah pakai)
│   ├── dashboard.php           ← Dashboard statistik
│   ├── kelola_pengaturan.php   ← Edit pengaturan situs
│   ├── kelola_berita.php       ← CRUD berita + upload gambar
│   ├── kelola_guru.php         ← CRUD guru + upload foto
│   ├── kelola_ekskul.php       ← CRUD ekstrakurikuler
│   ├── kelola_visimisi.php     ← Edit visi, misi, tujuan, nilai
│   ├── kelola_tentang.php      ← Edit sejarah, sambutan, fasilitas
│   ├── kelola_ppdb.php         ← Edit PPDB + upload formulir PDF
│   ├── pesan_masuk.php         ← Lihat pesan kontak
│   ├── hapus.php               ← Handler hapus generik
│   ├── admin_head.php          ← Template sidebar + topbar
│   ├── admin_foot.php          ← Template footer + JS
│   └── uploads/                ← File upload
│       ├── berita/             ← Gambar berita
│       ├── guru/               ← Foto guru
│       └── ppdb/               ← Formulir PDF
├── config/
│   └── koneksi.php             ← Koneksi PDO + helper functions
├── includes/
│   ├── head.php                ← HTML head template publik
│   └── foot.php                ← HTML footer template publik
├── index.php                   ← Beranda
├── tentang.php                 ← Tentang kami
├── visi-misi.php               ← Visi & misi
├── ekstrakurikuler.php         ← Ekstrakurikuler
├── berita.php                  ← Daftar berita
├── berita-detail.php           ← Detail berita
├── ppdb.php                    ← Info PPDB
├── kontak.php                  ← Form kontak
├── shared.js                   ← Header/footer + animasi publik
├── styles.css                  ← Custom CSS (animasi, transisi)
├── database.sql                ← Schema + data awal
├── .env.example                ← Template konfigurasi database
├── .gitignore                  ← Git ignore rules
├── logo.jpeg                   ← Logo sekolah
└── guru[1-4].jpeg              ← Foto guru
```

## Fitur Admin

| Halaman | Fitur |
|---------|-------|
| Dashboard | Kartu statistik (berita, guru, ekskul, pesan), berita & pesan terbaru |
| Pengaturan | Edit akreditasi, peserta didik, pengajar, alamat, telepon, email, jam operasional, sosial media, teks sambutan |
| Berita | CRUD penuh + upload gambar + kategori + tanggal |
| Guru & Staf | CRUD penuh + upload foto (maks 9 entri: 1 Kepsek + 8 guru) |
| Ekstrakurikuler | CRUD penuh + kategori + deskripsi + pembina + jadwal |
| Visi & Misi | Edit visi + tambah/hapus misi, tujuan, nilai |
| Tentang | Edit sejarah, sambutan, fasilitas |
| PPDB | Edit syarat/jadwal/alur/FAQ + upload formulir PDF |
| Pesan Masuk | Baca pesan kontak + tandai sudah dibaca + hapus |

## Keamanan

- Kredensial database disimpan di `.env` (tidak di-commit ke Git)
- Semua halaman admin dilindungi session login
- Password di-hash dengan `password_hash()` / `password_verify()` (bcrypt)
- Semua form dilindungi CSRF token
- Semua query menggunakan prepared statement (PDO)
- Upload divalidasi tipe file & ukuran (maks 5 MB)
- Folder uploads dilindungi `.htaccess` dari eksekusi PHP
- Proteksi brute force login (maks 5 percobaan per 15 menit)

## License

© 2026 SMA Putra Persada Batam. Seluruh hak cipta dilindungi.
