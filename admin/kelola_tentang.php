<?php
require_once 'auth.php';
require_login();

$pdo = db();

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf()) {
    $action = $_POST['action'] ?? '';

    if ($action === 'save_section') {
        $bagian = $_POST['bagian'] ?? '';
        $judul  = trim($_POST['judul'] ?? '');
        $isi    = trim($_POST['isi'] ?? '');

        if (in_array($bagian, ['sejarah','sambutan'])) {
            $existing = $pdo->prepare("SELECT id FROM tentang WHERE bagian=? LIMIT 1");
            $existing->execute([$bagian]);
            if ($existing->fetch()) {
                $pdo->prepare("UPDATE tentang SET judul=?, isi=? WHERE bagian=?")->execute([$judul, $isi, $bagian]);
            } else {
                $pdo->prepare("INSERT INTO tentang (bagian, judul, isi) VALUES (?,?,?)")->execute([$bagian, $judul, $isi]);
            }
            flash('success', 'Bagian ' . ucfirst($bagian) . ' berhasil disimpan.');
        }
        header('Location: kelola_tentang.php');
        exit;
    }

    if ($action === 'add_fasilitas') {
        $judul = trim($_POST['judul'] ?? '');
        $isi   = trim($_POST['isi'] ?? '');
        if ($judul !== '') {
            $pdo->prepare("INSERT INTO tentang (bagian, judul, isi) VALUES ('fasilitas',?,?)")->execute([$judul, $isi]);
            flash('success', 'Fasilitas berhasil ditambahkan.');
        }
        header('Location: kelola_tentang.php');
        exit;
    }

    if ($action === 'edit_fasilitas') {
        $id    = (int)($_POST['id'] ?? 0);
        $judul = trim($_POST['judul'] ?? '');
        $isi   = trim($_POST['isi'] ?? '');
        $pdo->prepare('UPDATE tentang SET judul=?, isi=? WHERE id=?')->execute([$judul, $isi, $id]);
        flash('success', 'Fasilitas berhasil diperbarui.');
        header('Location: kelola_tentang.php');
        exit;
    }

    if ($action === 'delete_fasilitas') {
        $id = (int)($_POST['id'] ?? 0);
        $pdo->prepare('DELETE FROM tentang WHERE id=?')->execute([$id]);
        flash('success', 'Fasilitas berhasil dihapus.');
        header('Location: kelola_tentang.php');
        exit;
    }
}

// Load data
$sejarah   = $pdo->query("SELECT * FROM tentang WHERE bagian='sejarah' LIMIT 1")->fetch();
$sambutan  = $pdo->query("SELECT * FROM tentang WHERE bagian='sambutan' LIMIT 1")->fetch();
$fasilitas = $pdo->query("SELECT * FROM tentang WHERE bagian='fasilitas' ORDER BY id")->fetchAll();

$pageTitle = 'Kelola Tentang';
include 'admin_head.php';
?>

<div class="max-w-3xl space-y-8">

  <!-- Sejarah -->
  <div class="admin-card bg-white/60 dark:bg-pine/40 backdrop-blur-sm rounded-2xl ring-1 ring-pine/8 dark:ring-cream/8 p-6">
    <h3 class="font-serif text-lg font-bold mb-5 flex items-center gap-2">
      <span class="w-1 h-5 rounded-full bg-brass"></span> Sejarah
    </h3>
    <form method="POST">
      <?php echo csrf_field(); ?>
      <input type="hidden" name="action" value="save_section">
      <input type="hidden" name="bagian" value="sejarah">
      <div class="mb-4">
        <label class="block text-sm font-semibold mb-1.5">Judul</label>
        <input type="text" name="judul" value="<?php echo esc($sejarah['judul'] ?? ''); ?>"
               class="w-full px-4 py-2.5 rounded-xl bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 focus:border-brass focus:ring-2 focus:ring-brass/20 outline-none transition text-sm">
      </div>
      <div class="mb-4">
        <label class="block text-sm font-semibold mb-1.5">Isi</label>
        <textarea name="isi" rows="6"
                  class="w-full px-4 py-2.5 rounded-xl bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 focus:border-brass focus:ring-2 focus:ring-brass/20 outline-none transition text-sm"><?php echo esc($sejarah['isi'] ?? ''); ?></textarea>
      </div>
      <div class="flex justify-end">
        <button type="submit" class="btn-action px-6 py-2.5 rounded-xl bg-brass hover:bg-brass-light text-pine-deep font-semibold text-sm shadow-md shadow-brass/20 hover:shadow-lg transition">Simpan</button>
      </div>
    </form>
  </div>

  <!-- Sambutan -->
  <div class="admin-card bg-white/60 dark:bg-pine/40 backdrop-blur-sm rounded-2xl ring-1 ring-pine/8 dark:ring-cream/8 p-6">
    <h3 class="font-serif text-lg font-bold mb-5 flex items-center gap-2">
      <span class="w-1 h-5 rounded-full bg-leaf"></span> Sambutan Kepala Sekolah
    </h3>
    <form method="POST">
      <?php echo csrf_field(); ?>
      <input type="hidden" name="action" value="save_section">
      <input type="hidden" name="bagian" value="sambutan">
      <div class="mb-4">
        <label class="block text-sm font-semibold mb-1.5">Judul</label>
        <input type="text" name="judul" value="<?php echo esc($sambutan['judul'] ?? ''); ?>"
               class="w-full px-4 py-2.5 rounded-xl bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 focus:border-brass focus:ring-2 focus:ring-brass/20 outline-none transition text-sm">
      </div>
      <div class="mb-4">
        <label class="block text-sm font-semibold mb-1.5">Isi Sambutan</label>
        <textarea name="isi" rows="6"
                  class="w-full px-4 py-2.5 rounded-xl bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 focus:border-brass focus:ring-2 focus:ring-brass/20 outline-none transition text-sm"><?php echo esc($sambutan['isi'] ?? ''); ?></textarea>
      </div>
      <div class="flex justify-end">
        <button type="submit" class="btn-action px-6 py-2.5 rounded-xl bg-brass hover:bg-brass-light text-pine-deep font-semibold text-sm shadow-md shadow-brass/20 hover:shadow-lg transition">Simpan</button>
      </div>
    </form>
  </div>

  <!-- Fasilitas -->
  <div class="admin-card bg-white/60 dark:bg-pine/40 backdrop-blur-sm rounded-2xl ring-1 ring-pine/8 dark:ring-cream/8 p-6">
    <h3 class="font-serif text-lg font-bold mb-5 flex items-center gap-2">
      <span class="w-1 h-5 rounded-full bg-brass"></span> Fasilitas
    </h3>

    <?php if (!empty($fasilitas)): ?>
      <div class="space-y-4 mb-6">
        <?php foreach ($fasilitas as $f): ?>
          <details class="group rounded-xl ring-1 ring-pine/8 dark:ring-cream/8 overflow-hidden">
            <summary class="flex items-center justify-between px-5 py-3.5 cursor-pointer hover:bg-pine/3 dark:hover:bg-cream/5 transition">
              <span class="font-semibold text-sm"><?php echo esc($f['judul']); ?></span>
              <svg class="w-4 h-4 text-pine/40 dark:text-cream/40 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </summary>
            <div class="px-5 pb-4 space-y-3">
              <form method="POST" id="edit-fas-<?php echo $f['id']; ?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="action" value="edit_fasilitas">
                <input type="hidden" name="id" value="<?php echo $f['id']; ?>">
                <input type="text" name="judul" value="<?php echo esc($f['judul']); ?>"
                       class="w-full px-3 py-2 rounded-lg bg-cream dark:bg-pine-deep border border-pine/10 dark:border-cream/10 focus:border-brass outline-none transition text-sm">
                <textarea name="isi" rows="3"
                          class="w-full px-3 py-2 rounded-lg bg-cream dark:bg-pine-deep border border-pine/10 dark:border-cream/10 focus:border-brass outline-none transition text-sm"><?php echo esc($f['isi']); ?></textarea>
              </form>
              <div class="flex items-center justify-between">
                <button type="submit" form="edit-fas-<?php echo $f['id']; ?>" class="btn-action px-4 py-2 rounded-lg text-xs font-semibold bg-brass/10 text-brass hover:bg-brass/20 transition">Simpan</button>
                <form method="POST" onsubmit="return confirmDelete('Hapus fasilitas ini?')">
                  <?php echo csrf_field(); ?>
                  <input type="hidden" name="action" value="delete_fasilitas">
                  <input type="hidden" name="id" value="<?php echo $f['id']; ?>">
                  <button class="btn-action text-xs text-red-500 hover:text-red-700 transition">Hapus</button>
                </form>
              </div>
            </div>
          </details>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <!-- Add new -->
    <form method="POST" class="space-y-3">
      <?php echo csrf_field(); ?>
      <input type="hidden" name="action" value="add_fasilitas">
      <input type="text" name="judul" required placeholder="Nama fasilitas..."
             class="w-full px-4 py-2.5 rounded-xl bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 border-dashed focus:border-brass focus:ring-2 focus:ring-brass/20 outline-none transition text-sm">
      <textarea name="isi" rows="2" placeholder="Deskripsi singkat..."
                class="w-full px-4 py-2.5 rounded-xl bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 border-dashed focus:border-brass focus:ring-2 focus:ring-brass/20 outline-none transition text-sm"></textarea>
      <div class="flex justify-end">
        <button type="submit" class="btn-action px-5 py-2.5 rounded-xl bg-leaf/10 text-leaf hover:bg-leaf/20 font-semibold text-sm transition">+ Tambah Fasilitas</button>
      </div>
    </form>
  </div>
</div>

<?php include 'admin_foot.php'; ?>
