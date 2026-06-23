<?php
/**
 * visi-misi.php — Visi & Misi
 * Data dari tabel: visi_misi (tipe: visi, misi, tujuan, nilai)
 */
require_once __DIR__ . '/config/koneksi.php';
$pdo = db();

$visi    = $pdo->query("SELECT * FROM visi_misi WHERE tipe='visi' ORDER BY urutan ASC")->fetchAll();
$misi    = $pdo->query("SELECT * FROM visi_misi WHERE tipe='misi' ORDER BY urutan ASC")->fetchAll();
$tujuan  = $pdo->query("SELECT * FROM visi_misi WHERE tipe='tujuan' ORDER BY urutan ASC")->fetchAll();
$nilai   = $pdo->query("SELECT * FROM visi_misi WHERE tipe='nilai' ORDER BY urutan ASC")->fetchAll();

$pageTitle = 'Visi & Misi';
include __DIR__ . '/includes/head.php';
?>

<section class="relative z-10 bg-gradient-to-br from-pine to-pine-deep text-cream overflow-hidden">
  <div class="absolute -top-10 -right-10 w-52 h-52 rounded-full bg-brass/15 blur-3xl"></div>
  <div class="max-w-6xl mx-auto px-5 py-16 sm:py-24 relative z-10">
    <nav class="text-xs text-cream/60 mb-5"><a href="index.php" class="hover:text-brass-light">Beranda</a> <span class="mx-1">/</span> <span class="text-brass-light">Visi & Misi</span></nav>
    <p class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest text-brass-light uppercase mb-4"><span class="h-px w-8 bg-brass"></span> Arah & Tujuan Kami</p>
    <h1 class="font-serif text-4xl sm:text-6xl leading-tight max-w-3xl">Visi & Misi Sekolah</h1>
    <p class="text-cream/80 mt-5 max-w-xl">Landasan dan arah yang memandu setiap langkah SMA Putra Persada Batam dalam mendidik generasi.</p>
  </div>
</section>

<section class="relative z-10">
  <div class="max-w-6xl mx-auto px-5 py-20 sm:py-28">
    <div class="reveal max-w-xl mb-12"><p class="text-xs font-semibold tracking-widest text-leaf dark:text-brass-light uppercase mb-4">Visi</p><h2 class="font-serif text-3xl sm:text-4xl text-pine dark:text-cream leading-tight">Landasan utama arah sekolah.</h2></div>
    <div class="grid sm:grid-cols-2 gap-x-10 gap-y-8 reveal">
      <?php foreach ($visi as $i => $v): ?>
      <div class="flex gap-5"><span class="numdot text-brass text-3xl leading-none shrink-0">0<?php echo $i + 1; ?></span><p class="text-pine/85 dark:text-cream/85 leading-relaxed pt-1"><?php echo esc($v['isi']); ?></p></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="relative z-10 bg-pine text-cream">
  <div class="max-w-6xl mx-auto px-5 py-20 sm:py-28">
    <div class="reveal max-w-xl mb-12"><p class="text-xs font-semibold tracking-widest text-brass-light uppercase mb-4">Misi</p><h2 class="font-serif text-3xl sm:text-4xl leading-tight">Langkah nyata mewujudkan visi.</h2></div>
    <div class="grid sm:grid-cols-2 gap-x-10 gap-y-8 reveal">
      <?php foreach ($misi as $i => $m): ?>
      <div class="flex gap-5"><span class="numdot text-brass-light text-3xl leading-none shrink-0">0<?php echo $i + 1; ?></span><p class="text-cream/85 leading-relaxed pt-1"><?php echo esc($m['isi']); ?></p></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="relative z-10 max-w-6xl mx-auto px-5 py-20 sm:py-28">
  <div class="reveal text-center max-w-2xl mx-auto mb-14"><p class="text-xs font-semibold tracking-widest text-leaf dark:text-brass-light uppercase mb-4">Tujuan</p><h2 class="font-serif text-3xl sm:text-4xl text-pine dark:text-cream">Hasil yang ingin kami capai.</h2></div>
  <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 reveal">
    <?php foreach ($tujuan as $i => $t): ?>
    <div class="rounded-2xl border border-pine/10 dark:border-cream/10 p-7 hover:border-brass transition bg-cream-deep/40 dark:bg-pine/40"><p class="numdot text-brass text-3xl mb-3">0<?php echo $i + 1; ?></p><h3 class="font-serif text-lg text-pine dark:text-cream mb-2"><?php echo esc($t['judul']); ?></h3><p class="text-sm text-pine/70 dark:text-cream/70 leading-relaxed"><?php echo esc($t['isi']); ?></p></div>
    <?php endforeach; ?>
  </div>
</section>

<section class="relative z-10 bg-cream-deep dark:bg-pine">
  <div class="max-w-6xl mx-auto px-5 py-20 sm:py-28">
    <div class="reveal text-center max-w-2xl mx-auto mb-14"><p class="text-xs font-semibold tracking-widest text-leaf dark:text-brass-light uppercase mb-4">Nilai-Nilai</p><h2 class="font-serif text-3xl sm:text-4xl text-pine dark:text-cream">Karakter yang kami tanamkan.</h2></div>
    <div class="flex flex-wrap justify-center gap-4 reveal">
      <?php foreach ($nilai as $n): ?>
      <div class="bg-cream dark:bg-pine-deep rounded-2xl px-6 py-5 ring-1 ring-pine/10 dark:ring-cream/10 max-w-xs"><p class="font-serif text-xl text-pine dark:text-brass-light mb-1"><?php echo esc($n['judul']); ?></p><p class="text-xs text-pine/65 dark:text-cream/65 leading-relaxed"><?php echo esc($n['isi']); ?></p></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="relative z-10 max-w-6xl mx-auto px-5 py-20 sm:py-28">
  <div class="rounded-[2rem] bg-gradient-to-br from-leaf to-pine text-cream px-8 sm:px-14 py-14 text-center relative overflow-hidden reveal">
    <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full bg-brass/20 blur-3xl"></div>
    <h2 class="font-serif text-3xl sm:text-5xl leading-tight max-w-2xl mx-auto">Jadilah bagian dari visi kami.</h2>
    <p class="text-cream/80 mt-5 max-w-lg mx-auto">Bergabunglah bersama SMA Putra Persada Batam pada Tahun Ajaran 2026/2027.</p>
    <a href="ppdb.php" class="inline-block mt-8 bg-brass text-pine-deep font-semibold px-8 py-3.5 rounded-full hover:bg-brass-light transition">Daftar PPDB</a>
  </div>
</section>

<?php $inlineJS = "wireHeaderFooter('visi-misi.php');"; ?>
<?php include __DIR__ . '/includes/foot.php'; ?>
