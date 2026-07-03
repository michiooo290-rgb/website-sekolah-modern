<?php
require_once 'auth.php';
require_login();

$pdo = db();

// Mark as read
if (isset($_GET['baca'])) {
    $id = (int)$_GET['baca'];
    $pdo->prepare('UPDATE pesan_kontak SET dibaca=1 WHERE id=?')->execute([$id]);
    header('Location: pesan_masuk.php?id=' . $id);
    exit;
}

// Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf()) {
    if (($_POST['action'] ?? '') === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        $pdo->prepare('DELETE FROM pesan_kontak WHERE id=?')->execute([$id]);
        flash('success', 'Pesan berhasil dihapus.');
        header('Location: pesan_masuk.php');
        exit;
    }
}

// Mark all read
if (isset($_GET['baca_semua'])) {
    $pdo->exec('UPDATE pesan_kontak SET dibaca=1 WHERE dibaca=0');
    flash('success', 'Semua pesan ditandai sudah dibaca.');
    header('Location: pesan_masuk.php');
    exit;
}

// Detail view
$detail = null;
if (isset($_GET['id'])) {
    $detail = $pdo->prepare('SELECT * FROM pesan_kontak WHERE id=?');
    $detail->execute([(int)$_GET['id']]);
    $detail = $detail->fetch();
    if ($detail && !$detail['dibaca']) {
        $pdo->prepare('UPDATE pesan_kontak SET dibaca=1 WHERE id=?')->execute([$detail['id']]);
        $detail['dibaca'] = 1;
    }
}

// ── List: pencarian, filter, pagination ─────────────────────────────
$q      = isset($_GET['q']) ? trim($_GET['q']) : '';
$filter = (isset($_GET['filter']) && $_GET['filter'] === 'unread') ? 'unread' : '';
$perPage = 15;
$hal     = isset($_GET['hal']) ? max(1, (int)$_GET['hal']) : 1;

// Hitung total keseluruhan & belum dibaca (tanpa filter, untuk badge & pills)
$total  = (int)$pdo->query('SELECT COUNT(*) FROM pesan_kontak')->fetchColumn();
$unread = (int)$pdo->query('SELECT COUNT(*) FROM pesan_kontak WHERE dibaca=0')->fetchColumn();

// Susun WHERE dinamis untuk daftar
$where  = [];
$params = [];
if ($q !== '') {
    $where[] = '(nama LIKE ? OR email LIKE ? OR subjek LIKE ?)';
    $like = '%' . $q . '%';
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
}
if ($filter === 'unread') {
    $where[] = 'dibaca = 0';
}
$whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';
$isFiltering = ($q !== '' || $filter === 'unread');

// Total baris yang cocok (untuk pagination)
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM pesan_kontak $whereSql");
$countStmt->execute($params);
$totalRows = (int)$countStmt->fetchColumn();

$totalPages = max(1, (int)ceil($totalRows / $perPage));
if ($hal > $totalPages) $hal = $totalPages;
$offset = ($hal - 1) * $perPage;

// Ambil baris halaman ini (LIMIT/OFFSET int aman karena sudah di-cast)
$listStmt = $pdo->prepare("SELECT id, nama, email, subjek, tanggal, dibaca FROM pesan_kontak $whereSql ORDER BY tanggal DESC LIMIT $perPage OFFSET $offset");
$listStmt->execute($params);
$rows = $listStmt->fetchAll();

// Helper untuk membangun URL yang mempertahankan q + filter
$pageUrl = function (array $override = []) use ($q, $filter) {
    $base = ['q' => $q !== '' ? $q : null, 'filter' => $filter !== '' ? $filter : null];
    $merged = array_merge($base, $override);
    $merged = array_filter($merged, fn($v) => $v !== null && $v !== '');
    return 'pesan_masuk.php' . ($merged ? ('?' . http_build_query($merged)) : '');
};

$pageTitle = 'Pesan Masuk';
include 'admin_head.php';
?>

<?php if ($detail): ?>
<!-- Detail View -->
<div class="max-w-2xl">
  <a href="pesan_masuk.php" class="inline-flex items-center gap-1 text-sm text-pine/60 dark:text-cream/60 hover:text-pine dark:hover:text-cream transition mb-6">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    Kembali ke daftar pesan
  </a>

  <div class="admin-card bg-white/60 dark:bg-pine/40 backdrop-blur-sm rounded-2xl ring-1 ring-pine/8 dark:ring-cream/8 p-6">
    <div class="flex items-start justify-between mb-6">
      <div>
        <h3 class="font-serif text-xl font-bold mb-1"><?php echo esc($detail['subjek'] ?: 'Tanpa Subjek'); ?></h3>
        <p class="text-sm text-pine/60 dark:text-cream/60"><?php echo tglIndo($detail['tanggal']); ?></p>
      </div>
      <form method="POST" data-confirm="Hapus pesan ini?">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="id" value="<?php echo $detail['id']; ?>">
        <button class="btn-action px-3 py-1.5 rounded-lg text-xs font-semibold text-red-600 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 transition">Hapus</button>
      </form>
    </div>

    <div class="grid sm:grid-cols-2 gap-4 mb-6 p-4 rounded-xl bg-cream-deep/50 dark:bg-pine-deep/50">
      <div>
        <p class="text-xs font-semibold text-pine/50 dark:text-cream/50 uppercase tracking-wider mb-1">Nama</p>
        <p class="text-sm font-semibold"><?php echo esc($detail['nama']); ?></p>
      </div>
      <div>
        <p class="text-xs font-semibold text-pine/50 dark:text-cream/50 uppercase tracking-wider mb-1">Email</p>
        <a href="mailto:<?php echo esc($detail['email']); ?>" class="text-sm text-leaf dark:text-brass-light hover:underline"><?php echo esc($detail['email']); ?></a>
      </div>
      <?php if ($detail['telepon']): ?>
      <div>
        <p class="text-xs font-semibold text-pine/50 dark:text-cream/50 uppercase tracking-wider mb-1">Telepon</p>
        <a href="tel:<?php echo esc($detail['telepon']); ?>" class="text-sm text-leaf dark:text-brass-light hover:underline"><?php echo esc($detail['telepon']); ?></a>
      </div>
      <?php endif; ?>
    </div>

    <div>
      <p class="text-xs font-semibold text-pine/50 dark:text-cream/50 uppercase tracking-wider mb-2">Pesan</p>
      <div class="text-sm leading-relaxed whitespace-pre-wrap"><?php echo esc($detail['pesan']); ?></div>
    </div>
  </div>
</div>

<?php else: ?>
<!-- List View -->
<div class="flex flex-wrap items-center justify-between gap-3 mb-5">
  <div class="flex items-center gap-3">
    <p class="text-sm text-pine/60 dark:text-cream/60">
      <?php if ($isFiltering): ?>
        <?php echo $totalRows; ?> dari <?php echo $total; ?> pesan
      <?php else: ?>
        <?php echo $total; ?> pesan
      <?php endif; ?>
    </p>
    <?php if ($unread > 0): ?>
      <span class="bg-brass text-pine-deep text-[10px] font-bold px-2 py-0.5 rounded-full"><?php echo $unread; ?> belum dibaca</span>
    <?php endif; ?>
  </div>
  <?php if ($unread > 0): ?>
    <a href="pesan_masuk.php?baca_semua=1" class="text-xs font-semibold text-leaf dark:text-brass-light hover:underline">Tandai semua dibaca</a>
  <?php endif; ?>
</div>

<!-- Pencarian + Filter -->
<div class="flex flex-wrap items-center gap-3 mb-5">
  <form method="GET" class="flex items-center gap-2 flex-1 min-w-[240px]">
    <?php if ($filter !== ''): ?><input type="hidden" name="filter" value="<?php echo esc($filter); ?>"><?php endif; ?>
    <div class="relative flex-1">
      <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-pine/40 dark:text-cream/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/></svg>
      <input type="text" name="q" value="<?php echo esc($q); ?>" placeholder="Cari nama, email, atau subjek..." class="w-full pl-9 pr-3 py-2 rounded-xl text-sm bg-white/60 dark:bg-pine/40 ring-1 ring-pine/10 dark:ring-cream/10 focus:ring-2 focus:ring-brass outline-none">
    </div>
    <button class="px-4 py-2 rounded-xl text-sm font-semibold bg-pine text-cream hover:bg-pine-deep transition">Cari</button>
    <?php if ($q !== ''): ?>
      <a href="<?php echo esc($pageUrl(['q' => null, 'hal' => null])); ?>" class="px-3 py-2 rounded-xl text-sm text-pine/60 dark:text-cream/60 hover:text-pine dark:hover:text-cream transition">Reset</a>
    <?php endif; ?>
  </form>
  <div class="flex items-center gap-1 bg-white/60 dark:bg-pine/40 rounded-xl ring-1 ring-pine/10 dark:ring-cream/10 p-1">
    <a href="<?php echo esc($pageUrl(['filter' => null, 'hal' => null])); ?>" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition <?php echo $filter === '' ? 'bg-brass text-pine-deep' : 'text-pine/60 dark:text-cream/60 hover:text-pine dark:hover:text-cream'; ?>">Semua</a>
    <a href="<?php echo esc($pageUrl(['filter' => 'unread', 'hal' => null])); ?>" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition <?php echo $filter === 'unread' ? 'bg-brass text-pine-deep' : 'text-pine/60 dark:text-cream/60 hover:text-pine dark:hover:text-cream'; ?>">Belum dibaca<?php if ($unread > 0): ?> · <?php echo $unread; ?><?php endif; ?></a>
  </div>
</div>

<div class="admin-card bg-white/60 dark:bg-pine/40 backdrop-blur-sm rounded-2xl ring-1 ring-pine/8 dark:ring-cream/8 overflow-hidden">
  <?php if (empty($rows)): ?>
    <div class="p-12 text-center">
      <svg class="w-12 h-12 text-pine/20 dark:text-cream/20 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
      <?php if ($isFiltering): ?>
        <p class="text-sm text-pine/50 dark:text-cream/50">Tidak ada pesan yang cocok dengan pencarian/filter.</p>
        <a href="pesan_masuk.php" class="inline-block mt-3 text-xs font-semibold text-leaf dark:text-brass-light hover:underline">Tampilkan semua pesan</a>
      <?php else: ?>
        <p class="text-sm text-pine/50 dark:text-cream/50">Belum ada pesan masuk.</p>
      <?php endif; ?>
    </div>
  <?php else: ?>
    <div class="divide-y divide-pine/5 dark:divide-cream/5">
      <?php foreach ($rows as $r): ?>
        <a href="pesan_masuk.php?id=<?php echo $r['id']; ?>&baca=<?php echo $r['id']; ?>"
           class="table-row flex items-center gap-4 px-5 py-4 group <?php echo !$r['dibaca'] ? 'bg-brass/5 dark:bg-brass/5' : ''; ?>">
          <div class="w-9 h-9 rounded-full <?php echo !$r['dibaca'] ? 'bg-brass/20 text-brass' : 'bg-pine/5 dark:bg-cream/10 text-pine/40 dark:text-cream/40'; ?> flex items-center justify-center text-xs font-bold shrink-0">
            <?php echo strtoupper(substr($r['nama'], 0, 1)); ?>
          </div>
          <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 mb-0.5">
              <?php if (!$r['dibaca']): ?><span class="w-2 h-2 rounded-full bg-brass shrink-0"></span><?php endif; ?>
              <span class="text-sm font-semibold truncate <?php echo !$r['dibaca'] ? '' : 'text-pine/70 dark:text-cream/70'; ?>"><?php echo esc($r['nama']); ?></span>
            </div>
            <p class="text-xs text-pine/50 dark:text-cream/50 truncate"><?php echo esc($r['subjek'] ?: 'Tanpa subjek'); ?></p>
          </div>
          <span class="text-xs text-pine/40 dark:text-cream/40 shrink-0"><?php echo tglPendek($r['tanggal']); ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<?php if ($totalPages > 1): ?>
<nav class="flex items-center justify-center gap-1 mt-6" aria-label="Navigasi halaman">
  <?php if ($hal > 1): ?>
    <a href="<?php echo esc($pageUrl(['hal' => $hal - 1])); ?>" class="px-3 py-1.5 rounded-lg text-sm font-semibold bg-white/60 dark:bg-pine/40 ring-1 ring-pine/10 dark:ring-cream/10 hover:bg-brass hover:text-pine-deep transition">← Sebelumnya</a>
  <?php else: ?>
    <span class="px-3 py-1.5 rounded-lg text-sm font-semibold text-pine/30 dark:text-cream/30 cursor-not-allowed">← Sebelumnya</span>
  <?php endif; ?>

  <?php
    $start = max(1, $hal - 2);
    $end   = min($totalPages, $hal + 2);
    if ($start > 1): ?>
      <a href="<?php echo esc($pageUrl(['hal' => 1])); ?>" class="w-9 h-9 flex items-center justify-center rounded-lg text-sm font-semibold bg-white/60 dark:bg-pine/40 ring-1 ring-pine/10 dark:ring-cream/10 hover:bg-brass hover:text-pine-deep transition">1</a>
      <?php if ($start > 2): ?><span class="px-1 text-pine/40 dark:text-cream/40">…</span><?php endif; ?>
    <?php endif; ?>

  <?php for ($p = $start; $p <= $end; $p++): ?>
    <?php if ($p == $hal): ?>
      <span class="w-9 h-9 flex items-center justify-center rounded-lg text-sm font-bold bg-brass text-pine-deep"><?php echo $p; ?></span>
    <?php else: ?>
      <a href="<?php echo esc($pageUrl(['hal' => $p])); ?>" class="w-9 h-9 flex items-center justify-center rounded-lg text-sm font-semibold bg-white/60 dark:bg-pine/40 ring-1 ring-pine/10 dark:ring-cream/10 hover:bg-brass hover:text-pine-deep transition"><?php echo $p; ?></a>
    <?php endif; ?>
  <?php endfor; ?>

  <?php if ($end < $totalPages): ?>
    <?php if ($end < $totalPages - 1): ?><span class="px-1 text-pine/40 dark:text-cream/40">…</span><?php endif; ?>
    <a href="<?php echo esc($pageUrl(['hal' => $totalPages])); ?>" class="w-9 h-9 flex items-center justify-center rounded-lg text-sm font-semibold bg-white/60 dark:bg-pine/40 ring-1 ring-pine/10 dark:ring-cream/10 hover:bg-brass hover:text-pine-deep transition"><?php echo $totalPages; ?></a>
  <?php endif; ?>

  <?php if ($hal < $totalPages): ?>
    <a href="<?php echo esc($pageUrl(['hal' => $hal + 1])); ?>" class="px-3 py-1.5 rounded-lg text-sm font-semibold bg-white/60 dark:bg-pine/40 ring-1 ring-pine/10 dark:ring-cream/10 hover:bg-brass hover:text-pine-deep transition">Berikutnya →</a>
  <?php else: ?>
    <span class="px-3 py-1.5 rounded-lg text-sm font-semibold text-pine/30 dark:text-cream/30 cursor-not-allowed">Berikutnya →</span>
  <?php endif; ?>
</nav>
<?php endif; ?>
<?php endif; ?>

<?php include 'admin_foot.php'; ?>
