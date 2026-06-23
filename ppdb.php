<?php
/**
 * ppdb.php — PPDB
 * Data dari tabel: ppdb_info, pengaturan
 */
require_once __DIR__ . '/config/koneksi.php';
$pdo = db();

$syarat = $pdo->query("SELECT * FROM ppdb_info WHERE bagian='syarat' ORDER BY urutan ASC")->fetchAll();
$jadwal = $pdo->query("SELECT * FROM ppdb_info WHERE bagian='jadwal' ORDER BY urutan ASC")->fetchAll();
$alur   = $pdo->query("SELECT * FROM ppdb_info WHERE bagian='alur' ORDER BY urutan ASC")->fetchAll();
$faq    = $pdo->query("SELECT * FROM ppdb_info WHERE bagian='faq' ORDER BY urutan ASC")->fetchAll();

$mapsEmbed = setting('maps_embed') ?? 'https://maps.google.com/maps?q=1.1707668,104.1001968&z=16&output=embed';
$alamat    = setting('alamat') ?? '';
$telepon   = setting('telepon') ?? '';
$email     = setting('email') ?? '';
$jamOp     = setting('jam_operasional') ?? '';

$jadwalIcons = ['📅','📝','📋','🏫','🎒'];
$alurIcons   = ['📍','📝','📝','✅'];

$pageTitle = 'PPDB';
include __DIR__ . '/includes/head.php';
?>

<section class="relative z-10 bg-gradient-to-br from-pine to-pine-deep text-cream overflow-hidden">
  <div class="absolute -top-10 -right-10 w-52 h-52 rounded-full bg-brass/15 blur-3xl"></div>
  <div class="max-w-6xl mx-auto px-5 py-16 sm:py-24 relative z-10">
    <nav class="text-xs text-cream/60 mb-5"><a href="index.php" class="hover:text-brass-light">Beranda</a> <span class="mx-1">/</span> <span class="text-brass-light">PPDB</span></nav>
    <p class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest text-brass-light uppercase mb-4"><span class="h-px w-8 bg-brass"></span> Penerimaan Peserta Didik Baru</p>
    <h1 class="font-serif text-4xl sm:text-6xl leading-tight max-w-3xl">PPDB 2026/2027</h1>
    <p class="text-cream/80 mt-5 max-w-xl">Informasi lengkap pendaftaran siswa baru SMA Putra Persada Batam. Pendaftaran dilakukan secara offline (datang langsung ke sekolah).</p>
  </div>
</section>

<!-- Syarat -->
<section class="relative z-10 max-w-6xl mx-auto px-5 py-20 sm:py-28">
  <div class="reveal text-center max-w-2xl mx-auto mb-14">
    <p class="text-xs font-semibold tracking-widest text-leaf dark:text-brass-light uppercase mb-4">Persyaratan</p>
    <h2 class="font-serif text-3xl sm:text-4xl text-pine dark:text-cream">Syarat Pendaftaran.</h2>
  </div>
  <div class="grid sm:grid-cols-2 gap-6 reveal">
    <?php foreach ($syarat as $s): ?>
    <div class="bg-cream-deep dark:bg-pine rounded-2xl p-7 ring-1 ring-pine/10 dark:ring-cream/10">
      <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-leaf to-pine flex items-center justify-center text-xl mb-4">📋</div>
      <h3 class="font-serif text-lg text-pine dark:text-cream mb-2"><?php echo esc($s['judul']); ?></h3>
      <div class="text-sm text-pine/70 dark:text-cream/70 leading-relaxed"><?php echo sanitize_html($s['isi']); ?></div>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- Jadwal -->
<section class="relative z-10 bg-cream-deep dark:bg-pine">
  <div class="max-w-6xl mx-auto px-5 py-20 sm:py-28 grid lg:grid-cols-3 gap-12">
    <div class="reveal">
      <p class="text-xs font-semibold tracking-widest text-leaf dark:text-brass-light uppercase mb-4">Jadwal</p>
      <h2 class="font-serif text-3xl sm:text-4xl text-pine dark:text-cream leading-tight">Jadwal penting PPDB.</h2>
      <p class="text-pine/70 dark:text-cream/70 mt-4 text-sm">Catat tanggal-tanggal penting berikut agar tidak terlewat.</p>
    </div>
    <div class="lg:col-span-2 reveal">
      <div class="relative pl-8 border-l-2 border-pine/15 dark:border-cream/15 space-y-8">
        <?php foreach ($jadwal as $j): ?>
        <div class="relative"><span class="absolute -left-[41px] top-1 w-4 h-4 rounded-full bg-brass ring-4 ring-cream-deep dark:ring-pine"></span><p class="font-serif text-brass dark:text-brass-light text-lg"><?php echo esc($j['tanggal']); ?></p><h3 class="font-semibold text-pine dark:text-cream mt-0.5"><?php echo esc($j['judul']); ?></h3><p class="text-sm text-pine/60 dark:text-cream/60"><?php echo esc($j['isi']); ?></p></div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- Alur -->
<section class="relative z-10 max-w-6xl mx-auto px-5 py-20 sm:py-28">
  <div class="reveal text-center max-w-2xl mx-auto mb-14">
    <p class="text-xs font-semibold tracking-widest text-leaf dark:text-brass-light uppercase mb-4">Alur</p>
    <h2 class="font-serif text-3xl sm:text-4xl text-pine dark:text-cream">Langkah pendaftaran.</h2>
  </div>
  <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 reveal">
    <?php foreach ($alur as $i => $a): ?>
    <div class="text-center">
      <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-leaf to-pine flex items-center justify-center text-2xl text-cream mx-auto mb-4 shadow-lg"><?php echo $i + 1; ?></div>
      <h3 class="font-serif text-lg text-pine dark:text-cream mb-2"><?php echo esc($a['judul']); ?></h3>
      <p class="text-sm text-pine/70 dark:text-cream/70 leading-relaxed"><?php echo esc($a['isi']); ?></p>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- Lokasi -->
<section class="relative z-10 bg-pine text-cream">
  <div class="max-w-6xl mx-auto px-5 py-20 sm:py-28">
    <div class="reveal max-w-xl mb-14">
      <p class="text-xs font-semibold tracking-widest text-brass-light uppercase mb-4">Lokasi</p>
      <h2 class="font-serif text-3xl sm:text-4xl leading-tight">Datang langsung ke sekolah.</h2>
      <p class="text-cream/70 mt-4 text-sm">Pendaftaran dilakukan secara offline. Silakan kunjungi kami pada hari dan jam kerja.</p>
    </div>
    <div class="grid lg:grid-cols-2 gap-10 reveal">
      <div>
        <ul class="space-y-4 text-cream/80 text-sm">
          <li class="flex items-start gap-3"><span class="text-brass-light text-lg mt-0.5">📍</span><div><p class="font-semibold text-cream">Alamat</p><p class="text-cream/70"><?php echo esc($alamat); ?></p></div></li>
          <li class="flex items-start gap-3"><span class="text-brass-light text-lg mt-0.5">📞</span><div><p class="font-semibold text-cream">Telepon</p><p class="text-cream/70"><?php echo esc($telepon); ?></p></div></li>
          <li class="flex items-start gap-3"><span class="text-brass-light text-lg mt-0.5">✉️</span><div><p class="font-semibold text-cream">Email</p><p class="text-cream/70"><?php echo esc($email); ?></p></div></li>
          <li class="flex items-start gap-3"><span class="text-brass-light text-lg mt-0.5">🕐</span><div><p class="font-semibold text-cream">Jam Operasional</p><p class="text-cream/70"><?php echo nl2br(esc($jamOp)); ?></p></div></li>
        </ul>
        <a href="#" class="inline-block mt-8 bg-brass text-pine-deep font-semibold px-7 py-3.5 rounded-full hover:bg-brass-light transition">📄 Unduh Formulir PPDB (PDF)</a>
      </div>
      <div class="rounded-2xl overflow-hidden ring-1 ring-cream/10 aspect-video">
        <iframe src="<?php echo esc($mapsEmbed); ?>" width="100%" height="100%" style="border:0" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
    </div>
  </div>
</section>

<!-- FAQ PPDB -->
<section class="relative z-10 max-w-3xl mx-auto px-5 py-20 sm:py-28">
  <div class="reveal text-center mb-12"><p class="text-xs font-semibold tracking-widest text-leaf dark:text-brass-light uppercase mb-3">FAQ PPDB</p><h2 class="font-serif text-3xl sm:text-4xl text-pine dark:text-cream">Pertanyaan seputar pendaftaran.</h2></div>
  <div class="reveal divide-y divide-pine/10 dark:divide-cream/10 border-y border-pine/10 dark:border-cream/10">
    <?php foreach ($faq as $f): ?>
    <div class="acc-item"><button class="acc-trigger w-full flex items-center justify-between gap-4 py-5 text-left"><span class="font-serif text-lg text-pine dark:text-cream"><?php echo esc($f['judul']); ?></span><span class="acc-icon shrink-0 text-2xl text-brass transition-transform">+</span></button><div class="acc-body"><p class="pb-5 text-pine/70 dark:text-cream/70 text-sm leading-relaxed"><?php echo esc($f['isi']); ?></p></div></div>
    <?php endforeach; ?>
  </div>
</section>

<!-- CTA -->
<section class="relative z-10 max-w-6xl mx-auto px-5 pb-20 sm:pb-28">
  <div class="rounded-[2rem] bg-gradient-to-br from-leaf to-pine text-cream px-8 sm:px-14 py-14 text-center relative overflow-hidden reveal">
    <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full bg-brass/20 blur-3xl"></div>
    <h2 class="font-serif text-3xl sm:text-5xl leading-tight max-w-2xl mx-auto">Masih punya pertanyaan?</h2>
    <p class="text-cream/80 mt-5 max-w-lg mx-auto">Hubungi kami langsung atau kunjungi sekolah untuk konsultasi gratis seputar PPDB.</p>
    <a href="kontak.php" class="inline-block mt-8 bg-brass text-pine-deep font-semibold px-8 py-3.5 rounded-full hover:bg-brass-light transition">Hubungi Kami</a>
  </div>
</section>

<?php $inlineJS = "wireHeaderFooter('ppdb.php');"; ?>
<?php include __DIR__ . '/includes/foot.php'; ?>
