# Panduan: Tailwind CSS Versi Produksi

Saat ini website memuat Tailwind lewat CDN yang meng-*compile* CSS langsung di
browser setiap halaman dibuka. Ini praktis untuk uji coba, tapi untuk website
yang sudah "live" ada 2 kelemahan:

1. **Lebih lambat** — browser harus membangun CSS dulu tiap kali halaman dibuka.
2. **Kedip tanpa style (FOUC)** — halaman sempat tampil polos sepersekian detik.

Solusinya: buat satu file CSS jadi (sudah di-*compile*), lalu panggil file itu.

## Langkah-langkah (cukup sekali setup)

### 1. Install Node.js
Unduh dari <https://nodejs.org> (pilih versi **LTS**). Cek berhasil:

```bash
node -v
npm -v
```

### 2. Siapkan Tailwind di folder proyek
Buka terminal di folder website, lalu:

```bash
npm init -y
npm install tailwindcss @tailwindcss/cli
```

### 3. Buat file sumber CSS: `assets/css/input.css`

```css
@import "tailwindcss";

@custom-variant dark (&:where(.dark, .dark *));

@theme {
  --color-pine: #0E3B2E;
  --color-pine-deep: #08291F;
  --color-leaf: #2F7D52;
  --color-cream: #F7F3E9;
  --color-cream-deep: #EFE8D6;
  --color-brass: #C9A227;
  --color-brass-light: #E0BC45;
  --font-serif: 'Fraunces', Georgia, serif;
  --font-sans: 'Plus Jakarta Sans', system-ui, sans-serif;
}
```

### 4. Build CSS jadi

```bash
npx @tailwindcss/cli -i ./assets/css/input.css -o ./assets/css/style.css --minify
```

Tambahkan `--watch` saat sedang mengembangkan supaya otomatis rebuild.

### 5. Ganti pemanggilan Tailwind di HTML
Di `admin/admin_head.php` (dan file publik yang serupa), ganti bagian:

```html
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4.3.0/..."></script>
<style type="text/tailwindcss"> ... @theme ... </style>
```

menjadi cukup satu baris:

```html
<link rel="stylesheet" href="../assets/css/style.css">
```

(Blok `@theme` sudah pindah ke `input.css`, jadi tidak perlu lagi di HTML.)

### 6. Ulangi build tiap menambah/mengubah class
Setiap kali menambah atau mengubah class Tailwind di file PHP/HTML, jalankan
lagi perintah build di Langkah 4 agar `style.css` ikut ter-update.

## Catatan

- Simpan `input.css` di Git. Kamu juga boleh meng-commit hasil build `style.css`
  supaya server tinggal pakai tanpa perlu Node.js.
- Mode gelap tetap berfungsi karena memakai `@custom-variant dark` yang sama
  seperti sebelumnya.
- Sisakan CDN Tailwind hanya untuk lingkungan pengembangan bila perlu, tetapi
  gunakan `style.css` hasil build di server produksi.
