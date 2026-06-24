<?php
require_once 'auth.php';
require_login();

$pdo   = db();
$edit  = null;
$mode  = 'list';

// ── Handle actions ──────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf()) {
    $action = $_POST['action'] ?? '';

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
        <div class="mb-2">
          <img src="uploads/berita/<?php echo esc($edit['gambar']); ?>" alt="" class="h-24 rounded-lg object-cover ring-1 ring-pine/10">
        </div>
      <?php endif; ?>
      <input type="file" name="gambar" accept="image/*"
             class="w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-brass/10 file:text-brass file:font-semibold file:text-sm hover:file:bg-brass/20 transition">
      <p class="text-xs text-pine/50 dark:text-cream/50 mt-1">JPG, PNG, atau WebP. Maks 5 MB.</p>
    </div>

    <div>
      <label class="block text-sm font-semibold mb-1.5">Isi Berita</label>
      <textarea name="isi" rows="12" required
                class="w-full px-4 py-2.5 rounded-xl bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 focus:border-brass focus:ring-2 focus:ring-brass/20 outline-none transition text-sm font-sans"><?php echo esc($edit['isi'] ?? ''); ?></textarea>
      <p class="text-xs text-pine/50 dark:text-cream/50 mt-1">Mendukung tag HTML untuk format (p, h3, ul, li, strong, em).</p>
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

<?php else: ?>

<!-- List mode -->
<div class="flex items-center justify-between mb-6">
  <p class="text-sm text-pine/60 dark:text-cream/60"><?php echo $pdo->query('SELECT COUNT(*) FROM berita')->fetchColumn(); ?> berita</p>
  <a href="kelola_berita.php?new=1"
     class="btn-action inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-brass hover:bg-brass-light text-pine-deep font-semibold text-sm shadow-md shadow-brass/20 hover:shadow-lg transition">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/></svg>
    Tambah Berita
  </a>
</div>

<div class="admin-card bg-white/60 dark:bg-pine/40 backdrop-blur-sm rounded-2xl ring-1 ring-pine/8 dark:ring-cream/8 overflow-hidden">
  <?php $rows = $pdo->query('SELECT * FROM berita ORDER BY tanggal DESC')->fetchAll(); ?>
  <?php if (empty($rows)): ?>
    <p class="text-sm text-pine/50 dark:text-cream/50 p-8 text-center">Belum ada berita.</p>
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

<?php endif; ?>

<?php include 'admin_foot.php'; ?>
