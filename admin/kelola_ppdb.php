<?php
require_once 'auth.php';
require_login();

$pdo = db();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf()) {
    $action = $_POST['action'] ?? '';

    // Save / add item
    if ($action === 'save_item') {
        $bagian  = $_POST['bagian'] ?? '';
        $id      = $_POST['id'] ?? null;
        $judul   = trim($_POST['judul'] ?? '');
        $isi     = trim($_POST['isi'] ?? '');
        $tanggal = trim($_POST['tanggal'] ?? '');
        $urutan  = (int)($_POST['urutan'] ?? 0);

        if (in_array($bagian, ['syarat','jadwal','alur','faq'])) {
            if ($id) {
                $pdo->prepare('UPDATE ppdb_info SET judul=?, isi=?, tanggal=?, urutan=? WHERE id=?')
                    ->execute([$judul, $isi, $tanggal, $urutan, $id]);
                flash('success', 'Item berhasil diperbarui.');
            } else {
                $pdo->prepare('INSERT INTO ppdb_info (bagian, judul, isi, tanggal, urutan) VALUES (?,?,?,?,?)')
                    ->execute([$bagian, $judul, $isi, $tanggal, $urutan]);
                flash('success', 'Item berhasil ditambahkan.');
            }
        }
        header('Location: kelola_ppdb.php');
        exit;
    }

    if ($action === 'delete_item') {
        $id = (int)($_POST['id'] ?? 0);
        $pdo->prepare('DELETE FROM ppdb_info WHERE id=?')->execute([$id]);
        flash('success', 'Item berhasil dihapus.');
        header('Location: kelola_ppdb.php');
        exit;
    }

    // Upload formulir PDF
    if ($action === 'upload_formulir') {
        if (!empty($_FILES['file_formulir']['name'])) {
            $ext = strtolower(pathinfo($_FILES['file_formulir']['name'], PATHINFO_EXTENSION));
            // Size limit: 10MB
            if ($_FILES['file_formulir']['size'] > 10 * 1024 * 1024) {
                flash('error', 'Ukuran file terlalu besar (maks 10 MB).');
            } elseif ($ext !== 'pdf') {
                flash('error', 'Hanya file PDF yang diizinkan.');
            } else {
                // Verify PDF magic bytes
                $handle = fopen($_FILES['file_formulir']['tmp_name'], 'rb');
                $header = fread($handle, 5);
                fclose($handle);
                if ($header !== '%PDF-') {
                    flash('error', 'File bukan PDF yang valid.');
                } else {
                    $name = 'formulir_ppdb_' . time() . '.pdf';
                    $dest = __DIR__ . '/uploads/ppdb/' . $name;
                    if (!is_dir(__DIR__ . '/uploads/ppdb')) mkdir(__DIR__ . '/uploads/ppdb', 0755, true);
                    move_uploaded_file($_FILES['file_formulir']['tmp_name'], $dest);
                    // Store as pengaturan
                    $stmt = $pdo->prepare("INSERT INTO pengaturan (kunci, nilai) VALUES ('file_formulir_ppdb', ?) ON DUPLICATE KEY UPDATE nilai = VALUES(nilai)");
                    $stmt->execute([$name]);
                    flash('success', 'File formulir berhasil diupload.');
                }
            }
        }
        header('Location: kelola_ppdb.php');
        exit;
    }
}

// Load data
$syarat = $pdo->query("SELECT * FROM ppdb_info WHERE bagian='syarat' ORDER BY urutan")->fetchAll();
$jadwal = $pdo->query("SELECT * FROM ppdb_info WHERE bagian='jadwal' ORDER BY urutan")->fetchAll();
$alur   = $pdo->query("SELECT * FROM ppdb_info WHERE bagian='alur' ORDER BY urutan")->fetchAll();
$faq    = $pdo->query("SELECT * FROM ppdb_info WHERE bagian='faq' ORDER BY urutan")->fetchAll();
$fileFormulir = setting('file_formulir_ppdb');

$pageTitle = 'Kelola PPDB';
include 'admin_head.php';
?>

<div class="max-w-3xl space-y-8">

  <!-- Upload Formulir -->
  <div class="admin-card bg-white/60 dark:bg-pine/40 backdrop-blur-sm rounded-2xl ring-1 ring-pine/8 dark:ring-cream/8 p-6">
    <h3 class="font-serif text-lg font-bold mb-5 flex items-center gap-2">
      <span class="w-1 h-5 rounded-full bg-brass"></span> File Formulir PDF
    </h3>
    <?php if ($fileFormulir): ?>
      <div class="flex items-center gap-3 mb-4 p-3 rounded-lg bg-leaf/5 dark:bg-leaf/10 ring-1 ring-leaf/20">
        <svg class="w-5 h-5 text-leaf shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
        <span class="text-sm"><?php echo esc($fileFormulir); ?></span>
      </div>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
      <?php echo csrf_field(); ?>
      <input type="hidden" name="action" value="upload_formulir">
      <input type="file" name="file_formulir" accept=".pdf" required
             class="w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-brass/10 file:text-brass file:font-semibold file:text-sm hover:file:bg-brass/20 transition mb-3">
      <button type="submit" class="btn-action px-5 py-2.5 rounded-xl bg-brass hover:bg-brass-light text-pine-deep font-semibold text-sm shadow-md shadow-brass/20 hover:shadow-lg transition">Upload PDF</button>
    </form>
  </div>

  <?php
  $sections = [
    ['key'=>'syarat', 'label'=>'Persyaratan', 'data'=>$syarat],
    ['key'=>'jadwal', 'label'=>'Jadwal Penting', 'data'=>$jadwal],
    ['key'=>'alur',   'label'=>'Alur Pendaftaran', 'data'=>$alur],
    ['key'=>'faq',    'label'=>'FAQ', 'data'=>$faq],
  ];
  foreach ($sections as $sec):
  ?>
  <div class="admin-card bg-white/60 dark:bg-pine/40 backdrop-blur-sm rounded-2xl ring-1 ring-pine/8 dark:ring-cream/8 p-6">
    <h3 class="font-serif text-lg font-bold mb-5 flex items-center gap-2">
      <span class="w-1 h-5 rounded-full bg-leaf"></span> <?php echo $sec['label']; ?>
    </h3>

    <?php if (!empty($sec['data'])): ?>
      <div class="space-y-3 mb-5">
        <?php foreach ($sec['data'] as $item): ?>
          <details class="group rounded-xl ring-1 ring-pine/8 dark:ring-cream/8 overflow-hidden">
            <summary class="flex items-center justify-between px-5 py-3 cursor-pointer hover:bg-pine/3 dark:hover:bg-cream/5 transition">
              <span class="font-semibold text-sm"><?php echo esc($item['judul']); ?></span>
              <div class="flex items-center gap-2">
                <?php if ($item['tanggal']): ?><span class="text-xs text-pine/50 dark:text-cream/50"><?php echo esc($item['tanggal']); ?></span><?php endif; ?>
                <svg class="w-4 h-4 text-pine/40 dark:text-cream/40 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
              </div>
            </summary>
            <div class="px-5 pb-4 space-y-3">
              <form method="POST" id="edit-ppdb-<?php echo $item['id']; ?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="action" value="save_item">
                <input type="hidden" name="bagian" value="<?php echo $sec['key']; ?>">
                <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                <div class="grid sm:grid-cols-2 gap-3">
                  <input type="text" name="judul" value="<?php echo esc($item['judul']); ?>"
                         class="px-3 py-2 rounded-lg bg-cream dark:bg-pine-deep border border-pine/10 dark:border-cream/10 focus:border-brass outline-none transition text-sm">
                  <input type="text" name="tanggal" value="<?php echo esc($item['tanggal']); ?>"
                         placeholder="Tanggal (opsional)"
                         class="px-3 py-2 rounded-lg bg-cream dark:bg-pine-deep border border-pine/10 dark:border-cream/10 focus:border-brass outline-none transition text-sm">
                </div>
                <textarea name="isi" rows="3"
                          class="w-full px-3 py-2 rounded-lg bg-cream dark:bg-pine-deep border border-pine/10 dark:border-cream/10 focus:border-brass outline-none transition text-sm"><?php echo esc($item['isi']); ?></textarea>
              </form>
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                  <label class="text-xs text-pine/50 dark:text-cream/50">Urutan:</label>
                  <input type="number" name="urutan" form="edit-ppdb-<?php echo $item['id']; ?>" value="<?php echo $item['urutan']; ?>" min="0"
                         class="w-16 px-2 py-1 rounded-lg bg-cream dark:bg-pine-deep border border-pine/10 dark:border-cream/10 focus:border-brass outline-none transition text-sm text-center">
                </div>
                <div class="flex items-center gap-2">
                  <button type="submit" form="edit-ppdb-<?php echo $item['id']; ?>" class="btn-action px-4 py-2 rounded-lg text-xs font-semibold bg-brass/10 text-brass hover:bg-brass/20 transition">Simpan</button>
                  <form method="POST" onsubmit="return confirmDelete('Hapus item ini?')" class="inline">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="action" value="delete_item">
                    <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                    <button class="btn-action text-xs text-red-500 hover:text-red-700 transition">Hapus</button>
                  </form>
                </div>
              </div>
            </div>
          </details>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <form method="POST" class="space-y-3 pt-3 border-t border-pine/8 dark:border-cream/8">
      <?php echo csrf_field(); ?>
      <input type="hidden" name="action" value="save_item">
      <input type="hidden" name="bagian" value="<?php echo $sec['key']; ?>">
      <p class="text-xs font-semibold text-pine/60 dark:text-cream/60 uppercase tracking-wider">Tambah Baru</p>
      <div class="grid sm:grid-cols-2 gap-3">
        <input type="text" name="judul" required placeholder="Judul..."
               class="px-3 py-2 rounded-lg bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 border-dashed focus:border-brass outline-none transition text-sm">
        <input type="text" name="tanggal" placeholder="Tanggal (opsional)..."
               class="px-3 py-2 rounded-lg bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 border-dashed focus:border-brass outline-none transition text-sm">
      </div>
      <textarea name="isi" rows="2" placeholder="Isi..."
                class="w-full px-3 py-2 rounded-lg bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15 border-dashed focus:border-brass outline-none transition text-sm"></textarea>
      <div class="flex justify-end">
        <button type="submit" class="btn-action px-4 py-2 rounded-xl bg-leaf/10 text-leaf hover:bg-leaf/20 font-semibold text-sm transition">+ Tambah</button>
      </div>
    </form>
  </div>
  <?php endforeach; ?>
</div>

<?php include 'admin_foot.php'; ?>
