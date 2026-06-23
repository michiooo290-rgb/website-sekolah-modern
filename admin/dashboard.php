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

// Time-based greeting (server time)
$h = (int)date('G');
if     ($h < 11) { $greet = 'Selamat pagi';   }
elseif ($h < 15) { $greet = 'Selamat siang';  }
elseif ($h < 19) { $greet = 'Selamat sore';   }
else             { $greet = 'Selamat malam';  }

$pageTitle = 'Dashboard';
include 'admin_head.php';
?>

<!-- ============ Welcome banner ============ -->
<div class="admin-card relative overflow-hidden rounded-2xl bg-pine-deep text-cream p-6 sm:p-8 mb-8">
  <div class="absolute -top-20 -right-16 w-72 h-72 bg-brass/20 rounded-full blur-3xl"></div>
  <div class="absolute -bottom-24 -left-10 w-72 h-72 bg-leaf/25 rounded-full blur-3xl"></div>
  <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
    <div>
      <p class="text-brass-light text-sm font-semibold tracking-wide mb-1"><?php echo $greet; ?>,</p>
      <h2 class="font-serif text-2xl sm:text-3xl font-bold mb-2"><?php echo esc(admin_name()); ?> 👋</h2>
      <p class="text-cream/65 text-sm max-w-lg">Berikut ringkasan aktivitas website sekolah hari ini. Kelola konten dengan mudah dari satu tempat.</p>
    </div>
    <div class="shrink-0 flex items-center gap-3">
      <?php if ($stats['pesan_baru'] > 0): ?>
        <a href="pesan_masuk.php" class="btn-action inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-brass text-pine-deep font-semibold text-sm shadow-lg shadow-brass/20">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
          <?php echo $stats['pesan_baru']; ?> pesan baru
        </a>
      <?php else: ?>
        <a href="kelola_berita.php" class="btn-action inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-brass text-pine-deep font-semibold text-sm shadow-lg shadow-brass/20">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/></svg>
          Tulis Berita
        </a>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- ============ Stat Cards ============ -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 mb-8">
  <?php
  $cards = [
    ['label' => 'Berita',         'value' => $stats['berita'], 'icon' => 'news',  'color' => 'brass', 'href' => 'kelola_berita.php'],
    ['label' => 'Guru & Staf',    'value' => $stats['guru'],   'icon' => 'users', 'color' => 'leaf',  'href' => 'kelola_guru.php'],
    ['label' => 'Ekstrakurikuler','value' => $stats['ekskul'], 'icon' => 'star',  'color' => 'brass', 'href' => 'kelola_ekskul.php'],
    ['label' => 'Pesan Masuk',    'value' => $stats['pesan'],  'icon' => 'mail',  'color' => 'leaf',  'href' => 'pesan_masuk.php', 'badge' => $stats['pesan_baru']],
  ];
  $icons = [
    'news' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z"/>',
    'users'=> '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>',
    'star' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>',
    'mail' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>',
  ];
  foreach ($cards as $i => $c): ?>
    <a href="<?php echo $c['href']; ?>"
       class="admin-card group relative overflow-hidden bg-white/60 dark:bg-pine/40 backdrop-blur-sm rounded-2xl ring-1 ring-pine/8 dark:ring-cream/8 p-5 sm:p-6 block"
       style="animation-delay: <?php echo $i * 80; ?>ms">
      <div class="absolute top-0 left-0 w-full h-1 bg-<?php echo $c['color']; ?>/70"></div>
      <div class="flex items-start justify-between mb-4">
        <div class="w-11 h-11 rounded-xl bg-<?php echo $c['color']; ?>/10 dark:bg-<?php echo $c['color']; ?>/15
                    flex items-center justify-center group-hover:scale-110 transition-transform">
          <svg class="w-5 h-5 text-<?php echo $c['color']; ?> dark:text-<?php echo $c['color']; ?>-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <?php echo $icons[$c['icon']]; ?>
          </svg>
        </div>
        <?php if (!empty($c['badge']) && $c['badge'] > 0): ?>
          <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">+<?php echo $c['badge']; ?></span>
        <?php else: ?>
          <svg class="w-4 h-4 text-pine/20 dark:text-cream/20 group-hover:text-<?php echo $c['color']; ?> group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
        <?php endif; ?>
      </div>
      <p class="font-serif text-3xl font-bold mb-1"><?php echo $c['value']; ?></p>
      <p class="text-sm text-pine/60 dark:text-cream/60"><?php echo $c['label']; ?></p>
    </a>
  <?php endforeach; ?>
</div>

<!-- ============ Quick actions ============ -->
<div class="admin-card bg-white/60 dark:bg-pine/40 backdrop-blur-sm rounded-2xl ring-1 ring-pine/8 dark:ring-cream/8 p-6 mb-8">
  <h3 class="font-serif text-lg font-bold mb-5">Akses Cepat</h3>
  <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
    <?php
    $quick = [
      ['href'=>'kelola_berita.php',     'label'=>'Tulis Berita',   'd'=>'M12 4.5v15m7.5-7.5h-15'],
      ['href'=>'kelola_guru.php',       'label'=>'Tambah Guru',    'd'=>'M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM3 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 019.374 21c-2.331 0-4.512-.645-6.374-1.766z'],
      ['href'=>'kelola_ekskul.php',     'label'=>'Ekskul',         'd'=>'M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z'],
      ['href'=>'kelola_ppdb.php',       'label'=>'PPDB',           'd'=>'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z'],
      ['href'=>'kelola_pengaturan.php', 'label'=>'Pengaturan',     'd'=>'M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 010 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 010-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28z'],
    ];
    foreach ($quick as $q): ?>
      <a href="<?php echo $q['href']; ?>"
         class="btn-action group flex flex-col items-center gap-2 p-4 rounded-xl bg-cream dark:bg-pine-deep ring-1 ring-pine/8 dark:ring-cream/8 hover:ring-brass/40 text-center">
        <span class="w-10 h-10 rounded-lg bg-brass/10 dark:bg-brass/15 flex items-center justify-center text-brass dark:text-brass-light group-hover:bg-brass group-hover:text-pine-deep transition">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="<?php echo $q['d']; ?>"/></svg>
        </span>
        <span class="text-xs font-semibold leading-tight"><?php echo $q['label']; ?></span>
      </a>
    <?php endforeach; ?>
  </div>
</div>

<div class="grid lg:grid-cols-2 gap-6">
  <!-- Berita Terbaru -->
  <div class="admin-card bg-white/60 dark:bg-pine/40 backdrop-blur-sm rounded-2xl ring-1 ring-pine/8 dark:ring-cream/8 p-6">
    <div class="flex items-center justify-between mb-5">
      <h3 class="font-serif text-lg font-bold flex items-center gap-2">
        <span class="w-8 h-8 rounded-lg bg-brass/10 dark:bg-brass/15 flex items-center justify-center text-brass dark:text-brass-light">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z"/></svg>
        </span>
        Berita Terbaru
      </h3>
      <a href="kelola_berita.php" class="text-xs font-semibold text-leaf dark:text-brass-light hover:underline">Kelola →</a>
    </div>
    <?php if (empty($beritaTerbaru)): ?>
      <div class="py-10 text-center">
        <p class="text-sm text-pine/50 dark:text-cream/50">Belum ada berita.</p>
        <a href="kelola_berita.php" class="inline-block mt-3 text-xs font-semibold text-brass hover:underline">+ Tulis berita pertama</a>
      </div>
    <?php else: ?>
      <div class="divide-y divide-pine/8 dark:divide-cream/8">
        <?php foreach ($beritaTerbaru as $b): ?>
          <div class="table-row py-3 px-2 -mx-2 rounded-lg flex items-center justify-between gap-4">
            <div class="min-w-0">
              <p class="text-sm font-semibold truncate"><?php echo esc($b['judul']); ?></p>
              <p class="text-xs text-pine/50 dark:text-cream/50">
                <span class="inline-block px-2 py-0.5 rounded-full bg-leaf/10 text-leaf dark:text-brass-light text-[10px] font-semibold mr-1"><?php echo esc($b['kategori']); ?></span>
                <?php echo tglPendek($b['tanggal']); ?>
              </p>
            </div>
            <span class="flex items-center gap-1 text-xs text-pine/40 dark:text-cream/40 shrink-0">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              <?php echo $b['dilihat']; ?>
            </span>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

  <!-- Pesan Terbaru -->
  <div class="admin-card bg-white/60 dark:bg-pine/40 backdrop-blur-sm rounded-2xl ring-1 ring-pine/8 dark:ring-cream/8 p-6">
    <div class="flex items-center justify-between mb-5">
      <h3 class="font-serif text-lg font-bold flex items-center gap-2">
        <span class="w-8 h-8 rounded-lg bg-leaf/10 dark:bg-leaf/15 flex items-center justify-center text-leaf dark:text-brass-light">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
        </span>
        Pesan Terbaru
      </h3>
      <a href="pesan_masuk.php" class="text-xs font-semibold text-leaf dark:text-brass-light hover:underline">Lihat semua →</a>
    </div>
    <?php if (empty($pesanTerbaru)): ?>
      <div class="py-10 text-center">
        <p class="text-sm text-pine/50 dark:text-cream/50">Belum ada pesan.</p>
      </div>
    <?php else: ?>
      <div class="divide-y divide-pine/8 dark:divide-cream/8">
        <?php foreach ($pesanTerbaru as $p): ?>
          <a href="pesan_masuk.php?id=<?php echo $p['id']; ?>" class="table-row py-3 px-2 -mx-2 rounded-lg flex items-center justify-between gap-4 group">
            <div class="flex items-center gap-3 min-w-0">
              <span class="w-9 h-9 rounded-full bg-brass/15 dark:bg-brass/20 flex items-center justify-center text-brass dark:text-brass-light font-bold text-xs shrink-0">
                <?php echo strtoupper(substr($p['nama'], 0, 1)); ?>
              </span>
              <div class="min-w-0">
                <p class="text-sm font-semibold truncate flex items-center gap-2">
                  <?php echo esc($p['nama']); ?>
                  <?php if (!$p['dibaca']): ?><span class="w-2 h-2 rounded-full bg-brass shrink-0" title="Belum dibaca"></span><?php endif; ?>
                </p>
                <p class="text-xs text-pine/50 dark:text-cream/50 truncate"><?php echo esc($p['subjek'] ?: 'Tanpa subjek'); ?></p>
              </div>
            </div>
            <span class="text-xs text-pine/40 dark:text-cream/40 shrink-0"><?php echo tglPendek($p['tanggal']); ?></span>
          </a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php include 'admin_foot.php'; ?>
