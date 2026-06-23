<?php
/**
 * ekstrakurikuler.php — Ekstrakurikuler
 * Data dari tabel: ekstrakurikuler
 */
require_once __DIR__ . '/config/koneksi.php';
$pdo = db();

$ekskulList = $pdo->query('SELECT * FROM ekstrakurikuler ORDER BY kategori, nama ASC')->fetchAll();

// Group by kategori
$kategori = [];
foreach ($ekskulList as $e) {
    $kategori[$e['kategori']][] = $e;
}

$kategoriMeta = [
    'Keagamaan'             => ['🕌', 'from-emerald-600 to-pine'],
    'Olahraga'              => ['⚽', 'from-leaf to-pine'],
    'Seni & Budaya'         => ['🎨', 'from-amber-500 to-brass'],
    'Akademik & Sains'      => ['🔬', 'from-teal-600 to-pine'],
    'Organisasi & Kepanduan'=> ['🚩', 'from-pine to-pine-deep'],
];

$total = count($ekskulList);
$pageTitle = 'Ekstrakurikuler';
include __DIR__ . '/includes/head.php';
?>

<section class="relative z-10 bg-gradient-to-br from-pine to-pine-deep text-cream overflow-hidden">
  <div class="absolute -bottom-10 -left-10 w-52 h-52 rounded-full bg-leaf/25 blur-3xl"></div>
  <div class="absolute -top-10 -right-10 w-52 h-52 rounded-full bg-brass/15 blur-3xl"></div>
  <div class="max-w-6xl mx-auto px-5 py-16 sm:py-24 relative z-10">
    <nav class="text-xs text-cream/60 mb-5"><a href="index.php" class="hover:text-brass-light">Beranda</a> <span class="mx-1">/</span> <span class="text-brass-light">Ekstrakurikuler</span></nav>
    <p class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest text-brass-light uppercase mb-4"><span class="h-px w-8 bg-brass"></span> Wadah Bakat & Minat</p>
    <h1 class="font-serif text-4xl sm:text-6xl leading-tight max-w-3xl">Ekstrakurikuler</h1>
    <p class="text-cream/80 mt-5 max-w-xl">Lebih dari <?php echo $total; ?> kegiatan untuk mengembangkan bakat, minat, dan karakter siswa di luar jam pelajaran.</p>
    <div class="mt-8 flex flex-wrap gap-8">
      <div><p class="font-serif text-4xl text-brass-light"><?php echo count($kategori); ?></p><p class="text-xs text-cream/60 uppercase tracking-wide">Kategori</p></div>
      <div><p class="font-serif text-4xl text-brass-light"><?php echo $total; ?>+</p><p class="text-xs text-cream/60 uppercase tracking-wide">Pilihan Kegiatan</p></div>
    </div>
  </div>
</section>

<div class="relative z-10 max-w-6xl mx-auto px-5 py-20 sm:py-24 space-y-20">
  <?php foreach ($kategori as $nama => $items):
    $icon = $kategoriMeta[$nama][0] ?? '✦';
    $grad = $kategoriMeta[$nama][1] ?? 'from-leaf to-pine';
  ?>
  <section class="reveal">
    <div class="flex items-center gap-4 mb-8">
      <div class="w-14 h-14 rounded-2xl bg-gradient-to-br <?php echo $grad; ?> flex items-center justify-center text-2xl shadow-lg"><?php echo $icon; ?></div>
      <div><h2 class="font-serif text-2xl sm:text-3xl text-pine dark:text-cream"><?php echo esc($nama); ?></h2><p class="text-xs text-pine/55 dark:text-cream/55"><?php echo count($items); ?> kegiatan</p></div>
    </div>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
      <?php foreach ($items as $e): ?>
      <div class="group rounded-2xl border border-pine/10 dark:border-cream/10 p-6 hover:border-brass hover:shadow-lg transition bg-cream-deep/40 dark:bg-pine/40">
        <div class="flex items-start gap-3">
          <span class="text-brass text-lg mt-0.5">✦</span>
          <div>
            <h3 class="font-serif text-lg text-pine dark:text-cream leading-snug mb-1.5 group-hover:text-leaf dark:group-hover:text-brass-light transition"><?php echo esc($e['nama']); ?></h3>
            <p class="text-sm text-pine/70 dark:text-cream/70 leading-relaxed"><?php echo esc($e['deskripsi']); ?></p>
            <?php if ($e['pembina']): ?>
            <p class="text-xs text-pine/50 dark:text-cream/50 mt-2">Pembina: <?php echo esc($e['pembina']); ?></p>
            <?php endif; ?>
            <?php if ($e['jadwal']): ?>
            <p class="text-xs text-pine/50 dark:text-cream/50 mt-1">Jadwal: <?php echo esc($e['jadwal']); ?></p>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </section>
  <?php endforeach; ?>
</div>

<section class="relative z-10 bg-pine text-cream">
  <div class="max-w-6xl mx-auto px-5 py-20 sm:py-24 grid md:grid-cols-3 gap-10">
    <div class="reveal"><p class="text-xs font-semibold tracking-widest text-brass-light uppercase mb-4">Informasi</p><h2 class="font-serif text-3xl leading-tight">Bagaimana cara bergabung?</h2></div>
    <div class="md:col-span-2 reveal grid sm:grid-cols-3 gap-6">
      <div class="bg-pine-deep/60 rounded-2xl p-6 ring-1 ring-cream/10"><p class="numdot text-brass-light text-3xl mb-3">01</p><h3 class="font-serif text-lg mb-2">Pilih Kegiatan</h3><p class="text-sm text-cream/70 leading-relaxed">Pilih ekstrakurikuler sesuai minat dan bakatmu.</p></div>
      <div class="bg-pine-deep/60 rounded-2xl p-6 ring-1 ring-cream/10"><p class="numdot text-brass-light text-3xl mb-3">02</p><h3 class="font-serif text-lg mb-2">Daftar</h3><p class="text-sm text-cream/70 leading-relaxed">Isi formulir pendaftaran di awal tahun ajaran.</p></div>
      <div class="bg-pine-deep/60 rounded-2xl p-6 ring-1 ring-cream/10"><p class="numdot text-brass-light text-3xl mb-3">03</p><h3 class="font-serif text-lg mb-2">Ikuti Jadwal</h3><p class="text-sm text-cream/70 leading-relaxed">Hadir pada jadwal latihan rutin bersama pembina.</p></div>
    </div>
  </div>
</section>

<section class="relative z-10 max-w-6xl mx-auto px-5 py-20 sm:py-28">
  <div class="rounded-[2rem] bg-gradient-to-br from-leaf to-pine text-cream px-8 sm:px-14 py-14 text-center relative overflow-hidden reveal">
    <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full bg-brass/20 blur-3xl"></div>
    <h2 class="font-serif text-3xl sm:text-5xl leading-tight max-w-2xl mx-auto">Temukan bakatmu bersama kami.</h2>
    <p class="text-cream/80 mt-5 max-w-lg mx-auto">Jadilah bagian dari SMA Putra Persada Batam dan kembangkan potensimu seluas-luasnya.</p>
    <a href="ppdb.php" class="inline-block mt-8 bg-brass text-pine-deep font-semibold px-8 py-3.5 rounded-full hover:bg-brass-light transition">Daftar PPDB</a>
  </div>
</section>

<?php $inlineJS = "wireHeaderFooter('ekstrakurikuler.php');"; ?>
<?php include __DIR__ . '/includes/foot.php'; ?>
