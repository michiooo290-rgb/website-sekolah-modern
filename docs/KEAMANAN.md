# Catatan keamanan

Berkas ini merangkum apa yang sudah dijaga di dalam kode, dan apa yang hanya
bisa dipastikan langsung di Dashboard Supabase. Kode yang benar tidak
membuktikan databasenya sudah dikonfigurasi benar.

## Yang wajib diperiksa sebelum publish

### 1. Pastikan Row Level Security benar-benar menyala

Jalankan di **Supabase > SQL Editor**:

```sql
select tablename, rowsecurity
from pg_tables
where schemaname = 'public'
order by rowsecurity, tablename;
```

Setiap baris dengan `rowsecurity = false` adalah tabel yang **terbuka penuh**
lewat REST API publik: bisa dibaca, diubah, dan dihapus siapa saja. Semua
harus bernilai `true`.

Periksa juga policy-nya benar-benar terpasang:

```sql
select tablename, count(*) as jumlah_policy
from pg_policies
where schemaname = 'public'
group by tablename
order by tablename;
```

Tabel yang muncul tanpa policy sama sekali, sementara RLS-nya menyala, akan
menolak semua akses termasuk milik admin.

### 2. Matikan pendaftaran mandiri

**Authentication > Sign In / Providers > Email**, matikan **Enable email
signups**. Trigger `handle_new_user` otomatis membuat profil untuk setiap
akun baru. Akun itu hanya berperan `viewer` dan tidak bisa mengubah apa pun,
tetapi tanpa dimatikan siapa saja tetap bisa mendaftar dan membanjiri tabel
`auth.users`. Admin sekolah dibuat manual lewat Dashboard.

### 3. Pastikan ketiga berkas SQL sudah dijalankan

- `supabase/schema.sql` - tabel inti, RLS, dan fungsi pesan kontak
- `supabase/login-rate-limit.sql` - pembatas percobaan login panel admin
- `supabase/agenda-faq-sosial.sql` - tabel agenda, FAQ, dan tautan sosial

Khusus pembatas login: `check_login_allowed` sengaja dibuat *fail-open*.
Kalau fungsinya belum terpasang, login tetap berjalan **tanpa batas
percobaan** dan tidak ada tanda apa pun bahwa proteksinya absen. Verifikasi:

```sql
select proname from pg_proc
where proname in ('check_login_allowed','record_login_attempt','is_admin','submit_contact_message');
```

Keempatnya harus muncul.

### 4. Jangan buat tabel lewat Table Editor tanpa RLS

Tabel baru yang dibuat lewat antarmuka Dashboard tidak otomatis memiliki RLS.
Setiap kali menambah tabel, jalankan `alter table ... enable row level
security;` beserta policy-nya, lalu ulangi pemeriksaan di poin 1.

## Yang sudah dijaga di dalam kode

- **Batas area admin**: `loadAdminSession()` memakai `supabase.auth.getUser()`
  yang memverifikasi token ke server Supabase, bukan sekadar membaca cookie.
  Dipanggil di setiap halaman admin, bukan hanya di layout.
- **Penulisan data**: seluruh server action memanggil `requireAdmin()` lebih
  dulu, dan RLS tetap menjadi lapisan terakhir di sisi database.
- **Mass assignment**: payload hanya disusun dari daftar kolom di
  `admin-resources.ts`, sehingga kolom seperti `id` atau `dilihat` tidak bisa
  ditimpa lewat form.
- **Unggahan berkas**: tipe berkas diperiksa lewat *magic bytes*, bukan
  ekstensi atau MIME dari browser. Nama berkas diganti `crypto.randomUUID()`.
- **XSS**: kolom `isi` disanitasi dua kali, saat disimpan dan saat ditampilkan.
  Allowlist saat menyimpan hanya `p, br, strong, b, em, i, ul, ol, li,
  blockquote, h2, h3, a`. Tag `img`, `h4`, `table`, dan `script` akan dibuang.
- **Brute force login**: dibatasi per pasangan email+IP (5 kegagalan / 15
  menit) dan per IP (15 kegagalan / 15 menit). Memakai pasangan email+IP,
  bukan email saja, supaya orang asing tidak bisa mengunci akun admin sah.
- **Spam formulir kontak**: honeypot, validasi Zod, lalu pembatas di database
  sebesar 3 pesan / 15 menit per email, 8 pesan / jam per IP, dan penolakan
  isi pesan yang sama dalam 1 jam.
- **Header keamanan**: CSP, HSTS preload, `frame-ancestors 'none'`,
  `object-src 'none'`, `form-action 'self'`, dan `nosniff` di `next.config.ts`.

## Diketahui, belum dikerjakan

- **CSP masih memakai `script-src 'unsafe-inline'`.** Menggantinya dengan
  nonce mengharuskan CSP dipindah ke `src/proxy.ts` dan membuat **seluruh
  halaman menjadi dinamis**, sehingga kehilangan pembuatan statis dan caching.
  Untuk situs sekolah yang isinya jarang berubah, biayanya lebih besar
  daripada manfaatnya selama `sanitize-html` masih menutup jalur injeksi.
  Kerjakan bila situs mulai menerima masukan pengguna yang lebih bebas.
- **Versi dependensi belum dikunci.** Lihat catatan di `README` dan `.npmrc`.
