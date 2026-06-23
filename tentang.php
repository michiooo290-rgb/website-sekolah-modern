<?php
/**
 * tentang.php — Tentang Kami
 * Data dari tabel: tentang (bagian: sejarah, sambutan, fasilitas), guru
 */
require_once __DIR__ . '/config/koneksi.php';
$pdo = db();

$sejarah   = $pdo->query("SELECT * FROM tentang WHERE bagian='sejarah' LIMIT 1")->fetch();
$sambutan  = $pdo->query("SELECT * FROM tentang WHERE bagian='sambutan' LIMIT 1")->fetch();
$fasilitas = $pdo->query("SELECT * FROM tentang WHERE bagian='fasilitas' ORDER BY id ASC")->fetchAll();
$kepsek    = $pdo->query("SELECT * FROM guru WHERE jabatan='Kepala Sekolah' LIMIT 1")->fetch();

$pageTitle = 'Tentang Kami';
include __DIR__ . '/includes/head.php';
?>

<section class="relative z-10 bg-gradient-to-br from-pine to-pine-deep text-cream overflow-hidden">
  <div class="absolute -top-10 -right-10 w-52 h-52 rounded-full bg-brass/15 blur-3xl"></div>
  <div class="max-w-6xl mx-auto px-5 py-16 sm:py-24 relative z-10">
    <nav class="text-xs text-cream/60 mb-5"><a href="index.php" class="hover:text-brass-light">Beranda</a> <span class="mx-1">/</span> <span class="text-brass-light">Tentang</span></nav>
    <p class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest text-brass-light uppercase mb-4"><span class="h-px w-8 bg-brass"></span> Mengenal Kami</p>
    <h1 class="font-serif text-4xl sm:text-6xl leading-tight max-w-3xl">Tentang SMA Putra Persada</h1>
    <p class="text-cream/80 mt-5 max-w-xl">Mengenal lebih dekat sejarah, visi, dan dedikasi kami dalam membentuk generasi unggul.</p>
  </div>
</section>

<!-- Sejarah -->
<section class="relative z-10 max-w-6xl mx-auto px-5 py-20 sm:py-28">
  <div class="grid lg:grid-cols-2 gap-14 items-center">
    <div class="reveal">
      <div class="rounded-[2rem] overflow-hidden bg-gradient-to-br from-pine to-pine-deep aspect-[4/3] flex items-center justify-center shadow-xl">
        <img src="assets/img/logo.jpeg" alt="Logo SMA Putra Persada" class="w-40 h-40 object-contain opacity-80">
      </div>
    </div>
    <div class="reveal">
      <p class="text-xs font-semibold tracking-widest text-leaf dark:text-brass-light uppercase mb-4">Sejarah</p>
      <h2 class="font-serif text-3xl sm:text-4xl text-pine dark:text-cream leading-tight mb-5"><?php echo esc($sejarah['judul']); ?></h2>
      <?php echo sanitize_html($sejarah['isi']); ?>
    </div>
  </div>
</section>

<!-- Sambutan Kepala Sekolah -->
<section class="relative z-10 bg-pine text-cream">
  <div class="max-w-6xl mx-auto px-5 py-20 sm:py-28">
    <div class="reveal max-w-xl mb-14">
      <p class="text-xs font-semibold tracking-widest text-brass-light uppercase mb-4">Sambutan</p>
      <h2 class="font-serif text-3xl sm:text-4xl leading-tight">Kata Kepala Sekolah.</h2>
    </div>
    <div class="grid lg:grid-cols-12 gap-10 items-start reveal">
      <div class="lg:col-span-4">
        <div class="relative overflow-hidden rounded-2xl aspect-[3/4] ring-1 ring-cream/10 bg-pine-deep">
          <img src="<?php echo esc($kepsek['foto']); ?>" alt="<?php echo esc($kepsek['nama']); ?>" class="w-full h-full object-cover object-top">
          <div class="absolute inset-x-0 bottom-0 h-1/3 bg-gradient-to-t from-pine-deep/70 to-transparent"></div>
        </div>
        <div class="mt-4">
          <p class="font-serif text-lg text-cream"><?php echo esc($kepsek['nama']); ?></p>
          <p class="text-xs text-brass-light tracking-wide"><?php echo esc($kepsek['jabatan']); ?></p>
        </div>
      </div>
      <div class="lg:col-span-8">
        <p class="font-serif text-6xl text-brass-light leading-none mb-2">"</p>
        <blockquote class="font-serif text-xl sm:text-2xl leading-snug mb-6"><?php echo esc($sambutan['judul']); ?></blockquote>
        <?php echo sanitize_html($sambutan['isi']); ?>
      </div>
    </div>
  </div>
</section>

<!-- Profil Angka -->
<section class="relative z-10 max-w-6xl mx-auto px-5 py-20 sm:py-28">
  <div class="reveal text-center max-w-2xl mx-auto mb-14">
    <p class="text-xs font-semibold tracking-widest text-leaf dark:text-brass-light uppercase mb-4">Profil</p>
    <h2 class="font-serif text-3xl sm:text-4xl text-pine dark:text-cream">Sekolah dalam angka.</h2>
  </div>
  <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 reveal">
    <div class="rounded-2xl border border-pine/10 dark:border-cream/10 p-7 text-center bg-cream-deep/40 dark:bg-pine/40"><p class="font-serif text-4xl text-brass dark:text-brass-light">25+</p><p class="text-xs text-pine/60 dark:text-cream/60 mt-2 uppercase tracking-wide">Tahun Berdiri</p></div>
    <div class="rounded-2xl border border-pine/10 dark:border-cream/10 p-7 text-center bg-cream-deep/40 dark:bg-pine/40"><p class="font-serif text-4xl text-brass dark:text-brass-light">750+</p><p class="text-xs text-pine/60 dark:text-cream/60 mt-2 uppercase tracking-wide">Peserta Didik</p></div>
    <div class="rounded-2xl border border-pine/10 dark:border-cream/10 p-7 text-center bg-cream-deep/40 dark:bg-pine/40"><p class="font-serif text-4xl text-brass dark:text-brass-light">45+</p><p class="text-xs text-pine/60 dark:text-cream/60 mt-2 uppercase tracking-wide">Guru &amp; Staf</p></div>
    <div class="rounded-2xl border border-pine/10 dark:border-cream/10 p-7 text-center bg-cream-deep/40 dark:bg-pine/40"><p class="font-serif text-4xl text-brass dark:text-brass-light">25+</p><p class="text-xs text-pine/60 dark:text-cream/60 mt-2 uppercase tracking-wide">Ekstrakurikuler</p></div>
  </div>
</section>

<!-- Fasilitas -->
<section class="relative z-10 bg-cream-deep dark:bg-pine">
  <div class="max-w-6xl mx-auto px-5 py-20 sm:py-28">
    <div class="reveal max-w-xl mb-14">
      <p class="text-xs font-semibold tracking-widest text-leaf dark:text-brass-light uppercase mb-4">Fasilitas</p>
      <h2 class="font-serif text-3xl sm:text-4xl text-pine dark:text-cream leading-tight">Lingkungan belajar yang mendukung.</h2>
      <p class="text-pine/70 dark:text-cream/70 mt-4 text-sm">Fasilitas modern untuk mendukung proses pembelajaran yang optimal.</p>
    </div>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 reveal">
      <?php
      $fasIcons = ['🏫','🔬','💻','📚','🕌','⚽'];
      $fasGrads = ['from-leaf to-pine','from-emerald-600 to-pine','from-teal-600 to-pine','from-amber-500 to-brass','from-leaf to-pine','from-pine to-pine-deep'];
      foreach ($fasilitas as $i => $f):
      ?>
      <div class="bg-cream dark:bg-pine-deep rounded-2xl p-6 ring-1 ring-pine/10 dark:ring-cream/10">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br <?php echo $fasGrads[$i] ?? 'from-leaf to-pine'; ?> flex items-center justify-center text-xl mb-4"><?php echo $fasIcons[$i] ?? '🏫'; ?></div>
        <h3 class="font-serif text-lg text-pine dark:text-cream mb-2"><?php echo esc($f['judul']); ?></h3>
        <p class="text-sm text-pine/70 dark:text-cream/70 leading-relaxed"><?php echo esc($f['isi']); ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="relative z-10 max-w-6xl mx-auto px-5 py-20 sm:py-28">
  <div class="rounded-[2rem] bg-gradient-to-br from-leaf to-pine text-cream px-8 sm:px-14 py-14 text-center relative overflow-hidden reveal">
    <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full bg-brass/20 blur-3xl"></div>
    <h2 class="font-serif text-3xl sm:text-5xl leading-tight max-w-2xl mx-auto">Bergabunglah bersama kami.</h2>
    <p class="text-cream/80 mt-5 max-w-lg mx-auto">SMA Putra Persada Batam — tempat terbaik untuk memulai perjalanan pendidikan putra-putri Anda.</p>
    <a href="ppdb.php" class="inline-block mt-8 bg-brass text-pine-deep font-semibold px-8 py-3.5 rounded-full hover:bg-brass-light transition">Daftar PPDB</a>
  </div>
</section>

<?php $inlineJS = "wireHeaderFooter('tentang.php');"; ?>
<?php include __DIR__ . '/includes/foot.php'; ?>
