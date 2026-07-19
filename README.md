# Website SMA Putra Persada — Next.js + Supabase

Versi modern website sekolah yang berjalan sebagai aplikasi **Next.js** di **Vercel**, dengan **Supabase PostgreSQL**, **Supabase Auth**, dan **Supabase Storage**.

## Menjalankan secara lokal

```bash
npm install
copy .env.example .env.local
npm run dev
```

Tanpa environment Supabase, halaman publik tetap menampilkan data demo agar desain dapat dipreview.

## Menyiapkan Supabase

1. Buat project baru di Supabase.
2. Buka **SQL Editor** dan jalankan seluruh isi `supabase/schema.sql`.
3. Buka **Authentication > Users** dan buat pengguna admin.
4. Jadikan pengguna tersebut admin melalui SQL Editor:

```sql
update public.profiles
set role = 'admin', nama = 'Administrator'
where id = 'UUID-PENGGUNA-DARI-AUTH';
```

5. Salin Project URL dan anon key ke `.env.local`.

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

`SUPABASE_SERVICE_ROLE_KEY` tidak diperlukan oleh aplikasi saat ini karena akses admin dilindungi Supabase Auth dan Row Level Security. Jika kelak digunakan, key tersebut hanya boleh berada di server.

## Fitur

- Halaman beranda, tentang, visi-misi, berita, ekstrakurikuler, PPDB, dan kontak.
- Supabase Auth untuk login admin.
- CRUD konten melalui `/admin`.
- Upload gambar/dokumen ke bucket Supabase Storage `media`.
- Row Level Security untuk pemisahan akses publik dan admin.
- Data fallback untuk preview sebelum Supabase tersambung.

Versi PHP lama tetap tersedia melalui riwayat Git sebelum branch migrasi ini.
