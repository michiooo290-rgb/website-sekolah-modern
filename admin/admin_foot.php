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

    /* Auto-dismiss flash after 5s */
    setTimeout(() => {
      const f = document.getElementById('flash-msg');
      if (f) { f.style.transition = 'opacity .3s'; f.style.opacity = '0'; setTimeout(() => f.remove(), 300); }
    }, 5000);

    /* Confirm delete */
    function confirmDelete(msg) { return confirm(msg || 'Yakin ingin menghapus?'); }
  </script>
</body>
</html>
