<?php
/**
 * Admin authentication & helpers
 * — session guard, CSRF, flash messages
 */
require_once __DIR__ . '/../config/koneksi.php';

session_start();

/* ── Auth guard ───────────────────────────────────── */
function require_login(): void {
    if (empty($_SESSION['admin_id'])) {
        header('Location: login.php');
        exit;
    }
}

/* ── Role-based access control ────────────────────── */
function require_role(string ...$roles): void {
    require_login();
    $userRole = $_SESSION['admin_role'] ?? 'editor';
    if (!in_array($userRole, $roles)) {
        flash('error', 'Anda tidak memiliki akses untuk halaman ini.');
        header('Location: dashboard.php');
        exit;
    }
}

/* ── Get real client IP (behind proxy) ─────────────── */
function get_client_ip(): string {
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($ips[0]);
    }
    if (!empty($_SERVER['HTTP_X_REAL_IP'])) {
        return $_SERVER['HTTP_X_REAL_IP'];
    }
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

/* ── Brute force protection ───────────────────────── */
function ensure_login_attempts_table(): void {
    static $ensured = false;
    if ($ensured) return;
    $pdo = db();
    $pdo->prepare("CREATE TABLE IF NOT EXISTS login_attempts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL,
        ip_address VARCHAR(45) NOT NULL,
        attempted_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_username_ip (username, ip_address),
        INDEX idx_attempted (attempted_at)
    ) ENGINE=InnoDB")->execute();
    $ensured = true;
}

function check_login_attempts(string $username, string $ip): bool {
    $pdo = db();
    ensure_login_attempts_table();

    // Clean old attempts (older than 15 minutes)
    $pdo->prepare("DELETE FROM login_attempts WHERE attempted_at < DATE_SUB(NOW(), INTERVAL 15 MINUTE)")->execute();

    // Count recent attempts for this IP and username (combined protection)
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM login_attempts WHERE (ip_address = ? OR username = ?) AND attempted_at > DATE_SUB(NOW(), INTERVAL 15 MINUTE)");
    $stmt->execute([$ip, $username]);
    $attempts = $stmt->fetchColumn();

    return $attempts < 5; // Allow if less than 5 attempts
}

function record_login_attempt(string $username, string $ip): void {
    $pdo = db();
    $stmt = $pdo->prepare("INSERT INTO login_attempts (username, ip_address) VALUES (?, ?)");
    $stmt->execute([$username, $ip]);
}

function clear_login_attempts(string $ip): void {
    $pdo = db();
    $pdo->prepare("DELETE FROM login_attempts WHERE ip_address = ?")->execute([$ip]);
}

function admin_name(): string {
    return $_SESSION['admin_nama'] ?? 'Admin';
}

/* ── CSRF ─────────────────────────────────────────── */
function csrf_token(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

function verify_csrf(): bool {
    return isset($_POST['csrf_token'], $_SESSION['csrf'])
        && hash_equals($_SESSION['csrf'], $_POST['csrf_token']);
}

/* ── Flash messages ───────────────────────────────── */
function flash(string $key, string $msg): void {
    $_SESSION['flash'][$key] = $msg;
}

function get_flash(string $key): ?string {
    $msg = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $msg;
}

/* ── Upload helper ────────────────────────────────── */
function upload_gambar(array $file, string $dir, array $allowed = ['jpg','jpeg','png','webp']): ?string {
    if ($file['error'] !== UPLOAD_ERR_OK) return null;
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed)) return null;
    if ($file['size'] > 5 * 1024 * 1024) return null; // 5 MB

    // Verify actual image content (magic bytes)
    $imageInfo = @getimagesize($file['tmp_name']);
    if ($imageInfo === false) return null;
    $validMimes = ['image/jpeg', 'image/png', 'image/webp'];
    if (!in_array($imageInfo['mime'], $validMimes)) return null;

    // Verify extension matches actual MIME type
    $mimeToExt = [
        'image/jpeg' => ['jpg', 'jpeg'],
        'image/png'  => ['png'],
        'image/webp' => ['webp'],
    ];
    $validExts = $mimeToExt[$imageInfo['mime']] ?? [];
    if (!in_array($ext, $validExts)) return null;

    $name = uniqid() . '_' . time() . '.' . $ext;
    $dest = $dir . '/' . $name;
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    move_uploaded_file($file['tmp_name'], $dest);
    return $name;
}

/* ── Slug generator ───────────────────────────────── */
function make_slug(string $text): string {
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    return rtrim($text, '-');
}
