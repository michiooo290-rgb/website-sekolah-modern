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
        $ip = get_client_ip();

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
  <script nonce="<?php echo $cspNonce ?? ''; ?>" src="../assets/js/tailwind.js"></script>
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
  <style>
    .fade-up{opacity:0;transform:translateY(16px);animation:fadeUp .6s cubic-bezier(.2,.7,.3,1) forwards}
    .fade-up.d1{animation-delay:.08s}
    .fade-up.d2{animation-delay:.16s}
    .fade-up.d3{animation-delay:.24s}
    .fade-up.d4{animation-delay:.32s}
    @keyframes fadeUp{to{opacity:1;transform:none}}
    @keyframes floaty{0%,100%{transform:translateY(0)}50%{transform:translateY(-14px)}}
    .floaty{animation:floaty 7s ease-in-out infinite}
    .floaty.slow{animation-duration:10s}
    /* shimmering grain pattern overlay */
    .brand-pattern{background-image:radial-gradient(circle at 1px 1px, rgba(247,243,233,.18) 1px, transparent 0);background-size:22px 22px}
  </style>
</head>
<body class="min-h-dvh antialiased bg-cream dark:bg-pine-deep text-pine dark:text-cream font-sans">
  <div class="min-h-dvh grid lg:grid-cols-2">

    <!-- ============ Brand panel (hidden on small screens) ============ -->
    <aside class="relative hidden lg:flex flex-col justify-between overflow-hidden bg-pine-deep text-cream p-12 xl:p-16">
      <!-- gradient + pattern background -->
      <div class="absolute inset-0 bg-gradient-to-br from-pine via-pine-deep to-pine-deep"></div>
      <div class="absolute inset-0 brand-pattern opacity-60"></div>
      <div class="absolute -top-32 -right-24 w-96 h-96 bg-brass/20 rounded-full blur-3xl floaty"></div>
      <div class="absolute -bottom-40 -left-24 w-[28rem] h-[28rem] bg-leaf/25 rounded-full blur-3xl floaty slow"></div>

      <!-- top: logo -->
      <div class="relative fade-up">
        <div class="inline-flex items-center gap-3">
          <img src="../assets/img/logo.jpeg" alt="Logo" class="w-12 h-12 rounded-xl object-cover ring-2 ring-brass/40 shadow-lg">
          <div>
            <h1 class="font-serif text-lg font-bold leading-tight">SMA Putra Persada</h1>
            <p class="text-[11px] tracking-[0.25em] text-brass-light/80 uppercase">Batam</p>
          </div>
        </div>
      </div>

      <!-- middle: headline -->
      <div class="relative max-w-md">
        <span class="fade-up d1 inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brass/15 ring-1 ring-brass/30 text-brass-light text-xs font-semibold tracking-wide mb-6">
          <span class="w-1.5 h-1.5 rounded-full bg-brass-light animate-pulse"></span>
          Panel Administrasi
        </span>
        <h2 class="fade-up d2 font-serif text-4xl xl:text-5xl font-bold leading-[1.1] mb-5">
          Kelola sekolah<br>dalam satu tempat.
        </h2>
        <p class="fade-up d3 text-cream/70 leading-relaxed">
          Atur berita, data guru, ekstrakurikuler, PPDB, dan pesan masuk dengan mudah melalui dasbor terpusat.
        </p>
      </div>

      <!-- bottom: quick stats / footer -->
      <div class="relative fade-up d4 flex items-center gap-8 text-sm">
        <div>
          <p class="font-serif text-2xl font-bold text-brass-light">9</p>
          <p class="text-cream/50 text-xs">Modul</p>
        </div>
        <div class="w-px h-8 bg-cream/15"></div>
        <div>
          <p class="font-serif text-2xl font-bold text-brass-light">24/7</p>
          <p class="text-cream/50 text-xs">Akses Aman</p>
        </div>
        <div class="w-px h-8 bg-cream/15"></div>
        <div>
          <p class="font-serif text-2xl font-bold text-brass-light">v1.0</p>
          <p class="text-cream/50 text-xs">Versi Panel</p>
        </div>
      </div>
    </aside>

    <!-- ============ Form panel ============ -->
    <main class="relative flex items-center justify-center px-5 py-12 sm:px-8">
      <!-- decorative blobs for mobile/light side -->
      <div class="fixed inset-0 pointer-events-none overflow-hidden -z-10 lg:hidden">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-brass/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-leaf/15 rounded-full blur-3xl"></div>
      </div>

      <div class="w-full max-w-md">
        <!-- Mobile logo -->
        <div class="lg:hidden text-center mb-8 fade-up">
          <div class="inline-flex items-center gap-3">
            <img src="../assets/img/logo.jpeg" alt="Logo" class="w-12 h-12 rounded-xl object-cover shadow-md">
            <div class="text-left">
              <h1 class="font-serif text-lg font-bold leading-tight">Putra Persada</h1>
              <p class="text-[10px] tracking-widest text-leaf dark:text-brass-light uppercase">SMA · Batam</p>
            </div>
          </div>
        </div>

        <!-- Heading -->
        <div class="mb-8 fade-up d1">
          <h2 class="font-serif text-3xl font-bold mb-2">Selamat datang</h2>
          <p class="text-sm text-pine/60 dark:text-cream/60">Masuk ke panel admin dengan akun Anda.</p>
        </div>

        <!-- Error -->
        <?php if ($error): ?>
          <div class="mb-6 flex items-start gap-3 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300 text-sm ring-1 ring-red-200 dark:ring-red-800/40 fade-up">
            <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span><?php echo esc($error); ?></span>
          </div>
        <?php endif; ?>

        <!-- Form -->
        <form method="POST" class="space-y-5 fade-up d2" autocomplete="off">
          <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf']; ?>">

          <div>
            <label for="username" class="block text-sm font-semibold mb-2">Username</label>
            <div class="relative">
              <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-pine/35 dark:text-cream/35 pointer-events-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
              </span>
              <input type="text" id="username" name="username" required autofocus
                     value="<?php echo esc($_POST['username'] ?? ''); ?>"
                     placeholder="Masukkan username"
                     class="w-full pl-11 pr-4 py-3 rounded-xl bg-white/70 dark:bg-pine/50 border border-pine/15 dark:border-cream/15
                            placeholder:text-pine/30 dark:placeholder:text-cream/30
                            focus:border-brass focus:ring-2 focus:ring-brass/25 outline-none transition text-sm">
            </div>
          </div>

          <div>
            <div class="flex items-center justify-between mb-2">
              <label for="password" class="block text-sm font-semibold">Password</label>
            </div>
            <div class="relative">
              <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-pine/35 dark:text-cream/35 pointer-events-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
              </span>
              <input type="password" id="password" name="password" required
                     placeholder="Masukkan password"
                     class="w-full pl-11 pr-12 py-3 rounded-xl bg-white/70 dark:bg-pine/50 border border-pine/15 dark:border-cream/15
                            placeholder:text-pine/30 dark:placeholder:text-cream/30
                            focus:border-brass focus:ring-2 focus:ring-brass/25 outline-none transition text-sm">
              <button type="button" id="togglePw" aria-label="Tampilkan password"
                      class="absolute right-2 top-1/2 -translate-y-1/2 p-2 rounded-lg text-pine/40 dark:text-cream/40 hover:text-brass hover:bg-brass/10 transition">
                <svg id="eyeOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <svg id="eyeClosed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.243 4.243L9.88 9.88"/></svg>
              </button>
            </div>
          </div>

          <button type="submit"
                  class="group w-full py-3.5 px-6 rounded-xl bg-brass hover:bg-brass-light text-pine-deep font-bold text-sm
                         shadow-lg shadow-brass/25 hover:shadow-xl hover:shadow-brass/35
                         transition duration-300 active:scale-[0.98] flex items-center justify-center gap-2">
            Masuk
            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
          </button>
        </form>

        <!-- Security note -->
        <div class="mt-7 flex items-center justify-center gap-2 text-xs text-pine/45 dark:text-cream/45 fade-up d3">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.746 3.746 0 0121 12z"/></svg>
          Koneksi aman & terenkripsi
        </div>

        <p class="text-center text-xs text-pine/40 dark:text-cream/40 mt-8 fade-up d4">
          &copy; <?php echo date('Y'); ?> SMA Putra Persada Batam
        </p>
      </div>
    </main>
  </div>

  <script nonce="<?php echo $cspNonce ?? ''; ?>">
    const pw = document.getElementById('password');
    const btn = document.getElementById('togglePw');
    const eyeOpen = document.getElementById('eyeOpen');
    const eyeClosed = document.getElementById('eyeClosed');
    btn.addEventListener('click', () => {
      const show = pw.type === 'password';
      pw.type = show ? 'text' : 'password';
      eyeOpen.classList.toggle('hidden', show);
      eyeClosed.classList.toggle('hidden', !show);
      pw.focus();
    });
  </script>
</body>
</html>
