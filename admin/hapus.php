<?php
/**
 * Generic delete handler (POST only, CSRF required)
 * Usage: <form method="POST" action="hapus.php">
 *          <input type="hidden" name="t" value="berita">
 *          <input type="hidden" name="id" value="123">
 *        </form>
 */
require_once 'auth.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_csrf()) {
    flash('error', 'Permintaan tidak valid.');
    header('Location: dashboard.php');
    exit;
}

$table = $_POST['t'] ?? '';
$id    = (int)($_POST['id'] ?? 0);

$allowed = [
    'berita'    => ['table' => 'berita',           'page' => 'kelola_berita.php',    'roles' => ['editor','superadmin']],
    'guru'      => ['table' => 'guru',             'page' => 'kelola_guru.php',      'roles' => ['superadmin']],
    'ekskul'    => ['table' => 'ekstrakurikuler',  'page' => 'kelola_ekskul.php',    'roles' => ['editor','superadmin']],
    'visi_misi' => ['table' => 'visi_misi',        'page' => 'kelola_visimisi.php',  'roles' => ['superadmin']],
    'tentang'   => ['table' => 'tentang',          'page' => 'kelola_tentang.php',   'roles' => ['superadmin']],
    'ppdb'      => ['table' => 'ppdb_info',        'page' => 'kelola_ppdb.php',      'roles' => ['superadmin']],
    'pesan'     => ['table' => 'pesan_kontak',     'page' => 'pesan_masuk.php',      'roles' => ['editor','superadmin']],
];

if (!isset($allowed[$table]) || $id <= 0) {
    flash('error', 'Permintaan tidak valid.');
    header('Location: dashboard.php');
    exit;
}

$target = $allowed[$table];

// Role-based access control
$userRole = $_SESSION['admin_role'] ?? 'editor';
if (!in_array($userRole, $target['roles'])) {
    flash('error', 'Anda tidak memiliki akses untuk menghapus data ini.');
    header('Location: ' . $target['page']);
    exit;
}
$pdo = db();
$stmt = $pdo->prepare("DELETE FROM {$target['table']} WHERE id=?");
$stmt->execute([$id]);

flash('success', 'Data berhasil dihapus.');
header('Location: ' . $target['page']);
exit;
