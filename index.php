<?php
/**
 * index.php — Beranda (Homepage)
 * Data dari tabel: pengaturan, guru, berita
 */
require_once __DIR__ . '/config/koneksi.php';

$pdo = db();

// Stats from pengaturan
$stats = [];
foreach (['akreditasi','peserta_didik','pengajar'] as $k) {
    $stats[] = [
        'nilai' => setting($k),
        'label' => setting('label_' . ($k === 'akreditasi' ? 'akreditasi' : ($k === 'peserta_didik' ? 'peserta' : 'pengajar'))),
    ];
}

// Guru (9 orang, urutan)
$guruList = $pdo->query('SELECT nama, jabatan, foto FROM guru ORDER BY urutan ASC')->fetchAll();

// Berita terbaru (4)
$beritaList = $pdo->query('SELECT judul, slug, kategori, isi, tanggal, dilihat FROM berita ORDER BY tanggal DESC LIMIT 4')->fetchAll();

$namaSekolah = setting('nama_sekolah') ?? 'SMA Putra Persada Batam';
$pageTitle = $namaSekolah;
include __DIR__ . '/includes/head.php';
?>

<!-- HERO -->
<section id="beranda" class="relative z-10 max-w-6xl mx-auto px-5 pt-12 sm:pt-16 pb-10">
  <div class="grid lg:grid-cols-12 gap-10 items-center">
    <div class="lg:col-span-7 reveal show">
      <p class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest text-leaf dark:text-brass-light uppercase mb-5"><span class="h-px w-8 bg-brass"></span> Sekolah Menengah Atas</p>
      <h1 class="font-serif font-semibold text-pine dark:text-cream leading-[1.05] text-[40px] sm:text-6xl lg:text-[68px]"><span class="blur-fade">Menumbuhkan</span><br><span class="blur-fade" style="transition-delay:.12s"><span class="italic text-leaf dark:text-brass-light">ilmu</span> & <span class="italic text-leaf dark:text-brass-light">akhlak</span></span><br><span class="blur-fade" style="transition-delay:.24s">yang berbuah.</span></h1>
      <p class="mt-6 text-pine/70 dark:text-cream/70 max-w-md text-[15px] leading-relaxed"><?php echo esc($namaSekolah); ?> — ruang tumbuh bagi generasi cerdas dan berkarakter islami, siap melangkah ke perguruan tinggi terbaik.</p>
      <div class="mt-8 flex flex-wrap items-center gap-4">
        <a href="ppdb.php" class="bg-brass text-pine-deep font-semibold px-7 py-3.5 rounded-full hover:bg-brass-light transition">Pendaftaran 2026/2027</a>
        <a href="tentang.php" class="elink font-semibold text-pine dark:text-cream">Pelajari lebih lanjut →</a>
      </div>
    </div>
    <div class="lg:col-span-5 reveal show">
      <div class="relative mx-auto max-w-sm">
        <div class="absolute -top-6 -left-6 w-24 h-24 rounded-full bg-brass/30 blur-2xl"></div>
        <div class="absolute -bottom-8 -right-4 w-32 h-32 rounded-full bg-leaf/30 blur-2xl"></div>
        <div class="rounded-[2.5rem] overflow-hidden bg-gradient-to-b from-leaf to-pine p-12 flex items-center justify-center aspect-square shadow-2xl ring-1 ring-pine/10">
          <img src="assets/img/logo.jpeg" alt="Logo <?php echo esc($namaSekolah); ?>" class="w-48 h-48 object-contain drop-shadow-2xl">
        </div>
      </div>
    </div>
  </div>
</section>

<!-- STATS -->
<section class="relative z-10 border-y border-pine/10 dark:border-cream/10">
  <div class="max-w-6xl mx-auto px-5 grid grid-cols-2 md:grid-cols-3 divide-x divide-pine/10 dark:divide-cream/10">
    <?php foreach ($stats as $s): ?>
    <div class="py-7 text-center"><p class="font-serif text-4xl text-pine dark:text-brass-light" data-counter="<?php echo esc($s['nilai']); ?>"><?php echo esc($s['nilai']); ?></p><p class="text-xs tracking-wide text-pine/60 dark:text-cream/60 mt-1 uppercase"><?php echo esc($s['label']); ?></p></div>
    <?php endforeach; ?>
  </div>
</section>

<!-- TENTANG -->
<section id="tentang" class="relative z-10 max-w-6xl mx-auto px-5 py-20 sm:py-28">
  <div class="grid lg:grid-cols-2 gap-14 items-center">
    <div class="reveal">
      <div class="rounded-[2rem] bg-gradient-to-br from-pine to-pine-deep text-cream p-10 shadow-xl">
        <p class="font-serif text-6xl text-brass-light leading-none mb-2">"</p>
        <blockquote class="font-serif text-2xl sm:text-3xl leading-snug">Mendidik bukan sekadar mengisi gelas, tetapi menyalakan api semangat.</blockquote>
        <div class="mt-10 grid grid-cols-2 gap-6 border-t border-cream/15 pt-6">
          <div><p class="font-serif text-3xl text-brass-light">25+</p><p class="text-xs text-cream/60 mt-1">tahun mendidik</p></div>
          <div><p class="font-serif text-3xl text-brass-light"><?php echo esc(setting('peserta_didik') ?? '11+'); ?></p><p class="text-xs text-cream/60 mt-1">peserta didik</p></div>
        </div>
      </div>
    </div>
    <div class="reveal">
      <p class="text-xs font-semibold tracking-widest text-leaf dark:text-brass-light uppercase mb-4">Tentang Kami</p>
      <h2 class="font-serif text-3xl sm:text-4xl text-pine dark:text-cream leading-tight mb-5">Sekolah yang menyeimbangkan prestasi dan iman.</h2>
      <p class="text-pine/70 dark:text-cream/70 leading-relaxed mb-4"><?php echo esc($namaSekolah); ?> hadir untuk membentuk generasi yang cerdas secara akademik, kuat dalam karakter, dan teguh dalam keimanan. Setiap siswa kami dampingi untuk menemukan potensi terbaiknya.</p>
      <p class="text-pine/70 dark:text-cream/70 leading-relaxed mb-6">Dengan guru profesional dan lingkungan belajar yang mendukung, kami berkomitmen mengantar setiap siswa menuju masa depan terbaiknya.</p>
      <div class="grid grid-cols-2 gap-x-6 gap-y-3 text-sm">
        <?php foreach(['Visi global, akar islami','Guru profesional','Fasilitas lengkap','Lingkungan aman'] as $t): ?>
        <p class="flex items-center gap-2 text-pine/80 dark:text-cream/80"><span class="text-brass">✦</span> <?php echo $t; ?></p>
        <?php endforeach; ?>
      </div>
      <a href="visi-misi.php" class="inline-block mt-6 elink font-semibold text-sm text-leaf dark:text-brass-light">Lihat Visi & Misi lengkap →</a>
    </div>
  </div>
</section>

<!-- KEUNGGULAN -->
<section class="relative z-10 bg-pine text-cream">
  <div class="max-w-6xl mx-auto px-5 py-20 sm:py-28">
    <div class="reveal max-w-xl mb-14">
      <p class="text-xs font-semibold tracking-widest text-brass-light uppercase mb-4">Mengapa Kami</p>
      <h2 class="font-serif text-3xl sm:text-4xl leading-tight">Empat alasan keluarga mempercayakan pendidikan di sini.</h2>
    </div>
    <div class="grid sm:grid-cols-2 gap-px bg-cream/10 rounded-2xl overflow-hidden reveal">
      <div class="bg-pine p-8 hover:bg-pine-deep transition group"><p class="numdot text-brass-light text-3xl mb-4">01</p><h3 class="font-serif text-xl mb-2">Akademik Unggul</h3><p class="text-cream/70 text-sm leading-relaxed">Kurikulum Merdeka dengan bimbingan intensif persiapan UTBK/SNBT menuju PTN favorit.</p></div>
      <div class="bg-pine p-8 hover:bg-pine-deep transition group"><p class="numdot text-brass-light text-3xl mb-4">02</p><h3 class="font-serif text-xl mb-2">Karakter Islami</h3><p class="text-cream/70 text-sm leading-relaxed">Pembiasaan shalat dhuha, tahfiz, dan pembinaan akhlak mulia sebagai napas keseharian.</p></div>
      <div class="bg-pine p-8 hover:bg-pine-deep transition group"><p class="numdot text-brass-light text-3xl mb-4">03</p><h3 class="font-serif text-xl mb-2">Bakat &amp; Prestasi</h3><p class="text-cream/70 text-sm leading-relaxed">25+ ekstrakurikuler serta pembinaan olimpiade, seni, dan olahraga tingkat provinsi.</p></div>
      <div class="bg-pine p-8 hover:bg-pine-deep transition group"><p class="numdot text-brass-light text-3xl mb-4">04</p><h3 class="font-serif text-xl mb-2">Lingkungan Nyaman</h3><p class="text-cream/70 text-sm leading-relaxed">Fasilitas modern, guru pendamping, dan suasana belajar yang aman dan menyenangkan.</p></div>
    </div>
  </div>
</section>

<!-- PROGRAM -->
<section id="program" class="relative z-10 max-w-6xl mx-auto px-5 py-20 sm:py-28">
  <div class="reveal text-center max-w-2xl mx-auto mb-16">
    <p class="text-xs font-semibold tracking-widest text-leaf dark:text-brass-light uppercase mb-4">Program Akademik</p>
    <h2 class="font-serif text-3xl sm:text-4xl text-pine dark:text-cream">Peminatan unggulan, satu tujuan: masa depanmu.</h2>
  </div>
  <div class="grid md:grid-cols-1 max-w-xl mx-auto gap-8 reveal">
    <div class="group relative text-center"><p class="font-serif text-7xl text-brass/25 dark:text-brass/40 leading-none mb-2">01</p><h3 class="font-serif text-2xl text-pine dark:text-cream mb-3">Peminatan IPS</h3><p class="text-pine/70 dark:text-cream/70 text-sm leading-relaxed mb-4">Ekonomi · Geografi · Sosiologi · Sejarah — jalur hukum, bisnis, dan sosial.</p><div class="mt-6 h-px bg-pine/10 dark:bg-cream/10 group-hover:bg-brass transition"></div></div>
  </div>
  <p class="text-center mt-12 reveal"><a href="ekstrakurikuler.php" class="elink text-sm font-semibold text-leaf dark:text-brass-light">Lihat juga kegiatan ekstrakurikuler →</a></p>
</section>

<!-- GURU — Auto Slider -->
<style>
  @keyframes guru-scroll { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
  .guru-outer { overflow:hidden; }
  .guru-track { display:flex; gap:1.5rem; width:max-content; animation:guru-scroll 25s linear infinite; }
  .guru-track:hover { animation-play-state:paused; }
  .guru-slide { flex:0 0 auto; width:200px; }
  .guru-slide img { width:100%; aspect-ratio:3/4; object-fit:cover; object-position:top; border-radius:1rem; }
  .guru-fade { mask:linear-gradient(90deg,transparent 0%,black 8%,black 92%,transparent 100%); -webkit-mask:linear-gradient(90deg,transparent 0%,black 8%,black 92%,transparent 100%); }
  @media (min-width:640px) { .guru-slide { width:240px; } }
</style>
<section id="guru" class="relative z-10 bg-pine-deep text-cream overflow-hidden">
  <div class="max-w-6xl mx-auto px-5 pt-10 sm:pt-14 pb-6">
    <div class="reveal text-center max-w-2xl mx-auto mb-10">
      <p class="text-xs font-semibold tracking-widest text-brass-light uppercase mb-3">Tenaga Pendidik</p>
      <h2 class="font-serif text-3xl sm:text-4xl">Guru &amp; Pengajar Kami</h2>
      <p class="text-cream/70 mt-4 text-sm">Dibina oleh pendidik profesional yang berdedikasi membersamai setiap siswa.</p>
    </div>
  </div>
  <div class="guru-outer guru-fade reveal pb-20 sm:pb-28">
    <div class="guru-track">
      <?php
      // Duplikat array untuk infinite loop seamless
      $guruDouble = array_merge($guruList, $guruList);
      foreach ($guruDouble as $g): ?>
      <figure class="guru-slide group text-center">
        <div class="relative overflow-hidden ring-1 ring-cream/10 mb-3 bg-pine rounded-2xl">
          <img src="<?php echo esc($g['foto']); ?>" alt="<?php echo esc($g['nama']); ?>" loading="lazy" class="group-hover:scale-105 group-hover:brightness-110 transition duration-500">
        </div>
        <figcaption>
          <p class="font-serif text-sm text-cream leading-snug"><?php echo esc($g['nama']); ?></p>
          <p class="text-[11px] text-brass-light tracking-wide mt-0.5"><?php echo esc($g['jabatan']); ?></p>
        </figcaption>
      </figure>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- AGENDA -->
<section id="agenda" class="relative z-10 bg-cream-deep dark:bg-pine">
  <div class="max-w-6xl mx-auto px-5 py-20 sm:py-28 grid lg:grid-cols-3 gap-12">
    <div class="reveal">
      <p class="text-xs font-semibold tracking-widest text-leaf dark:text-brass-light uppercase mb-4">Agenda</p>
      <h2 class="font-serif text-3xl sm:text-4xl text-pine dark:text-cream leading-tight">Jadwal penting penerimaan siswa baru.</h2>
      <p class="text-pine/70 dark:text-cream/70 mt-4 text-sm">Catat tanggalnya dan jangan sampai terlewat.</p>
    </div>
    <div class="lg:col-span-2 reveal">
      <div class="relative pl-8 border-l-2 border-pine/15 dark:border-cream/15 space-y-8">
        <div class="relative"><span class="absolute -left-[41px] top-1 w-4 h-4 rounded-full bg-brass ring-4 ring-cream-deep dark:ring-pine"></span><p class="font-serif text-brass dark:text-brass-light text-lg">15 Jun 2026</p><h3 class="font-semibold text-pine dark:text-cream mt-0.5">Pembukaan PPDB 2026/2027</h3><p class="text-sm text-pine/60 dark:text-cream/60">Pendaftaran jalur prestasi &amp; reguler resmi dibuka.</p></div>
        <div class="relative"><span class="absolute -left-[41px] top-1 w-4 h-4 rounded-full bg-brass ring-4 ring-cream-deep dark:ring-pine"></span><p class="font-serif text-brass dark:text-brass-light text-lg">28 Jun 2026</p><h3 class="font-semibold text-pine dark:text-cream mt-0.5">Tes Seleksi Calon Siswa</h3><p class="text-sm text-pine/60 dark:text-cream/60">Tes akademik dan wawancara peserta didik baru.</p></div>
        <div class="relative"><span class="absolute -left-[41px] top-1 w-4 h-4 rounded-full bg-brass ring-4 ring-cream-deep dark:ring-pine"></span><p class="font-serif text-brass dark:text-brass-light text-lg">10 Jul 2026</p><h3 class="font-semibold text-pine dark:text-cream mt-0.5">Pengumuman Hasil Seleksi</h3><p class="text-sm text-pine/60 dark:text-cream/60">Pengumuman &amp; daftar ulang siswa yang diterima.</p></div>
        <div class="relative"><span class="absolute -left-[41px] top-1 w-4 h-4 rounded-full bg-brass ring-4 ring-cream-deep dark:ring-pine"></span><p class="font-serif text-brass dark:text-brass-light text-lg">15 Jul 2026</p><h3 class="font-semibold text-pine dark:text-cream mt-0.5">Masa Pengenalan Sekolah</h3><p class="text-sm text-pine/60 dark:text-cream/60">MPLS bagi siswa baru tahun ajaran 2026/2027.</p></div>
      </div>
    </div>
  </div>
</section>

<!-- BERITA -->
<section id="kabar" class="relative z-10 max-w-6xl mx-auto px-5 py-20 sm:py-28">
  <div class="reveal flex items-end justify-between mb-12">
    <div><p class="text-xs font-semibold tracking-widest text-leaf dark:text-brass-light uppercase mb-3">Kabar Sekolah</p><h2 class="font-serif text-3xl sm:text-4xl text-pine dark:text-cream">Berita &amp; Kegiatan Terbaru</h2></div>
    <a href="berita.php" class="hidden sm:inline elink font-semibold text-sm">Semua kabar →</a>
  </div>
  <?php if (!empty($beritaList)): ?>
  <?php $first = $beritaList[0]; ?>
  <div class="grid lg:grid-cols-2 gap-10 reveal">
    <a href="berita-detail.php?slug=<?php echo esc($first['slug']); ?>" class="group block bg-cream-deep dark:bg-pine rounded-[1.5rem] p-8 ring-1 ring-pine/10 dark:ring-cream/10">
      <span class="inline-block bg-brass text-pine-deep text-[11px] font-bold tracking-wide uppercase px-3 py-1 rounded-full mb-4"><?php echo esc($first['kategori']); ?></span>
      <p class="text-xs text-pine/50 dark:text-cream/50 mb-2"><?php echo tglPendek($first['tanggal']); ?></p>
      <h3 class="font-serif text-2xl sm:text-3xl text-pine dark:text-cream mb-3 leading-snug group-hover:text-leaf dark:group-hover:text-brass-light transition"><?php echo esc($first['judul']); ?></h3>
      <p class="text-pine/70 dark:text-cream/70 text-sm leading-relaxed mb-5"><?php echo esc(mb_strimwidth(strip_tags($first['isi']), 0, 160, '...')); ?></p>
      <span class="elink text-sm font-semibold text-leaf dark:text-brass-light">Baca selengkapnya →</span>
    </a>
    <div class="flex flex-col divide-y divide-pine/10 dark:divide-cream/10">
      <?php foreach (array_slice($beritaList, 1) as $b): ?>
      <a href="berita-detail.php?slug=<?php echo esc($b['slug']); ?>" class="group flex items-start gap-4 py-5 first:pt-0"><span class="numdot text-2xl text-brass/50 dark:text-brass/60 leading-none shrink-0 mt-1">✦</span><div><span class="text-[11px] font-semibold text-brass dark:text-brass-light tracking-wide uppercase"><?php echo esc($b['kategori']); ?> · <?php echo tglPendek($b['tanggal']); ?></span><h3 class="font-serif text-lg text-pine dark:text-cream leading-snug mt-1 group-hover:text-leaf dark:group-hover:text-brass-light transition"><?php echo esc($b['judul']); ?></h3></div></a>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>
</section>

<!-- FAQ -->
<section class="relative z-10 max-w-3xl mx-auto px-5 py-20 sm:py-28">
  <div class="reveal text-center mb-12"><p class="text-xs font-semibold tracking-widest text-leaf dark:text-brass-light uppercase mb-3">Pertanyaan Umum</p><h2 class="font-serif text-3xl sm:text-4xl text-pine dark:text-cream">Yang sering ditanyakan.</h2></div>
  <div class="reveal divide-y divide-pine/10 dark:divide-cream/10 border-y border-pine/10 dark:border-cream/10">
    <div class="acc-item"><button class="acc-trigger w-full flex items-center justify-between gap-4 py-5 text-left"><span class="font-serif text-lg text-pine dark:text-cream">Kapan pendaftaran PPDB dibuka?</span><span class="acc-icon shrink-0 text-2xl text-brass transition-transform">+</span></button><div class="acc-body"><p class="pb-5 text-pine/70 dark:text-cream/70 text-sm leading-relaxed">PPDB tahun ajaran 2026/2027 dibuka mulai 15 Juni 2026 melalui jalur prestasi, reguler, dan beasiswa.</p></div></div>
    <div class="acc-item"><button class="acc-trigger w-full flex items-center justify-between gap-4 py-5 text-left"><span class="font-serif text-lg text-pine dark:text-cream">Apa saja peminatan yang tersedia?</span><span class="acc-icon shrink-0 text-2xl text-brass transition-transform">+</span></button><div class="acc-body"><p class="pb-5 text-pine/70 dark:text-cream/70 text-sm leading-relaxed">Saat ini tersedia peminatan IPS (Ilmu Pengetahuan Sosial) dengan pendampingan intensif menuju PTN.</p></div></div>
    <div class="acc-item"><button class="acc-trigger w-full flex items-center justify-between gap-4 py-5 text-left"><span class="font-serif text-lg text-pine dark:text-cream">Apakah ada program keagamaan?</span><span class="acc-icon shrink-0 text-2xl text-brass transition-transform">+</span></button><div class="acc-body"><p class="pb-5 text-pine/70 dark:text-cream/70 text-sm leading-relaxed">Ya. Pembiasaan shalat dhuha, tahfiz Al-Quran, dan kajian rutin menjadi bagian keseharian siswa.</p></div></div>
    <div class="acc-item"><button class="acc-trigger w-full flex items-center justify-between gap-4 py-5 text-left"><span class="font-serif text-lg text-pine dark:text-cream">Bagaimana cara menghubungi sekolah?</span><span class="acc-icon shrink-0 text-2xl text-brass transition-transform">+</span></button><div class="acc-body"><p class="pb-5 text-pine/70 dark:text-cream/70 text-sm leading-relaxed">Anda dapat menghubungi kami melalui telepon, email, atau mengisi formulir pada halaman Kontak.</p></div></div>
  </div>
</section>

<!-- CTA -->
<section id="ppdb" class="relative z-10 max-w-6xl mx-auto px-5 pb-20 sm:pb-28">
  <div class="rounded-[2rem] bg-gradient-to-br from-leaf to-pine text-cream px-8 sm:px-14 py-14 text-center relative overflow-hidden reveal">
    <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full bg-brass/20 blur-3xl"></div>
    <p class="text-xs font-semibold tracking-widest text-brass-light uppercase mb-4">Penerimaan Peserta Didik Baru</p>
    <h2 class="font-serif text-3xl sm:text-5xl leading-tight max-w-2xl mx-auto">Mulai perjalananmu bersama Putra Persada.</h2>
    <p class="text-cream/80 mt-5 max-w-lg mx-auto">Kuota terbatas untuk Tahun Ajaran 2026/2027. Tersedia jalur prestasi, reguler, dan beasiswa.</p>
    <a href="ppdb.php" class="inline-block mt-8 bg-brass text-pine-deep font-semibold px-8 py-3.5 rounded-full hover:bg-brass-light transition">Daftar Sekarang</a>
  </div>
</section>

<?php $inlineJS = "wireHeaderFooter('index.php');"; ?>
<?php include __DIR__ . '/includes/foot.php'; ?>
