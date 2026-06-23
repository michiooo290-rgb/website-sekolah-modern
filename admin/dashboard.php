<?php
require_once 'auth.php';
require_login();

$stats = [
    'berita' => db()->query('SELECT COUNT(*) FROM berita')->fetchColumn(),
    'guru'   => db()->query('SELECT COUNT(*) FROM guru')->fetchColumn(),
    'ekskul' => db()->query('SELECT COUNT(*) FROM ekstrakurikuler')->fetchColumn(),
    'pesan'  => db()->query('SELECT COUNT(*) FROM pesan_kontak')->fetchColumn(),
    'pesan_baru' => db()->query('SELECT COUNT(*) FROM pesan_kontak WHERE dibaca = 0')->fetchColumn(),
];

$beritaTerbaru = db()->query('SELECT judul, kategori, tanggal, dilihat FROM berita ORDER BY tanggal DESC LIMIT 5')->fetchAll();
$pesanTerbaru  = db()->query('SELECT id, nama, subjek, tanggal, dibaca FROM pesan_kontak ORDER BY tanggal DESC LIMIT 5')->fetchAll();

$pageTitle = 'Dashboard';
include 'admin_head.php';
?>

<!-- Stat Cards -->
<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
  <?php
  $cards = [
    ['label' => 'Berita',       'value' => $stats['berita'],    'icon' => 'news', 'color' => 'brass'],
    ['label' => 'Guru & Staf',  'value' => $stats['guru'],      'icon' => 'users','color' => 'leaf'],
    ['label' => 'Ekstrakurikuler','value' => $stats['ekskul'],  'icon' => 'star', 'color' => 'brass'],
    ['label' => 'Pesan Masuk',  'value' => $stats['pesan'],     'icon' => 'mail', 'color' => 'leaf', 'badge' => $stats['pesan_baru']],
  ];
  foreach ($cards as $i => $c): ?>
    <div class="admin-card bg-white/60 dark:bg-pine/40 backdrop-blur-sm rounded-2xl ring-1 ring-pine/8 dark:ring-cream/8 p-6"
         style="animation-delay: <?php echo $i * 80; ?>ms">
      <div class="flex items-start justify-between mb-4">
        <div class="w-11 h-11 rounded-xl bg-<?php echo $c['color']; ?>/10 dark:bg-<?php echo $c['color']; ?>/15
                    flex items-center justify-center">
          <svg class="w-5 h-5 text-<?php echo $c['color']; ?> dark:text-<?php echo $c['color']; ?>-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <?php
            $icons = [
              'news' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z"/>',
              'users'=> '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>',
              'star' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>',
              'mail' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>',
            ];
            echo $icons[$c['icon']]; ?>
          </svg>
        </div>
        <?php if (!empty($c['badge']) && $c['badge'] > 0): ?>
          <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full"><?php echo $c['badge']; ?> baru</span>
        <?php endif; ?>
      </div>
      <p class="font-serif text-3xl font-bold mb-1"><?php echo $c['value']; ?></p>
      <p class="text-sm text-pine/60 dark:text-cream/60"><?php echo $c['label']; ?></p>
    </div>
  <?php endforeach; ?>
</div>

<div class="grid lg:grid-cols-2 gap-6">
  <!-- Berita Terbaru -->
  <div class="admin-card bg-white/60 dark:bg-pine/40 backdrop-blur-sm rounded-2xl ring-1 ring-pine/8 dark:ring-cream/8 p-6">
    <div class="flex items-center justify-between mb-5">
      <h3 class="font-serif text-lg font-bold">Berita Terbaru</h3>
      <a href="kelola_berita.php" class="text-xs font-semibold text-leaf dark:text-brass-light hover:underline">Kelola →</a>
    </div>
    <?php if (empty($beritaTerbaru)): ?>
      <p class="text-sm text-pine/50 dark:text-cream/50 py-4 text-center">Belum ada berita.</p>
    <?php else: ?>
      <div class="divide-y divide-pine/8 dark:divide-cream/8">
        <?php foreach ($beritaTerbaru as $b): ?>
          <div class="table-row py-3 flex items-center justify-between gap-4">
            <div class="min-w-0">
              <p class="text-sm font-semibold truncate"><?php echo esc($b['judul']); ?></p>
              <p class="text-xs text-pine/50 dark:text-cream/50"><?php echo esc($b['kategori']); ?> · <?php echo tglPendek($b['tanggal']); ?></p>
            </div>
            <span class="text-xs text-pine/40 dark:text-cream/40 shrink-0"><?php echo $b['dilihat']; ?>×</span>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

  <!-- Pesan Terbaru -->
  <div class="admin-card bg-white/60 dark:bg-pine/40 backdrop-blur-sm rounded-2xl ring-1 ring-pine/8 dark:ring-cream/8 p-6">
    <div class="flex items-center justify-between mb-5">
      <h3 class="font-serif text-lg font-bold">Pesan Terbaru</h3>
      <a href="pesan_masuk.php" class="text-xs font-semibold text-leaf dark:text-brass-light hover:underline">Lihat semua →</a>
    </div>
    <?php if (empty($pesanTerbaru)): ?>
      <p class="text-sm text-pine/50 dark:text-cream/50 py-4 text-center">Belum ada pesan.</p>
    <?php else: ?>
      <div class="divide-y divide-pine/8 dark:divide-cream/8">
        <?php foreach ($pesanTerbaru as $p): ?>
          <a href="pesan_masuk.php?id=<?php echo $p['id']; ?>" class="table-row py-3 flex items-center justify-between gap-4 group">
            <div class="min-w-0">
              <p class="text-sm font-semibold truncate flex items-center gap-2">
                <?php if (!$p['dibaca']): ?><span class="w-2 h-2 rounded-full bg-brass shrink-0"></span><?php endif; ?>
                <?php echo esc($p['nama']); ?>
              </p>
              <p class="text-xs text-pine/50 dark:text-cream/50 truncate"><?php echo esc($p['subjek'] ?: 'Tanpa subjek'); ?></p>
            </div>
            <span class="text-xs text-pine/40 dark:text-cream/40 shrink-0"><?php echo tglPendek($p['tanggal']); ?></span>
          </a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php include 'admin_foot.php'; ?>
