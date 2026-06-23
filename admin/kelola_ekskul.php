<?php
require_once 'auth.php';
require_login();

$pdo  = db();
$edit = null;
$mode = 'list';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf()) {
    $action = $_POST['action'] ?? '';

    if ($action === 'save') {
        $id        = $_POST['id'] ?? null;
        $nama      = trim($_POST['nama'] ?? '');
        $kategori  = trim($_POST['kategori'] ?? '');
        $deskripsi = trim($_POST['deskripsi'] ?? '');
        $pembina   = trim($_POST['pembina'] ?? '');
        $jadwal    = trim($_POST['jadwal'] ?? '');

        if ($nama === '') {
            flash('error', 'Nama ekskul wajib diisi.');
        } else {
            if ($id) {
                $stmt = $pdo->prepare('UPDATE ekstrakurikuler SET nama=?, kategori=?, deskripsi=?, pembina=?, jadwal=? WHERE id=?');
                $stmt->execute([$nama, $kategori, $deskripsi, $pembina, $jadwal, $id]);
                flash('success', 'Ekstrakurikuler berhasil diperbarui.');
            } else {
                $stmt = $pdo->prepare('INSERT INTO ekstrakurikuler (nama, kategori, deskripsi, pembina, jadwal) VALUES (?,?,?,?,?)');
                $stmt->execute([$nama, $kategori, $deskripsi, $pembina, $jadwal]);
                flash('success', 'Ekstrakurikuler berhasil ditambahkan.');
            }
            header('Location: kelola_ekskul.php');
            exit;
        }
        $mode = 'form';
    }

    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        $pdo->prepare('DELETE FROM ekstrakurikuler WHERE id=?')->execute([$id]);
        flash('success', 'Ekstrakurikuler berhasil dihapus.');
        header('Location: kelola_ekskul.php');
        exit;
    }
}

if (isset($_GET['edit'])) {
    $edit = $pdo->prepare('SELECT * FROM ekstrakurikuler WHERE id=?');
    $edit->execute([(int)$_GET['edit']]);
    $edit = $edit->fetch();
    $mode = $edit ? 'form' : 'list';
}
if (isset($_GET['new'])) $mode = 'form';

$pageTitle = $mode === 'form' ? ($edit ? 'Edit Ekstrakurikuler' : 'Tambah Ekstrakurikuler') : 'Kelola Ekstrakurikuler';
include 'admin_head.php';

if ($mode === 'form'):
?>

<form method="POST" class="max-w-2xl space-y-6">
  <?php echo csrf_field(); ?>
  <input type="hidden" name="action" value="save">
  <?php if ($edit): ?><input type="hidden" name="id" value="<?php echo $edit['id']; ?>"><?php endif; ?>

  <div class="admin-card bg-white/60 dark:bg-pine/40 backdrop-blur-sm rounded-2xl ring-1 ring-pine/8 dark:ring-cream/8 p-6 space-y-5">
    <div>
      <label class="block text-sm font-semibold mb-1.5">Nama Kegiatan</label>
      <input type="text" name="nama" required value="<?php echo esc($edit['nama'] ?? ''); ?>"
             class="w-full px-4 py-2.5 rounded-xl bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 focus:border-brass focus:ring-2 focus:ring-brass/20 outline-none transition text-sm">
    </div>

    <div>
      <label class="block text-sm font-semibold mb-1.5">Kategori</label>
      <select name="kategori" class="w-full px-4 py-2.5 rounded-xl bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 focus:border-brass focus:ring-2 focus:ring-brass/20 outline-none transition text-sm">
        <?php foreach (['Keagamaan','Olahraga','Seni & Budaya','Akademik & Sains','Organisasi & Kepanduan'] as $kat): ?>
          <option value="<?php echo $kat; ?>" <?php echo (($edit['kategori'] ?? '') === $kat) ? 'selected' : ''; ?>><?php echo $kat; ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div>
      <label class="block text-sm font-semibold mb-1.5">Deskripsi</label>
      <textarea name="deskripsi" rows="4"
                class="w-full px-4 py-2.5 rounded-xl bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 focus:border-brass focus:ring-2 focus:ring-brass/20 outline-none transition text-sm"><?php echo esc($edit['deskripsi'] ?? ''); ?></textarea>
    </div>

    <div class="grid sm:grid-cols-2 gap-5">
      <div>
        <label class="block text-sm font-semibold mb-1.5">Pembina</label>
        <input type="text" name="pembina" value="<?php echo esc($edit['pembina'] ?? ''); ?>"
               class="w-full px-4 py-2.5 rounded-xl bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 focus:border-brass focus:ring-2 focus:ring-brass/20 outline-none transition text-sm">
      </div>
      <div>
        <label class="block text-sm font-semibold mb-1.5">Jadwal</label>
        <input type="text" name="jadwal" value="<?php echo esc($edit['jadwal'] ?? ''); ?>"
               placeholder="contoh: Sabtu, 14:00 - 16:00"
               class="w-full px-4 py-2.5 rounded-xl bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 focus:border-brass focus:ring-2 focus:ring-brass/20 outline-none transition text-sm">
      </div>
    </div>
  </div>

  <div class="flex items-center justify-between">
    <a href="kelola_ekskul.php" class="text-sm text-pine/60 dark:text-cream/60 hover:text-pine dark:hover:text-cream transition">← Kembali</a>
    <button type="submit" class="btn-action px-8 py-3 rounded-xl bg-brass hover:bg-brass-light text-pine-deep font-semibold text-sm shadow-md shadow-brass/20 hover:shadow-lg transition">
      <?php echo $edit ? 'Perbarui' : 'Simpan'; ?>
    </button>
  </div>
</form>

<?php else: ?>

<div class="flex items-center justify-between mb-6">
  <p class="text-sm text-pine/60 dark:text-cream/60"><?php echo $pdo->query('SELECT COUNT(*) FROM ekstrakurikuler')->fetchColumn(); ?> kegiatan</p>
  <a href="kelola_ekskul.php?new=1" class="btn-action inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-brass hover:bg-brass-light text-pine-deep font-semibold text-sm shadow-md shadow-brass/20 hover:shadow-lg transition">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/></svg>
    Tambah Ekskul
  </a>
</div>

<div class="admin-card bg-white/60 dark:bg-pine/40 backdrop-blur-sm rounded-2xl ring-1 ring-pine/8 dark:ring-cream/8 overflow-hidden">
  <?php $rows = $pdo->query('SELECT * FROM ekstrakurikuler ORDER BY kategori, nama')->fetchAll(); ?>
  <?php if (empty($rows)): ?>
    <p class="text-sm text-pine/50 dark:text-cream/50 p-8 text-center">Belum ada ekstrakurikuler.</p>
  <?php else: ?>
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-pine/10 dark:border-cream/10 text-left text-xs font-semibold text-pine/60 dark:text-cream/60 uppercase tracking-wider">
            <th class="px-5 py-3">Kegiatan</th>
            <th class="px-5 py-3">Kategori</th>
            <th class="px-5 py-3">Pembina</th>
            <th class="px-5 py-3">Jadwal</th>
            <th class="px-5 py-3 text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-pine/5 dark:divide-cream/5">
          <?php foreach ($rows as $r): ?>
            <tr class="table-row">
              <td class="px-5 py-3.5 font-semibold"><?php echo esc($r['nama']); ?></td>
              <td class="px-5 py-3.5">
                <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold bg-brass/10 text-brass"><?php echo esc($r['kategori']); ?></span>
              </td>
              <td class="px-5 py-3.5 text-pine/60 dark:text-cream/60"><?php echo esc($r['pembina'] ?: '-'); ?></td>
              <td class="px-5 py-3.5 text-pine/60 dark:text-cream/60"><?php echo esc($r['jadwal'] ?: '-'); ?></td>
              <td class="px-5 py-3.5 text-right">
                <div class="flex items-center justify-end gap-2">
                  <a href="kelola_ekskul.php?edit=<?php echo $r['id']; ?>" class="btn-action px-3 py-1.5 rounded-lg text-xs font-semibold bg-pine/5 dark:bg-cream/10 hover:bg-brass/15 hover:text-brass transition">Edit</a>
                  <form method="POST" onsubmit="return confirmDelete('Hapus ekskul ini?')" class="inline">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?php echo $r['id']; ?>">
                    <button class="btn-action px-3 py-1.5 rounded-lg text-xs font-semibold text-red-600 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 transition">Hapus</button>
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
