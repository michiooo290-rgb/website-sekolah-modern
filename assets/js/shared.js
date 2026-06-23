// Shared chrome (header + footer) — multi-page site
const NAV = [
  ['index.php','Beranda'],
  ['tentang.php','Tentang'],
  ['visi-misi.php','Visi & Misi'],
  ['ekstrakurikuler.php','Ekstrakurikuler'],
  ['berita.php','Berita'],
  ['ppdb.php','PPDB'],
  ['kontak.php','Kontak'],
];

function renderHeader(active){
  active = active || '';
  return `
<div class="bg-pine text-cream/90 text-xs py-2 relative z-20">
  <div class="marquee"><div class="marquee__track">
    ✦ Penerimaan Peserta Didik Baru TA 2026/2027 telah dibuka &nbsp;•&nbsp; Jalur Prestasi, Reguler & Beasiswa &nbsp;•&nbsp; Akreditasi A &nbsp;•&nbsp; SMA Putra Persada Batam — Cerdas, Berkarakter, Berakhlak &nbsp;•&nbsp; Hubungi (0778) XXX-XXXX &nbsp;✦&nbsp; Penerimaan Peserta Didik Baru TA 2026/2027 telah dibuka &nbsp;•&nbsp; Jalur Prestasi, Reguler & Beasiswa &nbsp;•&nbsp;
  </div></div>
</div>
<header id="topnav" class="sticky top-0 z-30 transition-all">
  <div class="max-w-6xl mx-auto px-5 h-[72px] flex items-center justify-between">
    <a href="index.php" class="flex items-center gap-3">
      <img src="assets/img/logo.jpeg" class="h-10 w-10 rounded-full object-cover ring-1 ring-brass/60">
      <div class="leading-none">
        <p class="font-serif font-semibold text-pine dark:text-cream text-[17px]">Putra Persada</p>
        <p class="text-[10px] tracking-[0.3em] text-leaf dark:text-brass-light mt-1">SMA · BATAM</p>
      </div>
    </a>
    <nav class="hidden md:flex items-center gap-6 text-[13px] font-medium">
      ${NAV.map(([h,l])=>`<a href="${h}" class="elink hover:text-leaf dark:hover:text-brass-light ${h===active?'text-leaf dark:text-brass-light':''}">${l}</a>`).join('')}
    </nav>
    <a href="ppdb.php" class="hidden md:inline-flex items-center gap-2 bg-pine dark:bg-brass text-cream dark:text-pine-deep text-[13px] font-semibold px-5 py-2.5 rounded-full hover:bg-pine-deep dark:hover:bg-brass-light transition">Daftar PPDB</a>
    <button id="mbtn" class="md:hidden p-2 text-pine dark:text-cream"><svg width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 7h18M4 13h18M4 19h18" stroke-linecap="round"/></svg></button>
  </div>
  <div id="mnav" class="md:hidden bg-cream dark:bg-pine-deep border-t border-pine/10 dark:border-cream/10">
    <div class="px-5 py-3 flex flex-col gap-1">
      ${NAV.map(([h,l])=>`<a href="${h}" class="mlink py-2.5 border-b border-pine/5 dark:border-cream/5">${l}</a>`).join('')}
      <a href="ppdb.php" class="mlink mt-2 text-center bg-pine dark:bg-brass text-cream dark:text-pine-deep font-semibold py-2.5 rounded-full">Daftar PPDB</a>
    </div>
  </div>
</header>`;
}

function renderFooter(){
  return `
<footer id="kontak" class="relative z-10 bg-pine-deep text-cream">
  <div class="max-w-6xl mx-auto px-5 py-16 grid md:grid-cols-12 gap-10">
    <div class="md:col-span-5">
      <div class="flex items-center gap-3 mb-5">
        <img src="assets/img/logo.jpeg" class="h-12 w-12 rounded-full object-cover ring-1 ring-brass/60">
        <div><p class="font-serif text-lg">SMA Putra Persada</p><p class="text-[10px] tracking-[0.3em] text-brass-light">BATAM</p></div>
      </div>
      <p class="text-cream/70 text-sm max-w-xs mb-5">Cerdas, berkarakter, dan berakhlak mulia — membersamai generasi menuju masa depan terbaik.</p>
      <div class="flex gap-3">${['Instagram','YouTube','TikTok','Facebook'].map(s=>`<a href="#" class="text-xs border border-cream/25 px-3 py-1.5 rounded-full hover:bg-cream hover:text-pine-deep transition">${s}</a>`).join('')}</div>
    </div>
    <div class="md:col-span-3">
      <p class="text-brass-light text-xs font-semibold tracking-widest uppercase mb-4">Navigasi</p>
      <ul class="space-y-2.5 text-sm text-cream/80">${NAV.map(([h,l])=>`<li><a href="${h}" class="elink hover:text-brass-light">${l}</a></li>`).join('')}</ul>
    </div>
    <div class="md:col-span-4">
      <p class="text-brass-light text-xs font-semibold tracking-widest uppercase mb-4">Kontak</p>
      <ul class="space-y-2.5 text-sm text-cream/80">
        <li>📍 Kota Batam, Kepulauan Riau</li>
        <li>📞 (0778) XXX-XXXX</li>
        <li>✉️ info@smaputrapersada.sch.id</li>
        <li>🕐 Senin–Jumat, 07.00–15.00 WIB</li>
      </ul>
      <form onsubmit="event.preventDefault(); alert('Terima kasih! (demo)'); this.reset();" class="mt-5 flex gap-2">
        <input required type="email" placeholder="Email Anda" class="flex-1 bg-pine border border-cream/15 rounded-full px-4 py-2.5 text-sm outline-none focus:border-brass placeholder:text-cream/40">
        <button class="bg-brass text-pine-deep font-semibold px-4 py-2.5 rounded-full hover:bg-brass-light transition text-sm">Kirim</button>
      </form>
    </div>
  </div>
  <div class="border-t border-cream/10 py-5 text-center text-xs text-cream/50">© 2026 SMA Putra Persada Batam · Seluruh hak cipta dilindungi.</div>
</footer>`;
}

/* Reusable banner for sub-pages */
function pageBanner(breadcrumb, label, title, desc){
  return `
<section class="relative z-10 bg-gradient-to-br from-pine to-pine-deep text-cream overflow-hidden">
  <div class="absolute -top-10 -right-10 w-52 h-52 rounded-full bg-brass/15 blur-3xl"></div>
  <div class="max-w-6xl mx-auto px-5 py-16 sm:py-24 relative z-10">
    <nav class="text-xs text-cream/60 mb-5"><a href="index.php" class="hover:text-brass-light">Beranda</a> <span class="mx-1">/</span> <span class="text-brass-light">${breadcrumb}</span></nav>
    <p class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest text-brass-light uppercase mb-4"><span class="h-px w-8 bg-brass"></span> ${label}</p>
    <h1 class="font-serif text-4xl sm:text-6xl leading-tight max-w-3xl">${title}</h1>
    ${desc ? `<p class="text-cream/80 mt-5 max-w-xl">${desc}</p>` : ''}
  </div>
</section>`;
}

/* Reusable CTA block */
function pageCTA(heading, text, btnLabel){
  return `
<section class="relative z-10 max-w-6xl mx-auto px-5 py-20 sm:py-28">
  <div class="rounded-[2rem] bg-gradient-to-br from-leaf to-pine text-cream px-8 sm:px-14 py-14 text-center relative overflow-hidden reveal">
    <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full bg-brass/20 blur-3xl"></div>
    <h2 class="font-serif text-3xl sm:text-5xl leading-tight max-w-2xl mx-auto">${heading}</h2>
    <p class="text-cream/80 mt-5 max-w-lg mx-auto">${text}</p>
    <a href="ppdb.php" class="inline-block mt-8 bg-brass text-pine-deep font-semibold px-8 py-3.5 rounded-full hover:bg-brass-light transition">${btnLabel}</a>
  </div>
</section>`;
}

function onScroll(){ const t = document.getElementById('topnav'); if (t) t.classList.toggle('scrolled', scrollY > 20); }

function initChrome(){
  addEventListener('scroll', onScroll);
  const mbtn = document.getElementById('mbtn'), mnav = document.getElementById('mnav');
  if (mbtn) mbtn.addEventListener('click', ()=> mnav.classList.toggle('open'));
  document.querySelectorAll('.mlink').forEach(a=> a.addEventListener('click', ()=> mnav.classList.remove('open')));
  const io = new IntersectionObserver(es=> es.forEach(e=>{ if(e.isIntersecting) e.target.classList.add('show'); }), {threshold:.12});
  document.querySelectorAll('.reveal').forEach(el=> io.observe(el));

  /* Blur-fade animation (once on scroll) */
  const bfIO = new IntersectionObserver(es=> es.forEach(e=>{ if(e.isIntersecting){ e.target.classList.add('show'); bfIO.unobserve(e.target); } }), {threshold:0});
  document.querySelectorAll('.blur-fade').forEach(el=> bfIO.observe(el));

  /* Counter animation */
  const counterEls = document.querySelectorAll('[data-counter]');
  if (counterEls.length) {
    const counterIO = new IntersectionObserver(entries=>{
      entries.forEach(entry=>{
        if(!entry.isIntersecting) return;
        const el = entry.target;
        counterIO.unobserve(el);
        const raw = el.getAttribute('data-counter');
        const match = raw.match(/^([\d.]+)/);
        if(!match) return;
        const target = parseFloat(match[1]);
        const suffix = raw.slice(match[1].length);
        const decimals = match[1].includes('.') ? match[1].split('.')[1].length : 0;
        const dur = 1200;
        const start = performance.now();
        function tick(now){
          const p = Math.min((now - start) / dur, 1);
          const ease = 1 - Math.pow(1 - p, 3);
          el.textContent = (target * ease).toFixed(decimals).replace(/\.0+$/,'') + (p >= 1 ? suffix : '');
          if(p < 1) requestAnimationFrame(tick);
        }
        requestAnimationFrame(tick);
      });
    }, {threshold:.3});
    counterEls.forEach(el=> counterIO.observe(el));
  }

  document.querySelectorAll('.acc-trigger').forEach(btn=>{
    btn.addEventListener('click', ()=>{
      const item = btn.closest('.acc-item');
      const open = item.classList.contains('open');
      document.querySelectorAll('.acc-item').forEach(i=> i.classList.remove('open'));
      if(!open) item.classList.add('open');
    });
  });
}

/* Wire header + footer + paper texture + initChrome */
function wireHeaderFooter(activePage){
  document.getElementById('site-header').innerHTML = renderHeader(activePage);
  document.getElementById('site-footer').innerHTML = renderFooter();
  document.body.classList.add('paper');
  initChrome();
  initLenis();
}

/* ── Lenis Smooth Scroll ──────────────────────────── */
function initLenis(){
  if(typeof Lenis === 'undefined') return;

  const lenis = new Lenis({
    duration: 1.2,
    easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
    touchMultiplier: 1.5,
    infinite: false,
  });

  // Sync Lenis with scroll events (untuk header sticky, dll)
  lenis.on('scroll', () => {
    onScroll();
  });

  // Animation frame loop
  function raf(time) {
    lenis.raf(time);
    requestAnimationFrame(raf);
  }
  requestAnimationFrame(raf);

  // Handle anchor links (smooth scroll ke section)
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', (e) => {
      const target = document.querySelector(anchor.getAttribute('href'));
      if (target) {
        e.preventDefault();
        lenis.scrollTo(target);
      }
    });
  });
}
