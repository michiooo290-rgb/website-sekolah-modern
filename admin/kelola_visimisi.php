<?php
require_once 'auth.php';
require_login();

$pdo = db();

// ── Handle POST ─────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf()) {
    $action = $_POST['action'] ?? '';

    // Save visi
    if ($action === 'save_visi') {
        $isi = trim($_POST['isi'] ?? '');
        $existing = $pdo->query("SELECT id FROM visi_misi WHERE tipe='visi' LIMIT 1")->fetch();
        if ($existing) {
            $pdo->prepare("UPDATE visi_misi SET isi=? WHERE tipe='visi'")->execute([$isi]);
        } else {
            $pdo->prepare("INSERT INTO visi_misi (tipe, isi, urutan) VALUES ('visi',?,0)")->execute([$isi]);
        }
        flash('success', 'Visi berhasil disimpan.');
        header('Location: kelola_visimisi.php');
        exit;
    }

    // Add item (misi/tujuan/nilai)
    if ($action === 'add_item') {
        $tipe = $_POST['tipe'] ?? '';
        $isi  = trim($_POST['isi'] ?? '');
        if (in_array($tipe, ['misi','tujuan','nilai']) && $isi !== '') {
            $max = $pdo->prepare("SELECT COALESCE(MAX(urutan),0) FROM visi_misi WHERE tipe=?");
            $max->execute([$tipe]);
            $urutan = $max->fetchColumn() + 1;
            $pdo->prepare("INSERT INTO visi_misi (tipe, isi, urutan) VALUES (?,?,?)")->execute([$tipe, $isi, $urutan]);
            flash('success', ucfirst($tipe) . ' berhasil ditambahkan.');
        }
        header('Location: kelola_visimisi.php');
        exit;
    }

    // Delete item
    if ($action === 'delete_item') {
        $id = (int)($_POST['id'] ?? 0);
        $pdo->prepare('DELETE FROM visi_misi WHERE id=?')->execute([$id]);
        flash('success', 'Item berhasil dihapus.');
        header('Location: kelola_visimisi.php');
        exit;
    }

    // Edit item
    if ($action === 'edit_item') {
        $id  = (int)($_POST['id'] ?? 0);
        $isi = trim($_POST['isi'] ?? '');
        $pdo->prepare('UPDATE visi_misi SET isi=? WHERE id=?')->execute([$isi, $id]);
        flash('success', 'Item berhasil diperbarui.');
        header('Location: kelola_visimisi.php');
        exit;
    }
}

// Load data
$visi   = $pdo->query("SELECT * FROM visi_misi WHERE tipe='visi' LIMIT 1")->fetch();
$misi   = $pdo->query("SELECT * FROM visi_misi WHERE tipe='misi' ORDER BY urutan")->fetchAll();
$tujuan = $pdo->query("SELECT * FROM visi_misi WHERE tipe='tujuan' ORDER BY urutan")->fetchAll();
$nilai  = $pdo->query("SELECT * FROM visi_misi WHERE tipe='nilai' ORDER BY urutan")->fetchAll();

$pageTitle = 'Kelola Visi & Misi';
include 'admin_head.php';
?>

<div class="max-w-3xl space-y-8">

  <!-- Visi -->
  <div class="admin-card bg-white/60 dark:bg-pine/40 backdrop-blur-sm rounded-2xl ring-1 ring-pine/8 dark:ring-cream/8 p-6">
    <h3 class="font-serif text-lg font-bold mb-5 flex items-center gap-2">
      <span class="w-1 h-5 rounded-full bg-brass"></span> Visi
    </h3>
    <form method="POST">
      <?php echo csrf_field(); ?>
      <input type="hidden" name="action" value="save_visi">
      <textarea name="isi" rows="3" required
                class="w-full px-4 py-2.5 rounded-xl bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 focus:border-brass focus:ring-2 focus:ring-brass/20 outline-none transition text-sm mb-4"><?php echo esc($visi['isi'] ?? ''); ?></textarea>
      <div class="flex justify-end">
        <button type="submit" class="btn-action px-6 py-2.5 rounded-xl bg-brass hover:bg-brass-light text-pine-deep font-semibold text-sm shadow-md shadow-brass/20 hover:shadow-lg transition">Simpan Visi</button>
      </div>
    </form>
  </div>

  <?php
  $sections = [
    ['key'=>'misi',   'label'=>'Misi',   'data'=>$misi],
    ['key'=>'tujuan', 'label'=>'Tujuan', 'data'=>$tujuan],
    ['key'=>'nilai',  'label'=>'Nilai',  'data'=>$nilai],
  ];
  foreach ($sections as $sec):
  ?>
  <div class="admin-card bg-white/60 dark:bg-pine/40 backdrop-blur-sm rounded-2xl ring-1 ring-pine/8 dark:ring-cream/8 p-6">
    <h3 class="font-serif text-lg font-bold mb-5 flex items-center gap-2">
      <span class="w-1 h-5 rounded-full bg-leaf"></span> <?php echo $sec['label']; ?>
    </h3>

    <!-- Existing items -->
    <?php if (!empty($sec['data'])): ?>
      <div class="space-y-3 mb-5">
        <?php foreach ($sec['data'] as $item): ?>
          <div class="flex items-start gap-3 group">
            <span class="w-7 h-7 rounded-full bg-brass/10 text-brass font-serif font-bold text-xs flex items-center justify-center mt-0.5 shrink-0">
              <?php echo $item['urutan']; ?>
            </span>
            <form method="POST" class="flex-1 flex items-start gap-2">
              <?php echo csrf_field(); ?>
              <input type="hidden" name="action" value="edit_item">
              <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
              <input type="text" name="isi" value="<?php echo esc($item['isi']); ?>"
                     class="flex-1 px-3 py-2 rounded-lg bg-cream dark:bg-pine-deep border border-pine/10 dark:border-cream/10 focus:border-brass focus:ring-2 focus:ring-brass/20 outline-none transition text-sm">
              <button class="btn-action px-3 py-2 rounded-lg text-xs font-semibold bg-pine/5 dark:bg-cream/10 hover:bg-brass/15 hover:text-brass transition opacity-0 group-hover:opacity-100">Simpan</button>
            </form>
            <form method="POST" onsubmit="return confirmDelete('Hapus item ini?')">
              <?php echo csrf_field(); ?>
              <input type="hidden" name="action" value="delete_item">
              <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
              <button class="btn-action p-2 rounded-lg text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition opacity-0 group-hover:opacity-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
              </button>
            </form>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <!-- Add new -->
    <form method="POST" class="flex items-center gap-2">
      <?php echo csrf_field(); ?>
      <input type="hidden" name="action" value="add_item">
      <input type="hidden" name="tipe" value="<?php echo $sec['key']; ?>">
      <input type="text" name="isi" required placeholder="Tambah <?php echo strtolower($sec['label']); ?> baru..."
             class="flex-1 px-4 py-2.5 rounded-xl bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 border-dashed focus:border-brass focus:ring-2 focus:ring-brass/20 outline-none transition text-sm">
      <button type="submit" class="btn-action px-4 py-2.5 rounded-xl bg-leaf/10 text-leaf hover:bg-leaf/20 font-semibold text-sm transition">
        + Tambah
      </button>
    </form>
  </div>
  <?php endforeach; ?>
</div>

<?php include 'admin_foot.php'; ?>
