<?php
/**
 * Admin panel HTML head + sidebar + opening main
 * Variables expected: $pageTitle (string)
 */
$namaSekolah = setting('nama_sekolah') ?? 'SMA Putra Persada Batam';
$fullTitle   = $pageTitle . ' — Admin ' . $namaSekolah;
$currentPage = basename($_SERVER['PHP_SELF']);

$navGroups = [
    'Menu Utama' => [
        ['file'=>'dashboard.php', 'icon'=>'grid', 'label'=>'Dashboard'],
    ],
    'Konten' => [
        ['file'=>'kelola_berita.php',   'icon'=>'news',  'label'=>'Berita'],
        ['file'=>'kelola_guru.php',     'icon'=>'users', 'label'=>'Guru & Staf'],
        ['file'=>'kelola_ekskul.php',   'icon'=>'star',  'label'=>'Ekstrakurikuler'],
        ['file'=>'kelola_visimisi.php', 'icon'=>'eye',   'label'=>'Visi & Misi'],
        ['file'=>'kelola_tentang.php',  'icon'=>'info',  'label'=>'Tentang'],
        ['file'=>'kelola_ppdb.php',     'icon'=>'doc',   'label'=>'PPDB'],
    ],
    'Komunikasi' => [
        ['file'=>'pesan_masuk.php', 'icon'=>'mail', 'label'=>'Pesan Masuk'],
    ],
    'Sistem' => [
        ['file'=>'kelola_pengaturan.php', 'icon'=>'cog', 'label'=>'Pengaturan'],
    ],
];

$iconPaths = [
    'grid' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zm0 9.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zm0 9.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>',
    'cog'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 010 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 010-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>',
    'news' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z"/>',
    'users'=> '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>',
    'star' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>',
    'eye'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>',
    'info' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>',
    'doc'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>',
    'mail' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>',
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo esc($fullTitle); ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4.3.0/dist/index.global.js" integrity="sha384-nWTzRTCY/9V4Bo352ehygr1c4cnst4XN6lMR3fipakEQrhVpc0hEM5Dii3Amz0sT" crossorigin="anonymous"></script>
  <style type="text/tailwindcss">
    @custom-variant dark (&:where(.dark, .dark *));
    @theme {
      --color-pine: #0E3B2E;
      --color-pine-deep: #08291F;
      --color-leaf: #2F7D52;
      --color-cream: #F7F3E9;
      --color-cream-deep: #EFE8D6;
      --color-brass: #C9A227;
      --color-brass-light: #E0BC45;
      --font-serif: 'Fraunces', Georgia, serif;
      --font-sans: 'Plus Jakarta Sans', system-ui, sans-serif;
    }
  </style>
  <style>
    /* Admin-specific transitions */
    .sidebar-link{position:relative;transition:background .2s ease,color .2s ease}
    .sidebar-link .nav-ic{transition:background .2s ease,color .2s ease}
    .sidebar-link:hover{background:rgba(247,243,233,.06)}
    .sidebar-link:hover .nav-ic{background:rgba(247,243,233,.10)}
    .sidebar-link.active{background:linear-gradient(90deg,rgba(201,162,39,.18),rgba(201,162,39,.04));color:#E0BC45}
    .sidebar-link.active .nav-ic{background:#C9A227;color:#08291F}
    .sidebar-link.active::before{content:"";position:absolute;left:0;top:.5rem;bottom:.5rem;width:3px;border-radius:0 4px 4px 0;background:#E0BC45}
    .admin-card{transition:transform .25s ease,box-shadow .25s ease}
    .admin-card:hover{transform:translateY(-2px);box-shadow:0 8px 25px -5px rgba(14,59,46,.1)}
    .dark .admin-card:hover{box-shadow:0 8px 25px -5px rgba(0,0,0,.3)}
    .btn-action{transition:all .2s ease}
    .btn-action:hover{transform:translateY(-1px)}
    .btn-action:active{transform:translateY(0) scale(.98)}
    .fade-in{opacity:0;transform:translateY(12px);animation:fadeUp .4s ease forwards}
    @keyframes fadeUp{to{opacity:1;transform:none}}
    .sidebar-enter{animation:slideIn .3s ease forwards}
    @keyframes slideIn{from{opacity:0;transform:translateX(-16px)}to{opacity:1;transform:none}}
    /* Mobile sidebar */
    #sidebar{transition:transform .3s ease}
    #sidebar.closed{transform:translateX(-100%)}
    @media(min-width:1024px){#sidebar{transform:none!important}}
    /* Table rows */
    .table-row{transition:background .15s ease}
    .table-row:hover{background:rgba(201,162,39,.04)}
    .dark .table-row:hover{background:rgba(201,162,39,.06)}

    /* ── Global content polish (applies to every admin page) ── */
    main input[type=text],main input[type=email],main input[type=url],
    main input[type=password],main input[type=date],main input[type=number],
    main input[type=search],main input[type=tel],main textarea,main select{
      box-shadow:inset 0 1px 2px rgba(14,59,46,.04);
    }
    main input:hover:not(:focus),main textarea:hover:not(:focus),main select:hover:not(:focus){
      border-color:rgba(201,162,39,.55)!important;
    }
    main input:focus,main textarea:focus,main select:focus{
      box-shadow:0 0 0 4px rgba(201,162,39,.16)!important;
    }
    main select{
      appearance:none;-webkit-appearance:none;
      background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%232F7D52'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2.2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
      background-repeat:no-repeat;background-position:right .85rem center;background-size:1.05rem;padding-right:2.5rem!important;
    }
    main input[type=file]{cursor:pointer}
    button,.btn-action,a[role=button]{cursor:pointer}
    /* Card elevation */
    .admin-card{box-shadow:0 1px 2px rgba(14,59,46,.04),0 6px 16px -10px rgba(14,59,46,.12)}
    /* Table header band */
    table thead tr{background:rgba(14,59,46,.03)}
  </style>
</head>
<body class="min-h-dvh antialiased bg-cream dark:bg-pine-deep text-pine dark:text-cream font-sans">
  <!-- Mobile overlay -->
  <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-40 hidden lg:hidden" onclick="toggleSidebar()"></div>

  <!-- Sidebar -->
  <aside id="sidebar" class="fixed top-0 left-0 bottom-0 w-64 bg-pine-deep text-cream z-50 flex flex-col sidebar-enter
                               lg:translate-x-0 -translate-x-full">
    <!-- Brand -->
    <div class="p-5 border-b border-cream/10">
      <a href="dashboard.php" class="flex items-center gap-3 group">
        <img src="../assets/img/logo.jpeg" alt="Logo" class="w-10 h-10 rounded-lg object-cover ring-2 ring-brass/30
                                                    group-hover:ring-brass transition duration-300">
        <div>
          <h1 class="font-serif text-base font-bold leading-tight group-hover:text-brass-light transition"><?php echo esc($namaSekolah); ?></h1>
          <p class="text-[10px] tracking-[0.2em] text-cream/50 uppercase">Panel Admin</p>
        </div>
      </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-5">
      <?php
      $unread = db()->query('SELECT COUNT(*) FROM pesan_kontak WHERE dibaca = 0')->fetchColumn();
      foreach ($navGroups as $groupLabel => $items): ?>
        <div>
          <p class="px-3 mb-1.5 text-[10px] font-bold tracking-[0.18em] text-cream/35 uppercase"><?php echo $groupLabel; ?></p>
          <div class="space-y-0.5">
            <?php foreach ($items as $item):
              $isActive = $currentPage === $item['file']; ?>
              <a href="<?php echo $item['file']; ?>"
                 class="sidebar-link flex items-center gap-3 px-2.5 py-2 rounded-xl text-sm
                        <?php echo $isActive ? 'active font-semibold' : 'text-cream/70 hover:text-cream'; ?>">
                <span class="nav-ic flex items-center justify-center w-8 h-8 rounded-lg shrink-0 <?php echo $isActive ? '' : 'bg-cream/5'; ?>">
                  <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <?php echo $iconPaths[$item['icon']]; ?>
                  </svg>
                </span>
                <span class="truncate"><?php echo $item['label']; ?></span>
                <?php if ($item['file'] === 'pesan_masuk.php' && $unread > 0): ?>
                  <span class="ml-auto bg-brass text-pine-deep text-[10px] font-bold px-2 py-0.5 rounded-full shrink-0"><?php echo $unread; ?></span>
                <?php endif; ?>
              </a>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </nav>

    <!-- Footer -->
    <div class="p-3 border-t border-cream/10">
      <!-- User card -->
      <div class="flex items-center gap-3 p-2.5 mb-2.5 rounded-xl bg-cream/5 ring-1 ring-cream/8">
        <div class="w-9 h-9 rounded-full bg-brass flex items-center justify-center text-pine-deep font-bold text-sm shrink-0">
          <?php echo strtoupper(substr(admin_name(), 0, 1)); ?>
        </div>
        <div class="text-xs min-w-0">
          <p class="font-semibold truncate"><?php echo esc(admin_name()); ?></p>
          <p class="text-brass-light/80 capitalize truncate"><?php echo esc($_SESSION['admin_role'] ?? 'admin'); ?></p>
        </div>
      </div>
      <!-- Action buttons -->
      <div class="grid grid-cols-2 gap-2">
        <a href="../index.php" target="_blank"
           class="flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg text-xs font-semibold
                  bg-cream/5 text-cream/80 ring-1 ring-cream/10 hover:bg-cream/10 hover:text-cream transition">
          <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
          Website
        </a>
        <a href="logout.php"
           class="flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg text-xs font-semibold
                  bg-red-500/10 text-red-300 ring-1 ring-red-500/20 hover:bg-red-500/20 hover:text-red-200 transition">
          <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
          Keluar
        </a>
      </div>
    </div>
  </aside>

  <!-- Main content -->
  <div class="lg:ml-64 min-h-dvh flex flex-col">
    <!-- Top bar -->
    <header class="sticky top-0 z-30 bg-cream/80 dark:bg-pine-deep/80 backdrop-blur-md border-b border-pine/8 dark:border-cream/8">
      <div class="flex items-center justify-between px-5 lg:px-8 h-16">
        <div class="flex items-center gap-4">
          <button onclick="toggleSidebar()" class="lg:hidden p-2 -ml-2 rounded-lg hover:bg-pine/5 dark:hover:bg-cream/5 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
          </button>
          <h2 class="font-serif text-lg font-bold"><?php echo esc($pageTitle); ?></h2>
        </div>
        <div class="flex items-center gap-3 text-sm text-pine/60 dark:text-cream/60">
          <span><?php echo date('l, d M Y'); ?></span>
        </div>
      </div>
    </header>

    <!-- Page content -->
    <main class="flex-1 p-5 lg:p-8 fade-in">
      <?php
      $success = get_flash('success');
      $error   = get_flash('error');
      if ($success): ?>
        <div class="mb-6 flex items-start gap-3 p-4 rounded-xl bg-leaf/10 text-leaf dark:bg-leaf/20 dark:text-leaf ring-1 ring-leaf/20 text-sm fade-in" id="flash-msg">
          <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          <span><?php echo esc($success); ?></span>
          <button onclick="this.parentElement.remove()" class="ml-auto -mr-1 p-1 rounded hover:bg-leaf/10 transition">&times;</button>
        </div>
      <?php endif;
      if ($error): ?>
        <div class="mb-6 flex items-start gap-3 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300 ring-1 ring-red-200 dark:ring-red-800/40 text-sm fade-in" id="flash-msg">
          <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          <span><?php echo esc($error); ?></span>
          <button onclick="this.parentElement.remove()" class="ml-auto -mr-1 p-1 rounded hover:bg-red-100 transition">&times;</button>
        </div>
      <?php endif; ?>
