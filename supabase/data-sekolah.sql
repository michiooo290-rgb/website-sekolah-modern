-- =====================================================================
-- Identitas SMAS Putra Persada Batam
-- Sumber: papan "Profil dan Visi Misi" yang terpasang di sekolah.
--
-- Cara pakai: buka Supabase > SQL Editor > New query, tempel isi berkas
-- ini, lalu tekan Run. Nilai lama akan ditimpa, kunci yang belum ada akan
-- dibuat. Semua nilai ini juga bisa diubah sewaktu-waktu lewat halaman
-- /admin/pengaturan tanpa menyentuh SQL lagi.
-- =====================================================================

insert into public.pengaturan (kunci, nilai) values
  ('nama_sekolah', 'SMAS Putra Persada Batam'),
  ('alamat', 'Jl. H. Muhammad No. 80, RT 002 / RW 008, Kecamatan Nongsa, Kota Batam, Kepulauan Riau 29466'),
  ('telepon', '0822-8344-9944'),
  ('email', 'smasputrapersada@gmail.com'),
  ('tahun_berdiri', '2013'),
  ('yayasan', 'Yayasan Miftahul Hasanah')
on conflict (kunci) do update set nilai = excluded.nilai;

-- Periksa hasilnya:
-- select kunci, nilai from public.pengaturan
-- where kunci in ('nama_sekolah','alamat','telepon','email','tahun_berdiri','yayasan');
