<?php
/**
 * kontak.php — Kontak & Lokasi
 * Data dari tabel: pengaturan, pesan_kontak (write)
 */
require_once __DIR__ . '/config/koneksi.php';
if (session_status() === PHP_SESSION_NONE) session_start();
$pdo = db();

$alamat    = setting('alamat') ?? '';
$telepon   = setting('telepon') ?? '';
$email     = setting('email') ?? '';
$jamOp     = setting('jam_operasional') ?? '';
$mapsEmbed = setting('maps_embed') ?? 'https://maps.google.com/maps?q=1.1707668,104.1001968&z=16&output=embed';

// Generate CSRF token
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

// Handle form submission
$formMsg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!isset($_POST['csrf_token'], $_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $_POST['csrf_token'])) {
        $formMsg = '<div class="bg-red-100 border border-red-300 text-red-800 rounded-xl px-5 py-4 mb-6 text-sm">Token keamanan tidak valid. Silakan muat ulang halaman.</div>';
    } else {
    $nama    = trim($_POST['nama'] ?? '');
    $emailIn = trim($_POST['email'] ?? '');
    $teleponIn = trim($_POST['telepon'] ?? '');
    $subjek  = trim($_POST['subjek'] ?? '');
    $pesan   = trim($_POST['pesan'] ?? '');
    $honeypot = $_POST['website_url'] ?? ''; // Honeypot field

    // Honeypot: if filled, it's a bot
    if ($honeypot !== '') {
        $formMsg = '<div class="bg-leaf/20 border border-leaf/40 text-leaf dark:text-brass-light rounded-xl px-5 py-4 mb-6 text-sm font-semibold">✓ Pesan berhasil dikirim. Terima kasih!</div>';
    } else {
        // Rate limiting: max 3 submissions per IP per 10 minutes
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM pesan_kontak WHERE tanggal > DATE_SUB(NOW(), INTERVAL 10 MINUTE) AND telepon = ?");
        // Use a simple rate limit check via session
        $_SESSION['contact_attempts'] = ($_SESSION['contact_attempts'] ?? 0);
        $_SESSION['contact_last_time'] = $_SESSION['contact_last_time'] ?? 0;
        $timeSince = time() - $_SESSION['contact_last_time'];
        if ($timeSince > 600) { // Reset after 10 minutes
            $_SESSION['contact_attempts'] = 0;
        }

        // Validate
        $errors = [];
        if ($nama === '' || mb_strlen($nama) < 2) $errors[] = 'Nama wajib diisi (min. 2 karakter).';
        if (!filter_var($emailIn, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email tidak valid.';
        if ($pesan === '' || mb_strlen($pesan) < 10) $errors[] = 'Pesan wajib diisi (min. 10 karakter).';
        if (mb_strlen($nama) > 100) $errors[] = 'Nama maksimal 100 karakter.';
        if (mb_strlen($emailIn) > 150) $errors[] = 'Email maksimal 150 karakter.';
        if (mb_strlen($pesan) > 5000) $errors[] = 'Pesan maksimal 5000 karakter.';
        if ($_SESSION['contact_attempts'] >= 3) $errors[] = 'Terlalu banyak pesan. Coba lagi dalam 10 menit.';

        if (empty($errors)) {
            $stmt = $pdo->prepare('INSERT INTO pesan_kontak (nama, email, telepon, subjek, pesan) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$nama, $emailIn, $teleponIn ?: null, $subjek ?: null, $pesan]);
            $_SESSION['contact_attempts']++;
            $_SESSION['contact_last_time'] = time();
            $formMsg = '<div class="bg-leaf/20 border border-leaf/40 text-leaf dark:text-brass-light rounded-xl px-5 py-4 mb-6 text-sm font-semibold">✓ Pesan berhasil dikirim. Terima kasih!</div>';
        } else {
            $formMsg = '<div class="bg-red-100 border border-red-300 text-red-800 rounded-xl px-5 py-4 mb-6 text-sm">' . implode('<br>', array_map('esc', $errors)) . '</div>';
        }
    }
    } // end CSRF check
}

$pageTitle = 'Kontak';
include __DIR__ . '/includes/head.php';
?>

<section class="relative z-10 bg-gradient-to-br from-pine to-pine-deep text-cream overflow-hidden">
  <div class="absolute -top-10 -right-10 w-52 h-52 rounded-full bg-brass/15 blur-3xl"></div>
  <div class="max-w-6xl mx-auto px-5 py-16 sm:py-24 relative z-10">
    <nav class="text-xs text-cream/60 mb-5"><a href="index.php" class="hover:text-brass-light">Beranda</a> <span class="mx-1">/</span> <span class="text-brass-light">Kontak</span></nav>
    <p class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest text-brass-light uppercase mb-4"><span class="h-px w-8 bg-brass"></span> Hubungi Kami</p>
    <h1 class="font-serif text-4xl sm:text-6xl leading-tight max-w-3xl">Kontak & Lokasi</h1>
    <p class="text-cream/80 mt-5 max-w-xl">Kami siap membantu Anda. Jangan ragu untuk menghubungi kami melalui informasi di bawah ini.</p>
  </div>
</section>

<!-- Info + Form -->
<section class="relative z-10 max-w-6xl mx-auto px-5 py-20 sm:py-28">
  <div class="grid lg:grid-cols-2 gap-14">
    <!-- Info -->
    <div class="reveal">
      <p class="text-xs font-semibold tracking-widest text-leaf dark:text-brass-light uppercase mb-4">Informasi</p>
      <h2 class="font-serif text-3xl sm:text-4xl text-pine dark:text-cream leading-tight mb-8">Data kontak sekolah.</h2>
      <ul class="space-y-5">
        <li class="flex items-start gap-4">
          <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-leaf to-pine flex items-center justify-center text-lg shrink-0">📍</div>
          <div><p class="font-semibold text-pine dark:text-cream mb-0.5">Alamat</p><p class="text-sm text-pine/70 dark:text-cream/70 leading-relaxed"><?php echo esc($alamat); ?></p></div>
        </li>
        <li class="flex items-start gap-4">
          <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-600 to-pine flex items-center justify-center text-lg shrink-0">📞</div>
          <div><p class="font-semibold text-pine dark:text-cream mb-0.5">Telepon</p><p class="text-sm text-pine/70 dark:text-cream/70"><?php echo esc($telepon); ?></p></div>
        </li>
        <li class="flex items-start gap-4">
          <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-teal-600 to-pine flex items-center justify-center text-lg shrink-0">✉️</div>
          <div><p class="font-semibold text-pine dark:text-cream mb-0.5">Email</p><p class="text-sm text-pine/70 dark:text-cream/70"><?php echo esc($email); ?></p></div>
        </li>
        <li class="flex items-start gap-4">
          <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-brass flex items-center justify-center text-lg shrink-0">🕐</div>
          <div><p class="font-semibold text-pine dark:text-cream mb-0.5">Jam Operasional</p><p class="text-sm text-pine/70 dark:text-cream/70 leading-relaxed"><?php echo nl2br(esc($jamOp)); ?></p></div>
        </li>
      </ul>

      <div class="mt-10">
        <p class="text-xs font-semibold tracking-widest text-leaf dark:text-brass-light uppercase mb-4">Sosial Media</p>
        <div class="flex flex-wrap gap-3">
          <a href="<?php echo esc(setting('ig_url') ?? '#'); ?>" class="inline-flex items-center gap-2 bg-cream-deep dark:bg-pine text-pine dark:text-cream border border-pine/10 dark:border-cream/10 text-sm font-medium px-5 py-2.5 rounded-full hover:border-brass hover:text-brass transition" target="_blank" rel="noopener">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
            Instagram
          </a>
          <a href="<?php echo esc(setting('yt_url') ?? '#'); ?>" class="inline-flex items-center gap-2 bg-cream-deep dark:bg-pine text-pine dark:text-cream border border-pine/10 dark:border-cream/10 text-sm font-medium px-5 py-2.5 rounded-full hover:border-brass hover:text-brass transition" target="_blank" rel="noopener">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
            YouTube
          </a>
          <a href="<?php echo esc(setting('tiktok_url') ?? '#'); ?>" class="inline-flex items-center gap-2 bg-cream-deep dark:bg-pine text-pine dark:text-cream border border-pine/10 dark:border-cream/10 text-sm font-medium px-5 py-2.5 rounded-full hover:border-brass hover:text-brass transition" target="_blank" rel="noopener">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
            TikTok
          </a>
          <a href="<?php echo esc(setting('fb_url') ?? '#'); ?>" class="inline-flex items-center gap-2 bg-cream-deep dark:bg-pine text-pine dark:text-cream border border-pine/10 dark:border-cream/10 text-sm font-medium px-5 py-2.5 rounded-full hover:border-brass hover:text-brass transition" target="_blank" rel="noopener">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            Facebook
          </a>
        </div>
      </div>
    </div>

    <!-- Form -->
    <div class="reveal">
      <div class="bg-cream-deep dark:bg-pine rounded-[1.5rem] p-8 ring-1 ring-pine/10 dark:ring-cream/10">
        <p class="text-xs font-semibold tracking-widest text-leaf dark:text-brass-light uppercase mb-4">Kirim Pesan</p>
        <h3 class="font-serif text-2xl text-pine dark:text-cream mb-6">Ada yang ingin ditanyakan?</h3>
        <?php echo $formMsg; ?>
        <form method="POST" action="kontak.php" class="space-y-5">
          <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf']; ?>">
          <!-- Honeypot: hidden field to catch bots -->
          <div style="position:absolute;left:-9999px" aria-hidden="true">
            <input type="text" name="website_url" tabindex="-1" autocomplete="off">
          </div>
          <div>
            <label class="block text-xs font-semibold text-pine/60 dark:text-cream/60 uppercase tracking-wide mb-1.5">Nama Lengkap *</label>
            <input type="text" name="nama" required placeholder="Masukkan nama Anda" maxlength="100" value="<?php echo isset($_POST['nama']) ? esc($_POST['nama']) : ''; ?>" class="w-full bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 rounded-xl px-4 py-3 text-sm outline-none focus:border-brass transition placeholder:text-pine/30 dark:placeholder:text-cream/30">
          </div>
          <div>
            <label class="block text-xs font-semibold text-pine/60 dark:text-cream/60 uppercase tracking-wide mb-1.5">Email *</label>
            <input type="email" name="email" required placeholder="email@contoh.com" maxlength="150" value="<?php echo isset($_POST['email']) ? esc($_POST['email']) : ''; ?>" class="w-full bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 rounded-xl px-4 py-3 text-sm outline-none focus:border-brass transition placeholder:text-pine/30 dark:placeholder:text-cream/30">
          </div>
          <div>
            <label class="block text-xs font-semibold text-pine/60 dark:text-cream/60 uppercase tracking-wide mb-1.5">Telepon</label>
            <input type="tel" name="telepon" placeholder="08xxxxxxxxxx" maxlength="30" value="<?php echo isset($_POST['telepon']) ? esc($_POST['telepon']) : ''; ?>" class="w-full bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 rounded-xl px-4 py-3 text-sm outline-none focus:border-brass transition placeholder:text-pine/30 dark:placeholder:text-cream/30">
          </div>
          <div>
            <label class="block text-xs font-semibold text-pine/60 dark:text-cream/60 uppercase tracking-wide mb-1.5">Subjek</label>
            <select name="subjek" class="w-full bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 rounded-xl px-4 py-3 text-sm outline-none focus:border-brass transition text-pine/70 dark:text-cream/70">
              <option value="">Pilih subjek...</option>
              <option <?php echo (isset($_POST['subjek']) && $_POST['subjek']==='Informasi PPDB') ? 'selected' : ''; ?>>Informasi PPDB</option>
              <option <?php echo (isset($_POST['subjek']) && $_POST['subjek']==='Informasi Umum') ? 'selected' : ''; ?>>Informasi Umum</option>
              <option <?php echo (isset($_POST['subjek']) && $_POST['subjek']==='Kerja Sama') ? 'selected' : ''; ?>>Kerja Sama</option>
              <option <?php echo (isset($_POST['subjek']) && $_POST['subjek']==='Lainnya') ? 'selected' : ''; ?>>Lainnya</option>
            </select>
          </div>
          <div>
            <label class="block text-xs font-semibold text-pine/60 dark:text-cream/60 uppercase tracking-wide mb-1.5">Pesan *</label>
            <textarea name="pesan" required rows="4" maxlength="5000" placeholder="Tulis pesan Anda..." class="w-full bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 rounded-xl px-4 py-3 text-sm outline-none focus:border-brass transition placeholder:text-pine/30 dark:placeholder:text-cream/30 resize-none"><?php echo isset($_POST['pesan']) ? esc($_POST['pesan']) : ''; ?></textarea>
          </div>
          <button type="submit" class="w-full bg-brass text-pine-deep font-semibold px-6 py-3.5 rounded-full hover:bg-brass-light transition text-sm">Kirim Pesan</button>
        </form>
      </div>
    </div>
  </div>
</section>

<!-- Peta -->
<section class="relative z-10 bg-cream-deep dark:bg-pine">
  <div class="max-w-6xl mx-auto px-5 py-20 sm:py-28">
    <div class="reveal text-center max-w-2xl mx-auto mb-12">
      <p class="text-xs font-semibold tracking-widest text-leaf dark:text-brass-light uppercase mb-4">Lokasi</p>
      <h2 class="font-serif text-3xl sm:text-4xl text-pine dark:text-cream">Temukan kami di peta.</h2>
    </div>
    <div class="reveal rounded-2xl overflow-hidden ring-1 ring-pine/10 dark:ring-cream/10 aspect-video">
      <iframe src="<?php echo esc($mapsEmbed); ?>" width="100%" height="100%" style="border:0" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="relative z-10 max-w-6xl mx-auto px-5 py-20 sm:py-28">
  <div class="rounded-[2rem] bg-gradient-to-br from-leaf to-pine text-cream px-8 sm:px-14 py-14 text-center relative overflow-hidden reveal">
    <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full bg-brass/20 blur-3xl"></div>
    <h2 class="font-serif text-3xl sm:text-5xl leading-tight max-w-2xl mx-auto">Kunjungi kami langsung.</h2>
    <p class="text-cream/80 mt-5 max-w-lg mx-auto">Kami tunggu kunjungan Anda di SMA Putra Persada Batam. Pendaftaran dibuka setiap hari kerja.</p>
    <a href="ppdb.php" class="inline-block mt-8 bg-brass text-pine-deep font-semibold px-8 py-3.5 rounded-full hover:bg-brass-light transition">Info PPDB</a>
  </div>
</section>

<?php $inlineJS = "wireHeaderFooter('kontak.php');"; ?>
<?php include __DIR__ . '/includes/foot.php'; ?>
