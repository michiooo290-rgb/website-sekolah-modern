<?php
/**
 * setup.php — Jalankan SEKALI untuk mengatur password admin awal.
 * Akses: http://localhost/website_sekolah_modern/admin/setup.php
 *
 * HAPUS FILE INI setelah selesai!
 */

// ── Security: Block if not local environment ──────────
$allowedEnvs = ['local', 'development'];
$currentEnv  = getenv('APP_ENV') ?: 'local';
if (!in_array($currentEnv, $allowedEnvs)) {
    http_response_code(403);
    exit('403 Forbidden');
}

require_once __DIR__ . '/../config/koneksi.php';

session_start();

// Block if already logged in and password is already set
$admin = db()->prepare('SELECT password FROM admin WHERE username = ? LIMIT 1');
$admin->execute(['admin']);
$row = $admin->fetch();

if ($row && $row['password'] !== 'CHANGE_ME') {
    // Password already set
        echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Setup</title></head><body style='font-family:sans-serif;padding:40px;text-align:center'>";
        echo "<h2 style='color:#0E3B2E'>Password admin sudah diatur.</h2>";
        echo "<p>Silakan <a href='login.php'>login di sini</a>.</p>";
        echo "<p style='color:#c00;font-weight:bold;margin-top:20px'>⚠ Hapus file <code>setup.php</code> dari folder <code>admin/</code> demi keamanan.</p>";
        echo "</body></html>";
        exit;
}

$success = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password  = $_POST['password']  ?? '';
    $password2 = $_POST['password2'] ?? '';

    if (strlen($password) < 8) {
        $error = 'Password minimal 8 karakter.';
    } elseif (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password)) {
        $error = 'Password harus mengandung huruf besar, huruf kecil, dan angka.';
    } elseif ($password !== $password2) {
        $error = 'Konfirmasi password tidak cocok.';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = db()->prepare('UPDATE admin SET password = ? WHERE username = ?');
        $stmt->execute([$hash, 'admin']);
        $success = 'Password admin berhasil diatur! Anda sekarang bisa login.';

        // Auto-delete this setup file for security
        $selfPath = __FILE__;
        if (is_writable($selfPath)) {
            unlink($selfPath);
            $success .= ' File setup.php telah dihapus otomatis untuk keamanan.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Setup Admin — SMA Putra Persada</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script nonce="<?php echo $cspNonce ?? ''; ?>" src="../assets/js/tailwind.js"></script>
  <style type="text/tailwindcss">
    @theme {
      --color-pine: #0E3B2E;
      --color-pine-deep: #08291F;
      --color-leaf: #2F7D52;
      --color-cream: #F7F3E9;
      --color-cream-deep: #EFE8D6;
      --color-brass: #C9A227;
      --color-brass-light: #E0BC45;
      --font-serif: 'Fraunces', Georgia, serif;
      --font-sans: 'Plus Jakarta Sans', system-ui, sans-serif;
    }
  </style>
</head>
<body class="min-h-dvh antialiased bg-cream text-pine font-sans flex items-center justify-center px-5">
  <div class="fixed inset-0 pointer-events-none overflow-hidden -z-10">
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-brass/20 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-leaf/15 rounded-full blur-3xl"></div>
  </div>

  <div class="w-full max-w-md">
    <div class="text-center mb-10">
      <div class="inline-flex items-center gap-3 mb-4">
        <img src="../assets/img/logo.jpeg" alt="Logo" class="w-14 h-14 rounded-xl object-cover shadow-md">
        <div class="text-left">
          <h1 class="font-serif text-xl font-bold text-pine leading-tight">Putra Persada</h1>
          <p class="text-xs tracking-widest text-leaf uppercase">SMA · Batam</p>
        </div>
      </div>
      <p class="text-sm text-pine/60">Setup Awal Admin</p>
    </div>

    <div class="bg-white/70 backdrop-blur-sm rounded-2xl ring-1 ring-pine/10 p-8 shadow-lg shadow-pine/5">
      <h2 class="font-serif text-2xl font-bold mb-1">Atur Password Admin</h2>
      <p class="text-sm text-pine/60 mb-8">Buat password untuk akun admin Anda. Ini hanya perlu dilakukan sekali.</p>

      <?php if ($error): ?>
        <div class="mb-6 flex items-start gap-3 p-4 rounded-xl bg-red-50 text-red-700 text-sm ring-1 ring-red-200">
          <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          <span><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></span>
        </div>
      <?php endif; ?>

      <?php if ($success): ?>
        <div class="mb-6 flex items-start gap-3 p-4 rounded-xl bg-green-50 text-green-700 text-sm ring-1 ring-green-200">
          <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          <span><?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?></span>
        </div>
        <a href="login.php"
           class="block w-full text-center py-3 px-6 rounded-xl bg-brass hover:bg-brass-light text-pine-deep font-semibold text-sm
                  shadow-md shadow-brass/20 hover:shadow-lg hover:shadow-brass/30
                  transition duration-300 active:scale-[0.98]">
          Lanjut Login →
        </a>
        <p class="text-xs text-red-600 font-semibold mt-4 text-center">⚠ Hapus file <code>setup.php</code> dari folder <code>admin/</code> setelah login berhasil.</p>
      <?php else: ?>
        <form method="POST" class="space-y-5">
          <div>
            <label for="username_display" class="block text-sm font-semibold mb-2">Username</label>
            <input type="text" id="username_display" value="admin" disabled
                   class="w-full px-4 py-3 rounded-xl bg-gray-100 border border-pine/15 text-sm text-pine/60 cursor-not-allowed">
            <p class="text-xs text-pine/40 mt-1">Username tidak dapat diubah.</p>
          </div>

          <div>
            <label for="password" class="block text-sm font-semibold mb-2">Password Baru</label>
            <input type="password" id="password" name="password" required minlength="6" autofocus
                   placeholder="Minimal 6 karakter"
                   class="w-full px-4 py-3 rounded-xl bg-cream border border-pine/15
                          focus:border-brass focus:ring-2 focus:ring-brass/20 outline-none transition text-sm">
          </div>

          <div>
            <label for="password2" class="block text-sm font-semibold mb-2">Konfirmasi Password</label>
            <input type="password" id="password2" name="password2" required minlength="6"
                   placeholder="Ketik ulang password"
                   class="w-full px-4 py-3 rounded-xl bg-cream border border-pine/15
                          focus:border-brass focus:ring-2 focus:ring-brass/20 outline-none transition text-sm">
          </div>

          <button type="submit"
                  class="w-full py-3 px-6 rounded-xl bg-brass hover:bg-brass-light text-pine-deep font-semibold text-sm
                         shadow-md shadow-brass/20 hover:shadow-lg hover:shadow-brass/30
                         transition duration-300 active:scale-[0.98]">
            Simpan Password
          </button>
        </form>
      <?php endif; ?>
    </div>

    <p class="text-center text-xs text-pine/40 mt-8">
      &copy; <?php echo date('Y'); ?> SMA Putra Persada Batam
    </p>
  </div>
</body>
</html>
