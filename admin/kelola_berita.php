<?php
require_once 'auth.php';
require_login();

$pdo   = db();
$edit  = null;
$mode  = 'list';

// ── Handle actions ──────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf()) {
    $action = $_POST['action'] ?? '';

    // HAPUS FOTO SAJA
    if ($action === 'hapus_foto') {
        $id  = (int)($_POST['id'] ?? 0);
        $row = $pdo->prepare('SELECT gambar FROM berita WHERE id=?');
        $row->execute([$id]);
        $row = $row->fetch();
        if ($row && $row['gambar']) {
            $path = __DIR__ . '/uploads/berita/' . $row['gambar'];
            if (file_exists($path)) unlink($path);
            $pdo->prepare('UPDATE berita SET gambar=NULL WHERE id=?')->execute([$id]);
            flash('success', 'Foto berhasil dihapus.');
        }
        header('Location: kelola_berita.php?edit=' . $id);
        exit;
    }

    // CREATE / UPDATE
    if ($action === 'save') {
        $id       = $_POST['id'] ?? null;
        $judul    = trim($_POST['judul'] ?? '');
        $kategori = trim($_POST['kategori'] ?? '');
        $isi      = $_POST['isi'] ?? '';
        $tanggal  = $_POST['tanggal'] ?? date('Y-m-d');
        $slug     = make_slug($judul);
        // Ensure slug is unique
        $baseSlug = $slug;
        $suffix   = 0;
        while (true) {
            $check = $pdo->prepare('SELECT COUNT(*) FROM berita WHERE slug=?' . ($id ? ' AND id!=?' : ''));
            $params = [$slug];
            if ($id) $params[] = $id;
            $check->execute($params);
            if ($check->fetchColumn() == 0) break;
            $suffix++;
            $slug = $baseSlug . '-' . $suffix;
        }

        if ($judul === '') {
            flash('error', 'Judul wajib diisi.');
        } else {
            // Handle image upload
            $gambar = $_POST['gambar_lama'] ?? null;
            if (!empty($_FILES['gambar']['name'])) {
                $uploaded = upload_gambar($_FILES['gambar'], __DIR__ . '/uploads/berita');
                if ($uploaded) {
                    // Delete old image
                    if ($gambar && file_exists(__DIR__ . '/uploads/berita/' . $gambar)) {
                        unlink(__DIR__ . '/uploads/berita/' . $gambar);
                    }
                    $gambar = $uploaded;
                } else {
                    flash('error', 'Format gambar tidak didukung atau ukuran terlalu besar (maks 5 MB).');
                }
            }

            if (!get_flash('error')) {
                if ($id) {
                    $stmt = $pdo->prepare('UPDATE berita SET judul=?, slug=?, kategori=?, isi=?, gambar=?, tanggal=? WHERE id=?');
                    $stmt->execute([$judul, $slug, $kategori, $isi, $gambar, $tanggal, $id]);
                    flash('success', 'Berita berhasil diperbarui.');
                } else {
                    $stmt = $pdo->prepare('INSERT INTO berita (judul, slug, kategori, isi, gambar, tanggal) VALUES (?,?,?,?,?,?)');
                    $stmt->execute([$judul, $slug, $kategori, $isi, $gambar, $tanggal]);
                    flash('success', 'Berita berhasil ditambahkan.');
                }
                header('Location: kelola_berita.php');
                exit;
            }
        }
        $mode = 'form';
    }

    // DELETE
    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        // Get image filename before deleting
        $row = $pdo->prepare('SELECT gambar FROM berita WHERE id=?');
        $row->execute([$id]);
        $row = $row->fetch();
        if ($row && $row['gambar'] && file_exists(__DIR__ . '/uploads/berita/' . $row['gambar'])) {
            unlink(__DIR__ . '/uploads/berita/' . $row['gambar']);
        }
        $pdo->prepare('DELETE FROM berita WHERE id=?')->execute([$id]);
        flash('success', 'Berita berhasil dihapus.');
        header('Location: kelola_berita.php');
        exit;
    }
}

// Edit mode
if (isset($_GET['edit'])) {
    $edit = $pdo->prepare('SELECT * FROM berita WHERE id=?');
    $edit->execute([(int)$_GET['edit']]);
    $edit = $edit->fetch();
    $mode = $edit ? 'form' : 'list';
}

// New mode
if (isset($_GET['new'])) {
    $mode = 'form';
}

$pageTitle = $mode === 'form' ? ($edit ? 'Edit Berita' : 'Tambah Berita') : 'Kelola Berita';
include 'admin_head.php';

if ($mode === 'form'):
?>

<?php if ($edit && $edit['gambar']): ?>
<!-- Form terpisah untuk hapus foto (diletakkan di LUAR form utama agar HTML valid: form tidak boleh bersarang) -->
<form method="POST" id="form-hapus-foto" data-confirm="Hapus foto ini? Foto akan dihapus permanen." class="hidden">
  <?php echo csrf_field(); ?>
  <input type="hidden" name="action" value="hapus_foto">
  <input type="hidden" name="id" value="<?php echo $edit['id']; ?>">
</form>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" class="max-w-3xl space-y-6">
  <?php echo csrf_field(); ?>
  <input type="hidden" name="action" value="save">
  <?php if ($edit): ?><input type="hidden" name="id" value="<?php echo $edit['id']; ?>"><?php endif; ?>
  <?php if ($edit && $edit['gambar']): ?><input type="hidden" name="gambar_lama" value="<?php echo esc($edit['gambar']); ?>"><?php endif; ?>

  <div class="admin-card bg-white/60 dark:bg-pine/40 backdrop-blur-sm rounded-2xl ring-1 ring-pine/8 dark:ring-cream/8 p-6 space-y-5">
    <div>
      <label class="block text-sm font-semibold mb-1.5">Judul</label>
      <input type="text" name="judul" required value="<?php echo esc($edit['judul'] ?? ''); ?>"
             class="w-full px-4 py-2.5 rounded-xl bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 focus:border-brass focus:ring-2 focus:ring-brass/20 outline-none transition text-sm">
    </div>

    <div class="grid sm:grid-cols-2 gap-5">
      <div>
        <label class="block text-sm font-semibold mb-1.5">Kategori</label>
        <select name="kategori"
                class="w-full px-4 py-2.5 rounded-xl bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 focus:border-brass focus:ring-2 focus:ring-brass/20 outline-none transition text-sm">
          <?php foreach (['Prestasi','Kegiatan','Keagamaan','Beasiswa','Lingkungan','Pengumuman'] as $kat): ?>
            <option value="<?php echo $kat; ?>" <?php echo (($edit['kategori'] ?? '') === $kat) ? 'selected' : ''; ?>><?php echo $kat; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <label class="block text-sm font-semibold mb-1.5">Tanggal</label>
        <input type="date" name="tanggal" value="<?php echo esc($edit['tanggal'] ?? date('Y-m-d')); ?>"
               class="w-full px-4 py-2.5 rounded-xl bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 focus:border-brass focus:ring-2 focus:ring-brass/20 outline-none transition text-sm">
      </div>
    </div>

    <div>
      <label class="block text-sm font-semibold mb-1.5">Gambar</label>
      <?php if (!empty($edit['gambar'])): ?>
        <div class="mb-3 flex items-start gap-3">
          <img src="uploads/berita/<?php echo esc($edit['gambar']); ?>" alt=""
               class="h-24 rounded-lg object-cover ring-1 ring-pine/10">
          <!-- Tombol Hapus Foto: submit ke form #form-hapus-foto (di luar form utama) -->
          <button type="submit" form="form-hapus-foto"
                  class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-red-600 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/30 transition">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            Hapus Foto
          </button>
        </div>
      <?php endif; ?>
      <input type="file" name="gambar" accept="image/*"
             class="w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-brass/10 file:text-brass file:font-semibold file:text-sm hover:file:bg-brass/20 transition">
      <p class="text-xs text-pine/50 dark:text-cream/50 mt-1">JPG, PNG, atau WebP. Maks 5 MB.</p>
    </div>

    <div>
      <label class="block text-sm font-semibold mb-1.5">Isi Berita</label>
      <div class="rte rounded-xl border border-pine/15 dark:border-cream/15 focus-within:border-brass focus-within:ring-2 focus-within:ring-brass/20 transition overflow-hidden">
        <div class="rte-toolbar flex flex-wrap items-center gap-1 px-2 py-1.5 border-b border-pine/10 dark:border-cream/10 bg-cream dark:bg-pine-deep">
          <button type="button" class="rte-btn" data-cmd="bold" title="Tebal" aria-label="Tebal"><b>B</b></button>
          <button type="button" class="rte-btn" data-cmd="italic" title="Miring" aria-label="Miring"><i>I</i></button>
          <span class="rte-sep"></span>
          <button type="button" class="rte-btn" data-cmd="formatBlock" data-val="h3" title="Judul bagian" aria-label="Judul bagian">H3</button>
          <button type="button" class="rte-btn" data-cmd="formatBlock" data-val="p" title="Paragraf biasa" aria-label="Paragraf biasa">¶</button>
          <span class="rte-sep"></span>
          <button type="button" class="rte-btn" data-cmd="insertUnorderedList" title="Daftar butir" aria-label="Daftar butir">• Butir</button>
          <button type="button" class="rte-btn" data-cmd="insertOrderedList" title="Daftar bernomor" aria-label="Daftar bernomor">1. Nomor</button>
          <span class="rte-sep"></span>
          <button type="button" class="rte-btn" data-cmd="createLink" title="Sisipkan tautan" aria-label="Sisipkan tautan">Tautan</button>
          <button type="button" class="rte-btn" data-cmd="removeFormat" title="Bersihkan format" aria-label="Bersihkan format">Bersihkan</button>
        </div>
        <div id="rte-editor" contenteditable="true" role="textbox" aria-multiline="true" aria-label="Isi berita"
             data-placeholder="Tulis isi berita di sini…"
             class="rte-editor min-h-[16rem] max-h-[34rem] overflow-y-auto px-4 py-3 bg-cream dark:bg-pine-deep text-pine dark:text-cream text-sm leading-relaxed outline-none"><?php echo sanitize_html($edit['isi'] ?? ''); ?></div>
      </div>
      <textarea name="isi" id="rte-source" class="hidden" aria-hidden="true"><?php echo esc($edit['isi'] ?? ''); ?></textarea>
      <p class="text-xs text-pine/50 dark:text-cream/50 mt-1">Gunakan tombol format di atas untuk menata teks — tidak perlu menulis kode HTML.</p>
    </div>
  </div>

  <div class="flex items-center justify-between">
    <a href="kelola_berita.php" class="text-sm text-pine/60 dark:text-cream/60 hover:text-pine dark:hover:text-cream transition">← Kembali</a>
    <button type="submit"
            class="btn-action px-8 py-3 rounded-xl bg-brass hover:bg-brass-light text-pine-deep font-semibold text-sm shadow-md shadow-brass/20 hover:shadow-lg transition">
      <?php echo $edit ? 'Perbarui' : 'Simpan'; ?>
    </button>
  </div>
</form>

<style>
  .rte-btn{min-width:2rem;height:2rem;padding:0 .55rem;display:inline-flex;align-items:center;justify-content:center;border-radius:.5rem;font-size:.8rem;font-weight:600;color:#0E3B2E;background:transparent;cursor:pointer;border:none;transition:background .15s ease;line-height:1}
  .dark .rte-btn{color:#F7F3E9}
  .rte-btn:hover{background:rgba(201,162,39,.18)}
  .rte-sep{width:1px;height:1.25rem;background:rgba(14,59,46,.15);margin:0 .15rem}
  .dark .rte-sep{background:rgba(247,243,233,.15)}
  .rte-editor h3{font-family:'Fraunces',Georgia,serif;font-weight:600;font-size:1.25rem;margin:.6em 0 .3em}
  .rte-editor p{margin:.5em 0}
  .rte-editor ul{list-style:disc;padding-left:1.5em;margin:.5em 0}
  .rte-editor ol{list-style:decimal;padding-left:1.5em;margin:.5em 0}
  .rte-editor li{margin:.25em 0}
  .rte-editor a{color:#2F7D52;text-decoration:underline}
  .dark .rte-editor a{color:#E0BC45}
  .rte-editor:empty::before{content:attr(data-placeholder);color:#9ca3af;pointer-events:none}
</style>
<script>
  (function () {
    var editor = document.getElementById('rte-editor');
    var source = document.getElementById('rte-source');
    if (!editor || !source) return;

    try { document.execCommand('defaultParagraphSeparator', false, 'p'); } catch (e) {}

    function sync() { source.value = editor.innerHTML.trim(); }

    document.querySelectorAll('.rte-toolbar .rte-btn').forEach(function (btn) {
      // mousedown + preventDefault agar seleksi teks di editor tidak hilang saat tombol diklik
      btn.addEventListener('mousedown', function (e) {
        e.preventDefault();
        editor.focus();
        var cmd = btn.getAttribute('data-cmd');
        var val = btn.getAttribute('data-val');
        if (cmd === 'createLink') {
          var url = prompt('Masukkan alamat tautan (URL):', 'https://');
          if (!url) return;
          document.execCommand('createLink', false, url);
        } else if (cmd === 'formatBlock') {
          document.execCommand('formatBlock', false, '<' + val + '>');
        } else {
          document.execCommand(cmd, false, null);
        }
        sync();
      });
    });

    editor.addEventListener('input', sync);
    editor.addEventListener('blur', sync);
    sync();

    var form = editor.closest('form');
    if (form) {
      form.addEventListener('submit', function (e) {
        sync();
        if (editor.textContent.trim() === '') {
          e.preventDefault();
          alert('Isi berita tidak boleh kosong.');
          editor.focus();
        }
      });
    }
  })();
</script>

<?php else: ?>

<?php
// ── Cari + filter + pagination ────────────────────
$q       = trim($_GET['q'] ?? '');
$kat     = trim($_GET['kat'] ?? '');
$perPage = 10;
$hal     = max(1, (int)($_GET['hal'] ?? 1));

$where  = [];
$params = [];
if ($q !== '') {
    $where[]  = '(judul LIKE ? OR slug LIKE ?)';
    $params[] = '%' . $q . '%';
    $params[] = '%' . $q . '%';
}
if ($kat !== '') {
    $where[]  = 'kategori = ?';
    $params[] = $kat;
}
$whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

$totalAll = (int) $pdo->query('SELECT COUNT(*) FROM berita')->fetchColumn();
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM berita $whereSql");
$countStmt->execute($params);
$totalRows  = (int) $countStmt->fetchColumn();
$totalPages = max(1, (int) ceil($totalRows / $perPage));
if ($hal > $totalPages) $hal = $totalPages;
$offset = ($hal - 1) * $perPage;

$listStmt = $pdo->prepare("SELECT * FROM berita $whereSql ORDER BY tanggal DESC LIMIT $perPage OFFSET $offset");
$listStmt->execute($params);
$rows = $listStmt->fetchAll();

$katList = $pdo->query('SELECT DISTINCT kategori FROM berita ORDER BY kategori')->fetchAll(PDO::FETCH_COLUMN);

$isFiltering = ($q !== '' || $kat !== '');
$baseQ = [];
if ($q !== '')   $baseQ['q']   = $q;
if ($kat !== '') $baseQ['kat'] = $kat;
$pageUrl = function ($p) use ($baseQ) {
    return 'kelola_berita.php?' . http_build_query(array_merge($baseQ, ['hal' => $p]));
};
?>

<!-- List mode -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5">
  <p class="text-sm text-pine/60 dark:text-cream/60">
    <span class="font-semibold text-pine dark:text-cream"><?php echo $totalRows; ?></span>
    <?php echo $isFiltering ? 'hasil ditemukan' : 'berita'; ?>
    <?php if ($isFiltering): ?><span class="text-pine/40 dark:text-cream/40">dari <?php echo $totalAll; ?> total</span><?php endif; ?>
  </p>
  <a href="kelola_berita.php?new=1"
     class="btn-action inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-brass hover:bg-brass-light text-pine-deep font-semibold text-sm shadow-md shadow-brass/20 hover:shadow-lg transition">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/></svg>
    Tambah Berita
  </a>
</div>

<!-- Cari + filter -->
<form method="GET" class="flex flex-col sm:flex-row gap-3 mb-6">
  <div class="relative flex-1">
    <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-pine/40 dark:text-cream/40 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/></svg>
    <input type="text" name="q" value="<?php echo esc($q); ?>" placeholder="Cari judul berita…"
           class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-white/60 dark:bg-pine/40 border border-pine/15 dark:border-cream/15 focus:border-brass focus:ring-2 focus:ring-brass/20 outline-none transition text-sm">
  </div>
  <select name="kat"
          class="px-4 py-2.5 rounded-xl bg-white/60 dark:bg-pine/40 border border-pine/15 dark:border-cream/15 focus:border-brass focus:ring-2 focus:ring-brass/20 outline-none transition text-sm">
    <option value="">Semua kategori</option>
    <?php foreach ($katList as $k): ?>
      <option value="<?php echo esc($k); ?>" <?php echo ($kat === $k) ? 'selected' : ''; ?>><?php echo esc($k); ?></option>
    <?php endforeach; ?>
  </select>
  <button type="submit" class="btn-action px-5 py-2.5 rounded-xl bg-pine/5 dark:bg-cream/10 hover:bg-brass/15 hover:text-brass font-semibold text-sm transition">Cari</button>
  <?php if ($isFiltering): ?>
    <a href="kelola_berita.php" class="btn-action px-5 py-2.5 rounded-xl text-sm font-semibold text-pine/60 dark:text-cream/60 hover:bg-pine/5 dark:hover:bg-cream/10 transition text-center">Reset</a>
  <?php endif; ?>
</form>

<div class="admin-card bg-white/60 dark:bg-pine/40 backdrop-blur-sm rounded-2xl ring-1 ring-pine/8 dark:ring-cream/8 overflow-hidden">
  <?php if (empty($rows)): ?>
    <div class="p-12 text-center">
      <div class="w-16 h-16 rounded-2xl bg-pine/5 dark:bg-cream/10 flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8 text-pine/25 dark:text-cream/25" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z"/></svg>
      </div>
      <?php if ($isFiltering): ?>
        <p class="text-sm font-semibold text-pine/70 dark:text-cream/70 mb-1">Tidak ada berita yang cocok</p>
        <p class="text-sm text-pine/50 dark:text-cream/50 mb-4">Coba kata kunci lain atau ubah filter kategori.</p>
        <a href="kelola_berita.php" class="btn-action inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-pine/5 dark:bg-cream/10 hover:bg-brass/15 hover:text-brass font-semibold text-sm transition">Reset pencarian</a>
      <?php else: ?>
        <p class="text-sm font-semibold text-pine/70 dark:text-cream/70 mb-1">Belum ada berita</p>
        <p class="text-sm text-pine/50 dark:text-cream/50 mb-4">Mulai bagikan kabar sekolah dengan menambahkan berita pertama.</p>
        <a href="kelola_berita.php?new=1" class="btn-action inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-brass hover:bg-brass-light text-pine-deep font-semibold text-sm shadow-md shadow-brass/20 hover:shadow-lg transition">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/></svg>
          Tambah Berita Pertama
        </a>
      <?php endif; ?>
    </div>
  <?php else: ?>
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-pine/10 dark:border-cream/10 text-left text-xs font-semibold text-pine/60 dark:text-cream/60 uppercase tracking-wider">
            <th class="px-5 py-3">Berita</th>
            <th class="px-5 py-3">Kategori</th>
            <th class="px-5 py-3">Tanggal</th>
            <th class="px-5 py-3 text-center">Dilihat</th>
            <th class="px-5 py-3 text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-pine/5 dark:divide-cream/5">
          <?php foreach ($rows as $r): ?>
            <tr class="table-row">
              <td class="px-5 py-3.5">
                <div class="flex items-center gap-3">
                  <?php if ($r['gambar']): ?>
                    <img src="uploads/berita/<?php echo esc($r['gambar']); ?>" alt="" class="w-12 h-12 rounded-lg object-cover ring-1 ring-pine/10 shrink-0">
                  <?php else: ?>
                    <div class="w-12 h-12 rounded-lg bg-pine/5 dark:bg-cream/5 flex items-center justify-center shrink-0">
                      <svg class="w-5 h-5 text-pine/30 dark:text-cream/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5"/></svg>
                    </div>
                  <?php endif; ?>
                  <div class="min-w-0">
                    <p class="font-semibold truncate max-w-xs"><?php echo esc($r['judul']); ?></p>
                    <p class="text-xs text-pine/50 dark:text-cream/50 truncate max-w-xs"><?php echo esc($r['slug']); ?></p>
                  </div>
                </div>
              </td>
              <td class="px-5 py-3.5">
                <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold bg-leaf/10 text-leaf dark:bg-leaf/20"><?php echo esc($r['kategori']); ?></span>
              </td>
              <td class="px-5 py-3.5 text-pine/60 dark:text-cream/60"><?php echo tglPendek($r['tanggal']); ?></td>
              <td class="px-5 py-3.5 text-center text-pine/50 dark:text-cream/50"><?php echo $r['dilihat']; ?></td>
              <td class="px-5 py-3.5 text-right">
                <div class="flex items-center justify-end gap-2">
                  <a href="kelola_berita.php?edit=<?php echo $r['id']; ?>"
                     class="btn-action px-3 py-1.5 rounded-lg text-xs font-semibold bg-pine/5 dark:bg-cream/10 hover:bg-brass/15 hover:text-brass transition">Edit</a>
                  <form method="POST" data-confirm="Hapus berita ini?" class="inline">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?php echo $r['id']; ?>">
                    <button class="btn-action px-3 py-1.5 rounded-lg text-xs font-semibold text-red-600 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/30 transition">Hapus</button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<?php if ($totalPages > 1): ?>
<nav class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-5" aria-label="Navigasi halaman">
  <p class="text-xs text-pine/50 dark:text-cream/50">Halaman <?php echo $hal; ?> dari <?php echo $totalPages; ?></p>
  <div class="flex items-center gap-1.5">
    <?php if ($hal > 1): ?>
      <a href="<?php echo esc($pageUrl($hal - 1)); ?>" class="px-3 py-1.5 rounded-lg text-sm font-semibold bg-white/60 dark:bg-pine/40 ring-1 ring-pine/10 dark:ring-cream/10 hover:bg-brass/15 hover:text-brass transition">← Sebelumnya</a>
    <?php else: ?>
      <span class="px-3 py-1.5 rounded-lg text-sm font-semibold text-pine/25 dark:text-cream/25 cursor-not-allowed select-none">← Sebelumnya</span>
    <?php endif; ?>
    <?php for ($p = 1; $p <= $totalPages; $p++): ?>
      <?php if ($p == $hal): ?>
        <span class="w-9 h-9 inline-flex items-center justify-center rounded-lg text-sm font-bold bg-brass text-pine-deep"><?php echo $p; ?></span>
      <?php else: ?>
        <a href="<?php echo esc($pageUrl($p)); ?>" class="w-9 h-9 inline-flex items-center justify-center rounded-lg text-sm font-semibold bg-white/60 dark:bg-pine/40 ring-1 ring-pine/10 dark:ring-cream/10 hover:bg-brass/15 hover:text-brass transition"><?php echo $p; ?></a>
      <?php endif; ?>
    <?php endfor; ?>
    <?php if ($hal < $totalPages): ?>
      <a href="<?php echo esc($pageUrl($hal + 1)); ?>" class="px-3 py-1.5 rounded-lg text-sm font-semibold bg-white/60 dark:bg-pine/40 ring-1 ring-pine/10 dark:ring-cream/10 hover:bg-brass/15 hover:text-brass transition">Berikutnya →</a>
    <?php else: ?>
      <span class="px-3 py-1.5 rounded-lg text-sm font-semibold text-pine/25 dark:text-cream/25 cursor-not-allowed select-none">Berikutnya →</span>
    <?php endif; ?>
  </div>
</nav>
<?php endif; ?>

<?php endif; ?>

<?php include 'admin_foot.php'; ?>
