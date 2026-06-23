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
    'berita'    => ['table' => 'berita',           'page' => 'kelola_berita.php'],
    'guru'      => ['table' => 'guru',             'page' => 'kelola_guru.php'],
    'ekskul'    => ['table' => 'ekstrakurikuler',  'page' => 'kelola_ekskul.php'],
    'visi_misi' => ['table' => 'visi_misi',        'page' => 'kelola_visimisi.php'],
    'tentang'   => ['table' => 'tentang',          'page' => 'kelola_tentang.php'],
    'ppdb'      => ['table' => 'ppdb_info',        'page' => 'kelola_ppdb.php'],
    'pesan'     => ['table' => 'pesan_kontak',     'page' => 'pesan_masuk.php'],
];

if (!isset($allowed[$table]) || $id <= 0) {
    flash('error', 'Permintaan tidak valid.');
    header('Location: dashboard.php');
    exit;
}

$target = $allowed[$table];
$pdo = db();
$stmt = $pdo->prepare("DELETE FROM {$target['table']} WHERE id=?");
$stmt->execute([$id]);

flash('success', 'Data berhasil dihapus.');
header('Location: ' . $target['page']);
exit;
