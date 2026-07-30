-- Memindahkan isi bawaan halaman Tentang ke tabel yang bisa dikelola admin.
-- Isi di bawah ini sama persis dengan yang sebelumnya ditulis tetap di dalam
-- kode halaman, jadi tampilan situs tidak berubah setelah dijalankan.
--
-- Aman dijalankan berulang: kalau tabel sudah berisi data, seluruh blok
-- insert dilewati sehingga isi yang sudah kamu sunting tidak tertimpa.

insert into public.tentang (bagian, judul, isi)
select v.bagian, v.judul, v.isi
from (values
  ('sejarah',
   'Dari mimpi sederhana menjadi sekolah terpercaya.',
   '<p>Berdiri sejak tahun 2013, SMAS Putra Persada Batam terus bertumbuh menjadi sekolah yang mengintegrasikan keunggulan akademik dan karakter islami.</p>'),
  ('sambutan',
   'Assalamu''alaikum Warahmatullahi Wabarakatuh.',
   '<p>Kami percaya setiap anak memiliki potensi besar yang perlu dibimbing dengan ilmu, keteladanan, dan lingkungan belajar yang baik.</p>'),
  ('fasilitas', 'Ruang Kelas Modern', 'Ruang belajar nyaman dengan perangkat presentasi.'),
  ('fasilitas', 'Laboratorium IPA', 'Fasilitas praktikum Fisika, Kimia, dan Biologi.'),
  ('fasilitas', 'Laboratorium Komputer', 'Pembelajaran digital dengan koneksi internet.'),
  ('fasilitas', 'Perpustakaan', 'Koleksi literasi dan ruang baca yang nyaman.'),
  ('fasilitas', 'Mushola', 'Tempat ibadah dan pembinaan karakter islami.'),
  ('fasilitas', 'Lapangan Olahraga', 'Ruang kegiatan olahraga dan ekstrakurikuler.')
) as v(bagian, judul, isi)
where not exists (select 1 from public.tentang);

-- Angka pada bagian "Sekolah dalam angka" kini dibaca dari tabel pengaturan.
-- Baris berikut hanya mengisi kunci yang belum ada, tanpa menimpa isianmu.
insert into public.pengaturan (kunci, nilai) values
  ('tahun_berdiri', '2013'),
  ('peserta_didik', ''),
  ('label_peserta', 'Peserta Didik'),
  ('pengajar', ''),
  ('label_pengajar', 'Guru & Staf')
on conflict (kunci) do nothing;
