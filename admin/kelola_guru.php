<?php
require_once 'auth.php';
require_login();

$pdo   = db();
$edit  = null;
$mode  = 'list';

// ── Handle actions ──────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf()) {
    $action = $_POST['action'] ?? '';

    if ($action === 'save') {
        $id      = $_POST['id'] ?? null;
        $nama    = trim($_POST['nama'] ?? '');
        $jabatan = trim($_POST['jabatan'] ?? '');
        $mapel   = trim($_POST['mapel'] ?? '');
        $urutan  = (int)($_POST['urutan'] ?? 0);

        if ($nama === '' || $jabatan === '') {
            flash('error', 'Nama dan jabatan wajib diisi.');
        } else {
            $foto = $_POST['foto_lama'] ?? 'placeholder.jpeg';
            if (!empty($_FILES['foto']['name'])) {
                $uploaded = upload_gambar($_FILES['foto'], __DIR__ . '/uploads/guru');
                if ($uploaded) {
                    if ($foto && $foto !== 'placeholder.jpeg' && file_exists(__DIR__ . '/uploads/guru/' . $foto)) {
                        unlink(__DIR__ . '/uploads/guru/' . $foto);
                    }
                    $foto = $uploaded;
                }
            }

            if (!get_flash('error')) {
                if ($id) {
                    $stmt = $pdo->prepare('UPDATE guru SET nama=?, jabatan=?, mapel=?, foto=?, urutan=? WHERE id=?');
                    $stmt->execute([$nama, $jabatan, $mapel, $foto, $urutan, $id]);
                    flash('success', 'Data guru berhasil diperbarui.');
                } else {
                    // Check max 9
                    $count = $pdo->query('SELECT COUNT(*) FROM guru')->fetchColumn();
                    if ($count >= 9) {
                        flash('error', 'Maksimal 9 entri guru (1 Kepala Sekolah + 8 guru). Hapus salah satu terlebih dahulu.');
                    } else {
                        $stmt = $pdo->prepare('INSERT INTO guru (nama, jabatan, mapel, foto, urutan) VALUES (?,?,?,?,?)');
                        $stmt->execute([$nama, $jabatan, $mapel, $foto, $urutan]);
                        flash('success', 'Data guru berhasil ditambahkan.');
                    }
                }
                if (!get_flash('error')) {
                    header('Location: kelola_guru.php');
                    exit;
                }
            }
        }
        $mode = 'form';
    }

    if ($action === 'delete') {
        $id  = (int)($_POST['id'] ?? 0);
        $row = $pdo->prepare('SELECT foto FROM guru WHERE id=?');
        $row->execute([$id]);
        $row = $row->fetch();
        if ($row && $row['foto'] && $row['foto'] !== 'placeholder.jpeg' && file_exists(__DIR__ . '/uploads/guru/' . $row['foto'])) {
            unlink(__DIR__ . '/uploads/guru/' . $row['foto']);
        }
        $pdo->prepare('DELETE FROM guru WHERE id=?')->execute([$id]);
        flash('success', 'Data guru berhasil dihapus.');
        header('Location: kelola_guru.php');
        exit;
    }
}

if (isset($_GET['edit'])) {
    $edit = $pdo->prepare('SELECT * FROM guru WHERE id=?');
    $edit->execute([(int)$_GET['edit']]);
    $edit = $edit->fetch();
    $mode = $edit ? 'form' : 'list';
}
if (isset($_GET['new'])) $mode = 'form';

$pageTitle = $mode === 'form' ? ($edit ? 'Edit Guru' : 'Tambah Guru') : 'Kelola Guru & Staf';
include 'admin_head.php';

if ($mode === 'form'):
?>

<form method="POST" enctype="multipart/form-data" class="max-w-2xl space-y-6">
  <?php echo csrf_field(); ?>
  <input type="hidden" name="action" value="save">
  <?php if ($edit): ?><input type="hidden" name="id" value="<?php echo $edit['id']; ?>"><?php endif; ?>
  <?php if ($edit && $edit['foto']): ?><input type="hidden" name="foto_lama" value="<?php echo esc($edit['foto']); ?>"><?php endif; ?>

  <div class="admin-card bg-white/60 dark:bg-pine/40 backdrop-blur-sm rounded-2xl ring-1 ring-pine/8 dark:ring-cream/8 p-6 space-y-5">
    <div>
      <label class="block text-sm font-semibold mb-1.5">Nama Lengkap</label>
      <input type="text" name="nama" required value="<?php echo esc($edit['nama'] ?? ''); ?>"
             class="w-full px-4 py-2.5 rounded-xl bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 focus:border-brass focus:ring-2 focus:ring-brass/20 outline-none transition text-sm">
    </div>

    <div class="grid sm:grid-cols-2 gap-5">
      <div>
        <label class="block text-sm font-semibold mb-1.5">Jabatan</label>
        <input type="text" name="jabatan" required value="<?php echo esc($edit['jabatan'] ?? ''); ?>"
               placeholder="contoh: Kepala Sekolah, Guru Matematika"
               class="w-full px-4 py-2.5 rounded-xl bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 focus:border-brass focus:ring-2 focus:ring-brass/20 outline-none transition text-sm">
      </div>
      <div>
        <label class="block text-sm font-semibold mb-1.5">Mata Pelajaran</label>
        <input type="text" name="mapel" value="<?php echo esc($edit['mapel'] ?? ''); ?>"
               placeholder="opsional"
               class="w-full px-4 py-2.5 rounded-xl bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 focus:border-brass focus:ring-2 focus:ring-brass/20 outline-none transition text-sm">
      </div>
    </div>

    <div>
      <label class="block text-sm font-semibold mb-1.5">Urutan Tampil</label>
      <input type="number" name="urutan" min="0" value="<?php echo esc($edit['urutan'] ?? '0'); ?>"
             class="w-32 px-4 py-2.5 rounded-xl bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 focus:border-brass focus:ring-2 focus:ring-brass/20 outline-none transition text-sm">
      <p class="text-xs text-pine/50 dark:text-cream/50 mt-1">Angka lebih kecil tampil lebih dulu.</p>
    </div>

    <div>
      <label class="block text-sm font-semibold mb-1.5">Foto</label>
      <?php if (!empty($edit['foto'])): ?>
        <div class="mb-2">
          <img src="uploads/guru/<?php echo esc($edit['foto']); ?>" alt="" class="h-24 w-24 rounded-xl object-cover ring-1 ring-pine/10">
        </div>
      <?php endif; ?>
      <input type="file" name="foto" accept="image/*"
             class="w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-brass/10 file:text-brass file:font-semibold file:text-sm hover:file:bg-brass/20 transition">
    </div>
  </div>

  <div class="flex items-center justify-between">
    <a href="kelola_guru.php" class="text-sm text-pine/60 dark:text-cream/60 hover:text-pine dark:hover:text-cream transition">← Kembali</a>
    <button type="submit" class="btn-action px-8 py-3 rounded-xl bg-brass hover:bg-brass-light text-pine-deep font-semibold text-sm shadow-md shadow-brass/20 hover:shadow-lg transition">
      <?php echo $edit ? 'Perbarui' : 'Simpan'; ?>
    </button>
  </div>
</form>

<?php else: ?>

<div class="flex items-center justify-between mb-6">
  <?php $count = $pdo->query('SELECT COUNT(*) FROM guru')->fetchColumn(); ?>
  <p class="text-sm text-pine/60 dark:text-cream/60"><?php echo $count; ?>/9 entri guru</p>
  <?php if ($count < 9): ?>
    <a href="kelola_guru.php?new=1" class="btn-action inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-brass hover:bg-brass-light text-pine-deep font-semibold text-sm shadow-md shadow-brass/20 hover:shadow-lg transition">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/></svg>
      Tambah Guru
    </a>
  <?php endif; ?>
</div>

<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
  <?php $rows = $pdo->query('SELECT * FROM guru ORDER BY urutan ASC, id ASC')->fetchAll(); ?>
  <?php foreach ($rows as $r): ?>
    <div class="admin-card bg-white/60 dark:bg-pine/40 backdrop-blur-sm rounded-2xl ring-1 ring-pine/8 dark:ring-cream/8 overflow-hidden group">
      <div class="aspect-[4/3] overflow-hidden bg-pine/5 dark:bg-cream/5">
        <img src="uploads/guru/<?php echo esc($r['foto']); ?>" alt="<?php echo esc($r['nama']); ?>"
             class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
      </div>
      <div class="p-5">
        <p class="font-serif font-bold mb-0.5"><?php echo esc($r['nama']); ?></p>
        <p class="text-sm text-leaf dark:text-brass-light"><?php echo esc($r['jabatan']); ?></p>
        <?php if ($r['mapel']): ?>
          <p class="text-xs text-pine/50 dark:text-cream/50 mt-0.5"><?php echo esc($r['mapel']); ?></p>
        <?php endif; ?>
        <div class="flex items-center gap-2 mt-4 pt-3 border-t border-pine/8 dark:border-cream/8">
          <a href="kelola_guru.php?edit=<?php echo $r['id']; ?>"
             class="btn-action flex-1 text-center px-3 py-2 rounded-lg text-xs font-semibold bg-pine/5 dark:bg-cream/10 hover:bg-brass/15 hover:text-brass transition">Edit</a>
          <form method="POST" onsubmit="return confirmDelete('Hapus guru ini?')" class="flex-1">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?php echo $r['id']; ?>">
            <button class="btn-action w-full px-3 py-2 rounded-lg text-xs font-semibold text-red-600 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 transition">Hapus</button>
          </form>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<?php endif; ?>

<?php include 'admin_foot.php'; ?>
