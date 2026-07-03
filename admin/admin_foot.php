    </main>

    <!-- Admin footer -->
    <footer class="border-t border-pine/8 dark:border-cream/8 px-5 lg:px-8 py-4 text-xs text-pine/40 dark:text-cream/40 flex items-center justify-between">
      <span>&copy; <?php echo date('Y'); ?> SMA Putra Persada Batam</span>
      <span>Panel Admin v1.0</span>
    </footer>
  </div>

  <script>
    /* Sidebar toggle */
    function toggleSidebar() {
      const sb = document.getElementById('sidebar');
      const ov = document.getElementById('sidebar-overlay');
      sb.classList.toggle('-translate-x-full');
      ov.classList.toggle('hidden');
    }

    /* Ganti tema terang/gelap & simpan pilihan ke localStorage */
    function toggleTheme() {
      const isDark = document.documentElement.classList.toggle('dark');
      localStorage.setItem('admin-theme', isDark ? 'dark' : 'light');
    }

    /* Auto-dismiss flash after 5s (dukung lebih dari satu notifikasi) */
    setTimeout(() => {
      document.querySelectorAll('.flash-msg').forEach(f => {
        f.style.transition = 'opacity .3s'; f.style.opacity = '0'; setTimeout(() => f.remove(), 300);
      });
    }, 5000);

    /* ── Konfirmasi hapus via modal kustom ── */
    (function () {
      let pendingForm = null;

      // Bangun elemen modal sekali saja
      const modal = document.createElement('div');
      modal.id = 'confirm-modal';
      modal.innerHTML =
        '<div class="cm-overlay" data-cm-cancel></div>' +
        '<div class="cm-box" role="dialog" aria-modal="true">' +
          '<div class="cm-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg></div>' +
          '<p class="cm-title">Konfirmasi Hapus</p>' +
          '<p class="cm-msg"></p>' +
          '<div class="cm-actions">' +
            '<button type="button" class="cm-btn cm-cancel" data-cm-cancel>Batal</button>' +
            '<button type="button" class="cm-btn cm-confirm">Ya, Hapus</button>' +
          '</div>' +
        '</div>';
      document.body.appendChild(modal);

      const msgEl = modal.querySelector('.cm-msg');

      function closeModal() { modal.classList.remove('open'); pendingForm = null; }
      function openModal(form, msg) {
        pendingForm = form;
        msgEl.textContent = msg || 'Tindakan ini tidak dapat dibatalkan.';
        modal.classList.add('open');
        modal.querySelector('.cm-confirm').focus();
      }

      modal.querySelectorAll('[data-cm-cancel]').forEach(el => el.addEventListener('click', closeModal));
      modal.querySelector('.cm-confirm').addEventListener('click', () => {
        const f = pendingForm;
        closeModal();
        if (f) { f.dataset.cmConfirmed = '1'; f.submit(); } // submit() melewati listener -> tidak loop
      });
      document.addEventListener('keydown', e => { if (e.key === 'Escape' && modal.classList.contains('open')) closeModal(); });

      // Listener submit terdelegasi: form dengan [data-confirm] minta konfirmasi dulu
      document.addEventListener('submit', e => {
        const form = e.target;
        if (!form.matches('[data-confirm]')) return;
        if (form.dataset.cmConfirmed === '1') { delete form.dataset.cmConfirmed; return; }
        e.preventDefault();
        openModal(form, form.getAttribute('data-confirm'));
      }, true);

      // Kompatibilitas: pola lama confirmDelete(msg)
      window.confirmDelete = function (msg) {
        const form = (window.event && window.event.target) ? window.event.target.closest('form') : null;
        if (form) { openModal(form, msg); }
        return false;
      };
    })();

    /* ── Cegah klik-dobel: kunci tombol submit setelah form berhasil dikirim ── */
    document.addEventListener('submit', function (e) {
      const form = e.target;
      if (e.defaultPrevented) return;             // dibatalkan oleh validasi / konfirmasi
      if (form.matches('[data-confirm]')) return; // form hapus ditangani lewat modal
      if (form.dataset.submitting === '1') { e.preventDefault(); return; }
      form.dataset.submitting = '1';
      const btns = form.querySelectorAll('button[type="submit"], button:not([type]), input[type="submit"]');
      // Nonaktifkan setelah data terkirim (timeout 0) agar nilai tombol tetap ikut terkirim
      setTimeout(function () {
        btns.forEach(function (b) {
          b.disabled = true;
          b.style.opacity = '.65';
          b.style.cursor = 'wait';
          b.setAttribute('aria-busy', 'true');
        });
      }, 0);
    }, false);
  </script>
</body>
</html>
