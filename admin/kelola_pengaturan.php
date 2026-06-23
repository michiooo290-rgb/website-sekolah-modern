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

<!-- Page intro -->
<div class="flex items-start gap-3 mb-7">
  <span class="w-11 h-11 rounded-xl bg-brass/12 text-brass flex items-center justify-center shrink-0">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 010 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 010-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
  </span>
  <div>
    <h2 class="font-serif text-2xl font-bold leading-tight">Pengaturan Website</h2>
    <p class="text-sm text-pine/60 dark:text-cream/60 mt-0.5">Kelola informasi sekolah, kontak, sosial media, dan teks sambutan yang tampil di halaman publik.</p>
  </div>
</div>

<form method="POST" class="max-w-3xl space-y-6">
  <?php echo csrf_field(); ?>

  <!-- Informasi Sekolah -->
  <div class="admin-card bg-white/60 dark:bg-pine/40 backdrop-blur-sm rounded-2xl ring-1 ring-pine/8 dark:ring-cream/8 p-6 sm:p-7">
    <div class="flex items-start gap-3 mb-6 pb-5 border-b border-pine/8 dark:border-cream/8">
      <span class="w-10 h-10 rounded-xl bg-brass/12 text-brass flex items-center justify-center shrink-0">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21a.75.75 0 01.75.75V21"/></svg>
      </span>
      <div>
        <h3 class="font-serif text-lg font-bold leading-tight">Informasi Sekolah</h3>
        <p class="text-xs text-pine/55 dark:text-cream/55 mt-0.5">Data umum sekolah untuk ditampilkan ke publik.</p>
      </div>
    </div>
    <div class="grid sm:grid-cols-2 gap-5">
      <div>
        <label class="block text-sm font-semibold mb-1.5">Akreditasi</label>
        <input type="text" name="akreditasi" value="<?php echo esc($vals['akreditasi']); ?>" placeholder="contoh: A"
               class="w-full px-4 py-2.5 rounded-xl bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 focus:border-brass focus:ring-2 focus:ring-brass/20 outline-none transition text-sm">
      </div>
      <div>
        <label class="block text-sm font-semibold mb-1.5">Jam Operasional</label>
        <input type="text" name="jam_operasional" value="<?php echo esc($vals['jam_operasional']); ?>" placeholder="contoh: Senin–Jumat, 07.00–15.00"
               class="w-full px-4 py-2.5 rounded-xl bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 focus:border-brass focus:ring-2 focus:ring-brass/20 outline-none transition text-sm">
      </div>
      <div>
        <label class="block text-sm font-semibold mb-1.5">Jumlah Peserta Didik</label>
        <input type="text" name="jumlah_peserta_didik" value="<?php echo esc($vals['jumlah_peserta_didik']); ?>" placeholder="contoh: 720"
               class="w-full px-4 py-2.5 rounded-xl bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 focus:border-brass focus:ring-2 focus:ring-brass/20 outline-none transition text-sm">
      </div>
      <div>
        <label class="block text-sm font-semibold mb-1.5">Jumlah Pengajar</label>
        <input type="text" name="jumlah_pengajar" value="<?php echo esc($vals['jumlah_pengajar']); ?>" placeholder="contoh: 48"
               class="w-full px-4 py-2.5 rounded-xl bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 focus:border-brass focus:ring-2 focus:ring-brass/20 outline-none transition text-sm">
      </div>
    </div>
  </div>

  <!-- Kontak -->
  <div class="admin-card bg-white/60 dark:bg-pine/40 backdrop-blur-sm rounded-2xl ring-1 ring-pine/8 dark:ring-cream/8 p-6 sm:p-7">
    <div class="flex items-start gap-3 mb-6 pb-5 border-b border-pine/8 dark:border-cream/8">
      <span class="w-10 h-10 rounded-xl bg-leaf/12 text-leaf flex items-center justify-center shrink-0">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
      </span>
      <div>
        <h3 class="font-serif text-lg font-bold leading-tight">Kontak</h3>
        <p class="text-xs text-pine/55 dark:text-cream/55 mt-0.5">Alamat dan informasi kontak resmi sekolah.</p>
      </div>
    </div>
    <div class="space-y-5">
      <div>
        <label class="block text-sm font-semibold mb-1.5">Alamat</label>
        <div class="relative">
          <span class="absolute left-3.5 top-3 text-pine/35 dark:text-cream/35 pointer-events-none">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
          </span>
          <textarea name="alamat" rows="2" placeholder="Jl. ..."
                    class="w-full pl-11 pr-4 py-2.5 rounded-xl bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 focus:border-brass focus:ring-2 focus:ring-brass/20 outline-none transition text-sm"><?php echo esc($vals['alamat']); ?></textarea>
        </div>
      </div>
      <div class="grid sm:grid-cols-2 gap-5">
        <div>
          <label class="block text-sm font-semibold mb-1.5">Telepon</label>
          <div class="relative">
            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-pine/35 dark:text-cream/35 pointer-events-none">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
            </span>
            <input type="text" name="telepon" value="<?php echo esc($vals['telepon']); ?>" placeholder="0778-..."
                   class="w-full pl-11 pr-4 py-2.5 rounded-xl bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 focus:border-brass focus:ring-2 focus:ring-brass/20 outline-none transition text-sm">
          </div>
        </div>
        <div>
          <label class="block text-sm font-semibold mb-1.5">Email</label>
          <div class="relative">
            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-pine/35 dark:text-cream/35 pointer-events-none">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
            </span>
            <input type="email" name="email" value="<?php echo esc($vals['email']); ?>" placeholder="info@sekolah.sch.id"
                   class="w-full pl-11 pr-4 py-2.5 rounded-xl bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 focus:border-brass focus:ring-2 focus:ring-brass/20 outline-none transition text-sm">
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Sosial Media -->
  <div class="admin-card bg-white/60 dark:bg-pine/40 backdrop-blur-sm rounded-2xl ring-1 ring-pine/8 dark:ring-cream/8 p-6 sm:p-7">
    <div class="flex items-start gap-3 mb-6 pb-5 border-b border-pine/8 dark:border-cream/8">
      <span class="w-10 h-10 rounded-xl bg-brass/12 text-brass flex items-center justify-center shrink-0">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z"/></svg>
      </span>
      <div>
        <h3 class="font-serif text-lg font-bold leading-tight">Sosial Media</h3>
        <p class="text-xs text-pine/55 dark:text-cream/55 mt-0.5">Tautan akun resmi sekolah. Kosongkan bila tidak ada.</p>
      </div>
    </div>
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
          <div class="relative">
            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-pine/35 dark:text-cream/35 pointer-events-none">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244"/></svg>
            </span>
            <input type="url" name="<?php echo $k; ?>" value="<?php echo esc($vals[$k]); ?>" placeholder="https://..."
                   class="w-full pl-11 pr-4 py-2.5 rounded-xl bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 focus:border-brass focus:ring-2 focus:ring-brass/20 outline-none transition text-sm">
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Teks Sambutan -->
  <div class="admin-card bg-white/60 dark:bg-pine/40 backdrop-blur-sm rounded-2xl ring-1 ring-pine/8 dark:ring-cream/8 p-6 sm:p-7">
    <div class="flex items-start gap-3 mb-6 pb-5 border-b border-pine/8 dark:border-cream/8">
      <span class="w-10 h-10 rounded-xl bg-leaf/12 text-leaf flex items-center justify-center shrink-0">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/></svg>
      </span>
      <div>
        <h3 class="font-serif text-lg font-bold leading-tight">Teks Sambutan</h3>
        <p class="text-xs text-pine/55 dark:text-cream/55 mt-0.5">Sambutan singkat yang muncul di beranda website.</p>
      </div>
    </div>
    <textarea name="teks_sambutan" rows="5" placeholder="Selamat datang di ..."
              class="w-full px-4 py-2.5 rounded-xl bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 focus:border-brass focus:ring-2 focus:ring-brass/20 outline-none transition text-sm leading-relaxed"><?php echo esc($vals['teks_sambutan']); ?></textarea>
  </div>

  <!-- Sticky save bar -->
  <div class="sticky bottom-4 z-10 flex items-center justify-between gap-4 p-3.5 sm:px-5 rounded-2xl bg-white/85 dark:bg-pine/70 backdrop-blur ring-1 ring-pine/10 dark:ring-cream/10 shadow-lg shadow-pine/10">
    <p class="text-xs text-pine/55 dark:text-cream/55 hidden sm:flex items-center gap-1.5">
      <svg class="w-4 h-4 text-leaf" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      Perubahan langsung tampil di website setelah disimpan.
    </p>
    <button type="submit"
            class="btn-action inline-flex items-center gap-2 px-7 py-3 rounded-xl bg-brass hover:bg-brass-light text-pine-deep font-bold text-sm shadow-md shadow-brass/25 hover:shadow-lg transition ml-auto sm:ml-0">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.5 12.75l6 6 9-13.5"/></svg>
      Simpan Pengaturan
    </button>
  </div>
</form>

<?php include 'admin_foot.php'; ?>
