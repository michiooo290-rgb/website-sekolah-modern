<?php
/**
 * berita.php — Daftar Berita
 * Data dari tabel: berita
 */
require_once __DIR__ . '/config/koneksi.php';
$pdo = db();

$beritaList = $pdo->query('SELECT judul, slug, kategori, isi, gambar, tanggal, dilihat FROM berita ORDER BY tanggal DESC')->fetchAll();

$kategoriWarna = [
  'Prestasi'   => 'bg-brass',
  'Kegiatan'   => 'bg-leaf',
  'Keagamaan'  => 'bg-emerald-600',
  'Beasiswa'   => 'bg-amber-500',
  'Lingkungan' => 'bg-teal-600',
];

$pageTitle = 'Berita';
include __DIR__ . '/includes/head.php';
?>

<section class="relative z-10 bg-gradient-to-br from-pine to-pine-deep text-cream overflow-hidden">
  <div class="absolute -top-10 -right-10 w-52 h-52 rounded-full bg-brass/15 blur-3xl"></div>
  <div class="max-w-6xl mx-auto px-5 py-16 sm:py-24 relative z-10">
    <nav class="text-xs text-cream/60 mb-5"><a href="index.php" class="hover:text-brass-light">Beranda</a> <span class="mx-1">/</span> <span class="text-brass-light">Berita</span></nav>
    <p class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest text-brass-light uppercase mb-4"><span class="h-px w-8 bg-brass"></span> Informasi Terkini</p>
    <h1 class="font-serif text-4xl sm:text-6xl leading-tight max-w-3xl">Berita & Kegiatan</h1>
    <p class="text-cream/80 mt-5 max-w-xl">Ikuti kabar terbaru seputar prestasi, kegiatan, dan perkembangan SMA Putra Persada Batam.</p>
  </div>
</section>

<section class="relative z-10 max-w-6xl mx-auto px-5 py-20 sm:py-28">
  <?php if (empty($beritaList)): ?>
    <p class="text-center text-pine/60 dark:text-cream/60 py-16">Belum ada berita yang dipublikasikan.</p>
  <?php else: ?>
  <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
    <?php foreach ($beritaList as $i => $b):
      $warna     = $kategoriWarna[$b['kategori']] ?? 'bg-brass';
      $gambarUrl = !empty($b['gambar']) ? 'admin/uploads/berita/' . $b['gambar'] : null;
      $delay     = ($i % 3) + 1;
    ?>
    <a href="berita-detail.php?slug=<?php echo rawurlencode($b['slug']); ?>"
       class="group reveal lift flex flex-col bg-cream-deep dark:bg-pine rounded-[1.5rem] overflow-hidden ring-1 ring-pine/10 dark:ring-cream/10 hover:ring-brass/60"
       data-delay="<?php echo $delay; ?>">
      <div class="zoomimg relative aspect-[16/10] overflow-hidden">
        <?php if ($gambarUrl): ?>
          <img src="<?php echo esc($gambarUrl); ?>" alt="<?php echo esc($b['judul']); ?>" class="w-full h-full object-cover">
        <?php else: ?>
          <div class="w-full h-full bg-gradient-to-br from-leaf to-pine flex items-center justify-center">
            <span class="font-serif text-cream/90 text-2xl tracking-wide"><?php echo esc($b['kategori']); ?></span>
          </div>
        <?php endif; ?>
        <span class="absolute top-4 left-4 inline-block <?php echo $warna; ?> text-cream text-[11px] font-bold tracking-wide uppercase px-3 py-1 rounded-full shadow-lg"><?php echo esc($b['kategori']); ?></span>
      </div>
      <div class="flex flex-col flex-1 p-7">
        <p class="text-xs text-pine/50 dark:text-cream/50 mb-2"><?php echo tglPendek($b['tanggal']); ?> · <?php echo (int)$b['dilihat']; ?> dilihat</p>
        <h3 class="font-serif text-xl text-pine dark:text-cream mb-3 leading-snug group-hover:text-leaf dark:group-hover:text-brass-light transition"><?php echo esc($b['judul']); ?></h3>
        <p class="text-pine/70 dark:text-cream/70 text-sm leading-relaxed mb-5"><?php echo esc(mb_strimwidth(strip_tags($b['isi']), 0, 130, '…')); ?></p>
        <span class="elink mt-auto text-sm font-semibold text-leaf dark:text-brass-light">Baca selengkapnya →</span>
      </div>
    </a>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</section>

<section class="relative z-10 max-w-6xl mx-auto px-5 pb-20 sm:pb-28">
  <div class="rounded-[2rem] bg-gradient-to-br from-leaf to-pine text-cream px-8 sm:px-14 py-14 text-center relative overflow-hidden reveal">
    <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full bg-brass/20 blur-3xl"></div>
    <h2 class="font-serif text-3xl sm:text-5xl leading-tight max-w-2xl mx-auto">Ingin tahu lebih banyak?</h2>
    <p class="text-cream/80 mt-5 max-w-lg mx-auto">Kunjungi sekolah kami atau hubungi langsung untuk informasi lebih lanjut.</p>
    <a href="kontak.php" class="inline-block mt-8 bg-brass text-pine-deep font-semibold px-8 py-3.5 rounded-full hover:bg-brass-light transition">Hubungi Kami</a>
  </div>
</section>

<?php $inlineJS = "wireHeaderFooter('berita.php');"; ?>
<?php include __DIR__ . '/includes/foot.php'; ?>
