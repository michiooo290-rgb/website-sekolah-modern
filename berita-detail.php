<?php
/**
 * berita-detail.php — Detail Berita
 * Data dari tabel: berita (by slug)
 */
require_once __DIR__ . '/config/koneksi.php';
$pdo = db();

$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';

$stmt = $pdo->prepare('SELECT * FROM berita WHERE slug = ?');
$stmt->execute([$slug]);
$berita = $stmt->fetch();

if ($berita) {
    // increment view counter
    $upd = $pdo->prepare('UPDATE berita SET dilihat = dilihat + 1 WHERE id = ?');
    $upd->execute([$berita['id']]);
    $pageTitle = $berita['judul'];
} else {
    $pageTitle = 'Berita Tidak Ditemukan';
}

$kategoriWarna = [
  'Prestasi'   => 'bg-brass',
  'Kegiatan'   => 'bg-leaf',
  'Keagamaan'  => 'bg-emerald-600',
  'Beasiswa'   => 'bg-amber-500',
  'Lingkungan' => 'bg-teal-600',
];

include __DIR__ . '/includes/head.php';
?>

<?php if ($berita): ?>

<section class="relative z-10 bg-gradient-to-br from-pine to-pine-deep text-cream overflow-hidden">
  <div class="absolute -top-10 -right-10 w-52 h-52 rounded-full bg-brass/15 blur-3xl"></div>
  <div class="max-w-6xl mx-auto px-5 py-16 sm:py-24 relative z-10">
    <nav class="text-xs text-cream/60 mb-5"><a href="index.php" class="hover:text-brass-light">Beranda</a> <span class="mx-1">/</span> <a href="berita.php" class="hover:text-brass-light">Berita</a> <span class="mx-1">/</span> <span class="text-brass-light"><?php echo esc($berita['kategori']); ?></span></nav>
    <p class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest text-brass-light uppercase mb-4"><span class="h-px w-8 bg-brass"></span> <?php echo esc($berita['kategori']); ?></p>
    <h1 class="font-serif text-3xl sm:text-5xl leading-tight max-w-3xl"><?php echo esc($berita['judul']); ?></h1>
    <p class="text-cream/60 mt-4 text-sm"><?php echo tglIndo($berita['tanggal']); ?> · <?php echo (int)$berita['dilihat']; ?> dilihat</p>
  </div>
</section>

<?php $gambarUrl = !empty($berita['gambar']) ? 'admin/uploads/berita/' . $berita['gambar'] : null; ?>

<?php if ($gambarUrl): ?>
<section class="relative z-10 max-w-4xl mx-auto px-5 -mt-10 sm:-mt-16">
  <figure class="reveal rounded-[1.75rem] overflow-hidden shadow-2xl ring-1 ring-pine/10 dark:ring-cream/10">
    <img src="<?php echo esc($gambarUrl); ?>" alt="<?php echo esc($berita['judul']); ?>" class="w-full h-auto object-cover">
  </figure>
</section>
<?php endif; ?>

<section class="relative z-10 max-w-3xl mx-auto px-5 <?php echo $gambarUrl ? 'pt-12 sm:pt-16' : 'pt-16 sm:pt-20'; ?> pb-12 sm:pb-16">
  <article class="reveal artikel text-pine/80 dark:text-cream/75">
    <?php echo sanitize_html($berita['isi']); ?>
  </article>
</section>

<section class="relative z-10 max-w-3xl mx-auto px-5 pb-20 sm:pb-28">
  <div class="flex items-center justify-between border-t border-pine/10 dark:border-cream/10 pt-8">
    <a href="berita.php" class="elink text-sm font-semibold text-leaf dark:text-brass-light">← Semua Berita</a>
    <a href="index.php" class="elink text-sm font-semibold text-pine/60 dark:text-cream/60">Beranda →</a>
  </div>
</section>

<?php else: ?>

<section class="relative z-10 bg-gradient-to-br from-pine to-pine-deep text-cream overflow-hidden">
  <div class="max-w-6xl mx-auto px-5 py-16 sm:py-24 relative z-10">
    <nav class="text-xs text-cream/60 mb-5"><a href="index.php" class="hover:text-brass-light">Beranda</a> <span class="mx-1">/</span> <a href="berita.php" class="hover:text-brass-light">Berita</a></nav>
    <h1 class="font-serif text-4xl sm:text-6xl leading-tight">Berita Tidak Ditemukan</h1>
  </div>
</section>

<section class="relative z-10 max-w-3xl mx-auto px-5 py-20 sm:py-28">
  <p class="text-pine/70 dark:text-cream/70 text-center py-10">Berita yang Anda cari tidak tersedia. <a href="berita.php" class="elink text-leaf dark:text-brass-light font-semibold">Kembali ke daftar berita →</a></p>
</section>

<?php endif; ?>

<?php $inlineJS = "wireHeaderFooter('berita.php');"; ?>
<?php include __DIR__ . '/includes/foot.php'; ?>
