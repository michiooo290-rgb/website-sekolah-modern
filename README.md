# Website SMA Putra Persada — Next.js + Supabase

Versi modern website sekolah yang berjalan sebagai aplikasi **Next.js** di **Vercel**, dengan **Supabase PostgreSQL**, **Supabase Auth**, dan **Supabase Storage**.

## Menjalankan secara lokal

```bash
npm install
copy .env.example .env
npm run dev
```

Tanpa environment Supabase, halaman publik tetap menampilkan data demo agar desain dapat dipreview.

Sebelum melakukan push, pastikan build bersih:

```bash
npm run build
```

## Menyiapkan Supabase

1. Buat project baru di Supabase.
2. Buka **SQL Editor** dan jalankan seluruh isi `supabase/schema.sql`.
3. Jalankan juga `supabase/login-rate-limit.sql` untuk mengaktifkan pembatas percobaan login.
4. Buka **Authentication > Users** dan buat pengguna admin.
5. Jadikan pengguna tersebut admin melalui SQL Editor:

```sql
update public.profiles
set role = 'admin', nama = 'Administrator'
where id = 'UUID-PENGGUNA-DARI-AUTH';
```

Pengguna baru otomatis dibuat dengan role `viewer`, jadi langkah ini wajib. Role `viewer` akan ditolak saat mencoba masuk ke `/admin`.

6. Matikan **Allow new users to sign up** di **Authentication > Sign In / Providers** agar tidak ada yang bisa mendaftar sendiri.
7. Salin Project URL dan anon key ke `.env`.

## Deploy ke Vercel

1. Import repository GitHub ke Vercel.
2. Framework akan terdeteksi sebagai Next.js.
3. Tambahkan environment variables:

```env
NEXT_PUBLIC_SUPABASE_URL=...
NEXT_PUBLIC_SUPABASE_ANON_KEY=...
NEXT_PUBLIC_SITE_URL=https://domain-anda.com
```

4. Deploy. Setiap push berikutnya ke branch produksi akan otomatis dideploy ulang.

Branch mana yang dianggap produksi diatur di **Settings > Environments > Production > Branch Tracking**, bukan di Settings > Git.

`SUPABASE_SERVICE_ROLE_KEY` tidak diperlukan oleh aplikasi saat ini karena akses admin dilindungi Supabase Auth dan Row Level Security. Jika kelak digunakan, key tersebut hanya boleh berada di server dan tidak boleh diberi awalan `NEXT_PUBLIC_`.

## Fitur

- Halaman beranda, tentang, visi-misi, berita, ekstrakurikuler, PPDB, dan kontak.
- Supabase Auth untuk login admin.
- CRUD konten melalui `/admin`.
- Upload gambar/dokumen ke bucket Supabase Storage `media`.
- Row Level Security untuk pemisahan akses publik dan admin.
- Halaman `/admin/aktivitas` untuk memantau percobaan login dan perubahan data.
- Data fallback untuk preview sebelum Supabase tersambung.

## Keamanan

- **Security header** diatur di `next.config.ts`: Content Security Policy, HSTS, `X-Frame-Options: DENY`, dan Cross-Origin-Opener-Policy.
- **Pembatas login** lewat `supabase/login-rate-limit.sql`: maksimal 5 percobaan gagal per pasangan email+IP dan 15 per IP dalam 15 menit. Dirancang *fail-open* agar gangguan database tidak memblokir login yang sah.
- **Audit log** merekam setiap penyimpanan dan penghapusan konten beserta alamat IP pada tabel `audit_log`.
- **Validasi upload** memeriksa magic bytes berkas, bukan hanya ekstensi. Batas 5 MB untuk gambar dan 10 MB untuk PDF.
- **Sanitasi HTML** dijalankan di sisi server dengan daftar tag yang diizinkan.
- Aktifkan **2FA** pada akun GitHub, Vercel, dan Supabase, lalu simpan recovery code di tempat terpisah dari ponsel.

## Catatan migrasi

Website ini semula ditulis dengan PHP dan MySQL. Seluruh berkas PHP, folder `assets/` di root, `includes/`, `admin/`, dan `.htaccess` sudah dihapus dari branch ini. Versi lama tetap dapat dilihat melalui riwayat Git.

Aset statis yang aktif berada di `public/assets/`. Jangan menghapus isinya, karena navbar dan footer situs publik dibangun saat runtime oleh `public/assets/js/shared.js`.
