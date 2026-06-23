<?php
require_once 'auth.php';
require_role('superadmin');

$pdo = db();

// Handle POST — save settings
if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf()) {
    $keys = [
        'akreditasi','jumlah_peserta_didik','jumlah_pengajar',
        'alamat','telepon','email','jam_operasional',
        'fb_url','ig_url','yt_url','tiktok_url',
        'teks_sambutan'
    ];
    $stmt = $pdo->prepare('INSERT INTO pengaturan (kunci, nilai) VALUES (?, ?) ON DUPLICATE KEY UPDATE nilai = VALUES(nilai)');
    foreach ($keys as $k) {
        $val = trim($_POST[$k] ?? '');
        $stmt->execute([$k, $val]);
    }
    flash('success', 'Pengaturan berhasil disimpan.');
    header('Location: kelola_pengaturan.php');
    exit;
}

// Load current values
$keys = [
    'akreditasi','jumlah_peserta_didik','jumlah_pengajar',
    'alamat','telepon','email','jam_operasional',
    'fb_url','ig_url','yt_url','tiktok_url',
    'teks_sambutan'
];
$vals = [];
foreach ($keys as $k) {
    $vals[$k] = setting($k) ?? '';
}

$pageTitle = 'Kelola Pengaturan';
include 'admin_head.php';
?>

<form method="POST" class="max-w-3xl space-y-8">
  <?php echo csrf_field(); ?>

  <!-- Informasi Sekolah -->
  <div class="admin-card bg-white/60 dark:bg-pine/40 backdrop-blur-sm rounded-2xl ring-1 ring-pine/8 dark:ring-cream/8 p-6">
    <h3 class="font-serif text-lg font-bold mb-5 flex items-center gap-2">
      <span class="w-2 h-2 rounded-full bg-brass"></span> Informasi Sekolah
    </h3>
    <div class="grid sm:grid-cols-2 gap-5">
      <div>
        <label class="block text-sm font-semibold mb-1.5">Akreditasi</label>
        <input type="text" name="akreditasi" value="<?php echo esc($vals['akreditasi']); ?>"
               class="w-full px-4 py-2.5 rounded-xl bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 focus:border-brass focus:ring-2 focus:ring-brass/20 outline-none transition text-sm">
      </div>
      <div>
        <label class="block text-sm font-semibold mb-1.5">Jumlah Peserta Didik</label>
        <input type="text" name="jumlah_peserta_didik" value="<?php echo esc($vals['jumlah_peserta_didik']); ?>"
               class="w-full px-4 py-2.5 rounded-xl bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 focus:border-brass focus:ring-2 focus:ring-brass/20 outline-none transition text-sm">
      </div>
      <div>
        <label class="block text-sm font-semibold mb-1.5">Jumlah Pengajar</label>
        <input type="text" name="jumlah_pengajar" value="<?php echo esc($vals['jumlah_pengajar']); ?>"
               class="w-full px-4 py-2.5 rounded-xl bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 focus:border-brass focus:ring-2 focus:ring-brass/20 outline-none transition text-sm">
      </div>
      <div>
        <label class="block text-sm font-semibold mb-1.5">Jam Operasional</label>
        <input type="text" name="jam_operasional" value="<?php echo esc($vals['jam_operasional']); ?>"
               class="w-full px-4 py-2.5 rounded-xl bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 focus:border-brass focus:ring-2 focus:ring-brass/20 outline-none transition text-sm">
      </div>
    </div>
  </div>

  <!-- Kontak -->
  <div class="admin-card bg-white/60 dark:bg-pine/40 backdrop-blur-sm rounded-2xl ring-1 ring-pine/8 dark:ring-cream/8 p-6">
    <h3 class="font-serif text-lg font-bold mb-5 flex items-center gap-2">
      <span class="w-2 h-2 rounded-full bg-leaf"></span> Kontak
    </h3>
    <div class="space-y-5">
      <div>
        <label class="block text-sm font-semibold mb-1.5">Alamat</label>
        <textarea name="alamat" rows="2"
                  class="w-full px-4 py-2.5 rounded-xl bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 focus:border-brass focus:ring-2 focus:ring-brass/20 outline-none transition text-sm"><?php echo esc($vals['alamat']); ?></textarea>
      </div>
      <div class="grid sm:grid-cols-2 gap-5">
        <div>
          <label class="block text-sm font-semibold mb-1.5">Telepon</label>
          <input type="text" name="telepon" value="<?php echo esc($vals['telepon']); ?>"
                 class="w-full px-4 py-2.5 rounded-xl bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 focus:border-brass focus:ring-2 focus:ring-brass/20 outline-none transition text-sm">
        </div>
        <div>
          <label class="block text-sm font-semibold mb-1.5">Email</label>
          <input type="email" name="email" value="<?php echo esc($vals['email']); ?>"
                 class="w-full px-4 py-2.5 rounded-xl bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 focus:border-brass focus:ring-2 focus:ring-brass/20 outline-none transition text-sm">
        </div>
      </div>
    </div>
  </div>

  <!-- Sosial Media -->
  <div class="admin-card bg-white/60 dark:bg-pine/40 backdrop-blur-sm rounded-2xl ring-1 ring-pine/8 dark:ring-cream/8 p-6">
    <h3 class="font-serif text-lg font-bold mb-5 flex items-center gap-2">
      <span class="w-2 h-2 rounded-full bg-brass"></span> Sosial Media
    </h3>
    <div class="grid sm:grid-cols-2 gap-5">
      <?php
      $sosmed = [
        'fb_url'     => 'Facebook',
        'ig_url'     => 'Instagram',
        'yt_url'     => 'YouTube',
        'tiktok_url' => 'TikTok',
      ];
      foreach ($sosmed as $k => $label): ?>
        <div>
          <label class="block text-sm font-semibold mb-1.5"><?php echo $label; ?></label>
          <input type="url" name="<?php echo $k; ?>" value="<?php echo esc($vals[$k]); ?>"
                 placeholder="https://..."
                 class="w-full px-4 py-2.5 rounded-xl bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 focus:border-brass focus:ring-2 focus:ring-brass/20 outline-none transition text-sm">
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Teks Sambutan -->
  <div class="admin-card bg-white/60 dark:bg-pine/40 backdrop-blur-sm rounded-2xl ring-1 ring-pine/8 dark:ring-cream/8 p-6">
    <h3 class="font-serif text-lg font-bold mb-5 flex items-center gap-2">
      <span class="w-2 h-2 rounded-full bg-leaf"></span> Teks Sambutan
    </h3>
    <textarea name="teks_sambutan" rows="5"
              class="w-full px-4 py-2.5 rounded-xl bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 focus:border-brass focus:ring-2 focus:ring-brass/20 outline-none transition text-sm"><?php echo esc($vals['teks_sambutan']); ?></textarea>
  </div>

  <!-- Submit -->
  <div class="flex justify-end">
    <button type="submit"
            class="btn-action px-8 py-3 rounded-xl bg-brass hover:bg-brass-light text-pine-deep font-semibold text-sm shadow-md shadow-brass/20 hover:shadow-lg transition">
      Simpan Pengaturan
    </button>
  </div>
</form>

<?php include 'admin_foot.php'; ?>
