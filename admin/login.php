<?php
require_once __DIR__ . '/auth.php';

// Redirect if already logged in
if (!empty($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

// CSRF token
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF
    if (!isset($_POST['csrf_token'], $_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $_POST['csrf_token'])) {
        $error = 'Token keamanan tidak valid. Silakan muat ulang halaman.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

        if ($username === '' || $password === '') {
            $error = 'Username dan password wajib diisi.';
        } elseif (!check_login_attempts($username, $ip)) {
            $error = 'Terlalu banyak percobaan login. Coba lagi dalam 15 menit.';
        } else {
            $stmt = db()->prepare('SELECT id, username, password, nama, role FROM admin WHERE username = ? LIMIT 1');
            $stmt->execute([$username]);
            $admin = $stmt->fetch();

            if ($admin && password_verify($password, $admin['password'])) {
                clear_login_attempts($ip);
                $_SESSION['admin_id']    = $admin['id'];
                $_SESSION['admin_nama']  = $admin['nama'];
                $_SESSION['admin_role']  = $admin['role'];
                $_SESSION['admin_user']  = $admin['username'];
                session_regenerate_id(true);
                header('Location: dashboard.php');
                exit;
            }
            record_login_attempt($username, $ip);
            $error = 'Username atau password salah.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Admin — SMA Putra Persada</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4.3.0/dist/index.global.js" integrity="sha384-nWTzRTCY/9V4Bo352ehygr1c4cnst4XN6lMR3fipakEQrhVpc0hEM5Dii3Amz0sT" crossorigin="anonymous"></script>
  <style type="text/tailwindcss">
    @custom-variant dark (&:where(.dark, .dark *));
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
  <script>
    document.documentElement.classList.toggle('dark',
      window.matchMedia('(prefers-color-scheme: dark)').matches);
  </script>
</head>
<body class="min-h-dvh antialiased bg-cream dark:bg-pine-deep text-pine dark:text-cream font-sans flex items-center justify-center px-5">
  <!-- Decorative blobs -->
  <div class="fixed inset-0 pointer-events-none overflow-hidden -z-10">
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-brass/20 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-leaf/15 rounded-full blur-3xl"></div>
  </div>

  <div class="w-full max-w-md">
    <!-- Logo -->
    <div class="text-center mb-10">
      <div class="inline-flex items-center gap-3 mb-4">
        <img src="../assets/img/logo.jpeg" alt="Logo" class="w-14 h-14 rounded-xl object-cover shadow-md">
        <div class="text-left">
          <h1 class="font-serif text-xl font-bold text-pine dark:text-cream leading-tight">Putra Persada</h1>
          <p class="text-xs tracking-widest text-leaf dark:text-brass-light uppercase">SMA · Batam</p>
        </div>
      </div>
      <p class="text-sm text-pine/60 dark:text-cream/60">Panel Administrasi</p>
    </div>

    <!-- Login Card -->
    <div class="bg-white/70 dark:bg-pine/50 backdrop-blur-sm rounded-2xl ring-1 ring-pine/10 dark:ring-cream/10 p-8 shadow-lg shadow-pine/5">
      <h2 class="font-serif text-2xl font-bold mb-1">Masuk</h2>
      <p class="text-sm text-pine/60 dark:text-cream/60 mb-8">Gunakan akun admin Anda</p>

      <?php if ($error): ?>
        <div class="mb-6 flex items-start gap-3 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300 text-sm ring-1 ring-red-200 dark:ring-red-800/40 transition">
          <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          <span><?php echo esc($error); ?></span>
        </div>
      <?php endif; ?>

      <form method="POST" class="space-y-5">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf']; ?>">
        <div>
          <label for="username" class="block text-sm font-semibold mb-2">Username</label>
          <input type="text" id="username" name="username" required autofocus
                 value="<?php echo esc($_POST['username'] ?? ''); ?>"
                 class="w-full px-4 py-3 rounded-xl bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15
                        focus:border-brass focus:ring-2 focus:ring-brass/20 outline-none transition text-sm">
        </div>

        <div>
          <label for="password" class="block text-sm font-semibold mb-2">Password</label>
          <input type="password" id="password" name="password" required
                 class="w-full px-4 py-3 rounded-xl bg-cream dark:bg-pine-deep border border-pine/15 dark:border-cream/15
                        focus:border-brass focus:ring-2 focus:ring-brass/20 outline-none transition text-sm">
        </div>

        <button type="submit"
                class="w-full py-3 px-6 rounded-xl bg-brass hover:bg-brass-light text-pine-deep font-semibold text-sm
                       shadow-md shadow-brass/20 hover:shadow-lg hover:shadow-brass/30
                       transition duration-300 active:scale-[0.98]">
          Masuk
        </button>
      </form>
    </div>

    <p class="text-center text-xs text-pine/40 dark:text-cream/40 mt-8">
      &copy; <?php echo date('Y'); ?> SMA Putra Persada Batam
    </p>
  </div>
</body>
</html>
