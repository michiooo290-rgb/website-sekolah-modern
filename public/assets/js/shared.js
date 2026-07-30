// Shared chrome (header + footer) — multi-page site
const NAV = [
  ['/','Beranda'],
  ['/tentang','Tentang'],
  ['/guru','Guru'],
  ['/visi-misi','Visi & Misi'],
  ['/ekstrakurikuler','Ekstrakurikuler'],
  ['/berita','Berita'],
  ['/ppdb','PPDB'],
  ['/kontak','Kontak'],
];

function renderHeader(active, ppdbOpen){
  active = active || '';
  if (typeof ppdbOpen === 'undefined') ppdbOpen = true;
  const marqueeOpen = '<span class="marquee-ribbon-item"><span>✦</span> Penerimaan Peserta Didik Baru TA 2026/2027</span><span class="marquee-ribbon-item">•</span><span class="marquee-ribbon-item">Jalur Prestasi, Reguler & Beasiswa</span><span class="marquee-ribbon-item">•</span><span class="marquee-ribbon-item"><span>✦</span> Akreditasi C</span><span class="marquee-ribbon-item">•</span><span class="marquee-ribbon-item">SMAS Putra Persada Batam</span><span class="marquee-ribbon-item">•</span><span class="marquee-ribbon-item">Cerdas, Berkarakter, Berakhlak</span><span class="marquee-ribbon-item">•</span>';
  const marqueeClosed = '<span class="marquee-ribbon-item"><span>✦</span> PPDB 2026/2027 Telah Ditutup</span><span class="marquee-ribbon-item">•</span><span class="marquee-ribbon-item">Informasi Tahun Ajaran Berikutnya Segera Hadir</span><span class="marquee-ribbon-item">•</span><span class="marquee-ribbon-item"><span>✦</span> Akreditasi C</span><span class="marquee-ribbon-item">•</span><span class="marquee-ribbon-item">SMAS Putra Persada Batam</span><span class="marquee-ribbon-item">•</span><span class="marquee-ribbon-item">Hubungi Kami untuk Informasi Lebih Lanjut</span><span class="marquee-ribbon-item">•</span>';
  return `
<div class="sticky top-0 z-30">
  <div class="bg-pine text-cream/90 text-xs py-2 relative marquee-ribbon">
    <div class="marquee-ribbon-row">
      ${Array(3).fill(ppdbOpen ? marqueeOpen : marqueeClosed).join('')}
    </div>
  </div>
  <header id="topnav" class="transition-all">
    <div class="max-w-6xl mx-auto px-5 h-[72px] flex items-center justify-between">
      <a href="/" class="flex items-center gap-3">
        <img src="/assets/img/logo.jpeg" class="h-10 w-10 rounded-full object-cover ring-1 ring-brass/60">
        <div class="leading-none">
          <p class="font-serif font-semibold text-pine dark:text-cream text-[17px]">Putra Persada</p>
          <p class="text-[10px] tracking-[0.3em] text-leaf dark:text-brass-light mt-1">SMAS · BATAM</p>
        </div>
      </a>
      <nav class="hidden md:flex items-center gap-6 text-[13px] font-medium">
        ${NAV.map(([h,l])=>`<a href="${h}" class="elink hover:text-leaf dark:hover:text-brass-light ${h===active?'text-leaf dark:text-brass-light':''}">${l}</a>`).join('')}
      </nav>
      <div class="flex items-center gap-2">
        <button id="themeToggle" aria-label="Ganti tema terang/gelap" title="Ganti tema terang/gelap" class="p-2 rounded-full text-pine dark:text-cream hover:bg-pine/10 dark:hover:bg-cream/10 transition">
          <svg class="dark:hidden" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
          <svg class="hidden dark:block" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/></svg>
        </button>
        <a href="/ppdb" class="highlight-btn header-ppdb-btn hidden md:inline-flex items-center gap-2 text-[13px] font-semibold px-5 py-2.5 rounded-full transition"><span class="hl-label">${ppdbOpen ? 'Daftar PPDB' : 'Info PPDB'}</span></a>
        <button id="mbtn" class="md:hidden p-2 text-pine dark:text-cream"><svg width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 7h18M4 13h18M4 19h18" stroke-linecap="round"/></svg></button>
      </div>
    </div>
    <div id="mnav" class="md:hidden bg-cream dark:bg-pine-deep border-t border-pine/10 dark:border-cream/10">
      <div class="px-5 py-3 flex flex-col gap-1">
        ${NAV.map(([h,l])=>`<a href="${h}" class="mlink py-2.5 border-b border-pine/5 dark:border-cream/5">${l}</a>`).join('')}
        <a href="/ppdb" class="mlink header-ppdb-btn mt-2 text-center font-semibold py-2.5 rounded-full">${ppdbOpen ? 'Daftar PPDB' : 'Info PPDB'}</a>
      </div>
    </div>
  </header>
</div>`;
}

function renderFooter(){
  return `
<footer id="kontak" class="relative z-10 bg-pine-deep text-cream">
  <div class="max-w-6xl mx-auto px-5 py-16 grid md:grid-cols-12 gap-10">
    <div class="md:col-span-5">
      <div class="flex items-center gap-3 mb-5">
        <img src="/assets/img/logo.jpeg" class="h-12 w-12 rounded-full object-cover ring-1 ring-brass/60">
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
  <div class="border-t border-cream/10 py-5 text-center text-xs text-cream/50">© 2026 SMAS Putra Persada Batam · Seluruh hak cipta dilindungi.</div>
</footer>`;
}

/* Reusable banner for sub-pages */
function pageBanner(breadcrumb, label, title, desc){
  return `
<section class="relative z-10 bg-gradient-to-br from-pine to-pine-deep text-cream overflow-hidden">
  <div class="absolute -top-10 -right-10 w-52 h-52 rounded-full bg-brass/15 blur-3xl"></div>
  <div class="max-w-6xl mx-auto px-5 py-16 sm:py-24 relative z-10">
    <nav class="text-xs text-cream/60 mb-5"><a href="/" class="hover:text-brass-light">Beranda</a> <span class="mx-1">/</span> <span class="text-brass-light">${breadcrumb}</span></nav>
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
    <a href="/ppdb" class="inline-block mt-8 bg-brass text-pine-deep font-semibold px-8 py-3.5 rounded-full hover:bg-brass-light transition">${btnLabel}</a>
  </div>
</section>`;
}

function onScroll(){ const t = document.getElementById('topnav'); if (t) t.classList.toggle('scrolled', scrollY > 20); }

function setTheme(isDark, persist){
  const root = document.documentElement;
  root.classList.add('theme-switching');
  root.classList.toggle('dark', isDark);
  root.style.colorScheme = isDark ? 'dark' : 'light';
  if(persist !== false) localStorage.setItem('theme', isDark ? 'dark' : 'light');
  const button = document.getElementById('themeToggle');
  if(button){
    button.setAttribute('aria-pressed', isDark ? 'true' : 'false');
    button.setAttribute('aria-label', isDark ? 'Gunakan tema terang' : 'Gunakan tema gelap');
    button.setAttribute('title', isDark ? 'Gunakan tema terang' : 'Gunakan tema gelap');
  }
  requestAnimationFrame(()=> requestAnimationFrame(()=> root.classList.remove('theme-switching')));
}

function initChrome(){
  /* Hindari event scroll ganda setelah navigasi client-side Next.js. */
  removeEventListener('scroll', onScroll);
  addEventListener('scroll', onScroll, {passive:true});
  onScroll();
  const mbtn = document.getElementById('mbtn'), mnav = document.getElementById('mnav');
  if (mbtn) mbtn.addEventListener('click', ()=> mnav.classList.toggle('open'));
  const tbtn = document.getElementById('themeToggle');
  setTheme(document.documentElement.classList.contains('dark'), false);
  if (tbtn) tbtn.addEventListener('click', ()=> setTheme(!document.documentElement.classList.contains('dark'), true));
  document.querySelectorAll('.mlink').forEach(a=> a.addEventListener('click', ()=> mnav.classList.remove('open')));
  const io = new IntersectionObserver(es=> es.forEach(e=>{ if(e.isIntersecting) e.target.classList.add('show'); }), {threshold:.12});
  document.querySelectorAll('.reveal').forEach(el=> io.observe(el));

  /* Blur-fade animation (once on scroll) */
  const bfIO = new IntersectionObserver(es=> es.forEach(e=>{ if(e.isIntersecting){ e.target.classList.add('show'); bfIO.unobserve(e.target); } }), {threshold:0});
  document.querySelectorAll('.blur-fade').forEach(el=> bfIO.observe(el));

  /* Counter animation — iOS-style per-digit blur (21st.dev inspired) */
  const counterEls = document.querySelectorAll('[data-counter]');
  if (counterEls.length) {
    /* Build digit spans once */
    function buildDigits(el, raw){
      const match = raw.match(/^([\d.]+)/);
      if(!match) return null;
      const target = parseFloat(match[1]);
      const suffix = raw.slice(match[1].length);
      const decimals = match[1].includes('.') ? match[1].split('.')[1].length : 0;
      const fmt = (v) => (v).toFixed(decimals).replace(/\.0+$/,'');
      const paddedTarget = fmt(target);
      const digits = paddedTarget.split('');
      /* Clear element using DOM methods */
      while(el.firstChild) el.removeChild(el.firstChild);
      const spans = [];
      digits.forEach(ch=>{
        const s = document.createElement('span');
        s.className = 'abn-digit';
        s.textContent = ch === '.' ? '.' : '0';
        el.appendChild(s);
        spans.push({el:s, isDigit:ch !== '.', targetChar:ch});
      });
      if(suffix){
        const sf = document.createElement('span');
        sf.textContent = suffix;
        sf.style.marginLeft = '2px';
        el.appendChild(sf);
      }
      return {spans, target, fmt, decimals};
    }

    const counterIO = new IntersectionObserver(entries=>{
      entries.forEach(entry=>{
        if(!entry.isIntersecting) return;
        const el = entry.target;
        counterIO.unobserve(el);
        const raw = el.getAttribute('data-counter');
        const data = buildDigits(el, raw);
        if(!data) return;

        const dur = 1200;
        const start = performance.now();
        let prevFormatted = data.fmt(0);

        function tick(now){
          const p = Math.min((now - start) / dur, 1);
          const ease = 1 - Math.pow(1 - p, 3);
          const currentFormatted = data.fmt(data.target * ease);

          /* Compare each digit, blur only changed ones */
          for(let i = 0; i < data.spans.length; i++){
            const sp = data.spans[i];
            if(!sp.isDigit) continue;
            const newChar = currentFormatted[i] || '0';
            const oldChar = prevFormatted[i] || '0';
            if(newChar !== oldChar){
              sp.el.classList.add('abn-changing');
              sp.el.textContent = newChar;
              /* Remove blur after short delay (spring settle) */
              setTimeout(()=>{
                sp.el.classList.remove('abn-changing');
                sp.el.classList.add('abn-settling');
                setTimeout(()=> sp.el.classList.remove('abn-settling'), 350);
              }, 80);
            }
          }
          prevFormatted = currentFormatted;

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

  /* ── Dia Text / Word Cycling (21st.dev inspired) ── */
  document.querySelectorAll('.dia-text').forEach(el=>{
    const words = (el.getAttribute('data-words') || '').split(',').map(s=>s.trim()).filter(Boolean);
    if(words.length < 2) return;
    const interval = parseInt(el.getAttribute('data-interval') || '2500', 10);
    el.textContent = '';
    const wrap = document.createElement('span');
    wrap.className = 'dia-text-wrap';
    el.appendChild(wrap);
    const wordEls = words.map((w,i)=>{
      const s = document.createElement('span');
      s.className = 'dia-text-word' + (i === 0 ? ' dia-active' : '');
      s.textContent = w;
      wrap.appendChild(s);
      return s;
    });
    let idx = 0;
    setInterval(()=>{
      const curr = wordEls[idx];
      idx = (idx + 1) % words.length;
      const next = wordEls[idx];
      curr.classList.remove('dia-active');
      curr.classList.add('dia-exit');
      next.classList.remove('dia-exit');
      next.classList.add('dia-active');
      setTimeout(()=> curr.classList.remove('dia-exit'), 500);
    }, interval);
  });

  /* ── Highlight Buttons (21st.dev inspired) ── */
  document.querySelectorAll('.highlight-btn').forEach(btn=>{
    /* Create spotlight overlay */
    const spot = document.createElement('span');
    spot.className = 'hl-spotlight';
    btn.appendChild(spot);

    btn.addEventListener('mousemove', (e)=>{
      const rect = btn.getBoundingClientRect();
      const x = ((e.clientX - rect.left) / rect.width) * 100;
      const y = ((e.clientY - rect.top) / rect.height) * 100;
      btn.style.setProperty('--mx', x + '%');
      btn.style.setProperty('--my', y + '%');
    });

    btn.addEventListener('click', (e)=>{
      const rect = btn.getBoundingClientRect();
      const size = Math.max(rect.width, rect.height) * 0.5;
      const ripple = document.createElement('span');
      ripple.className = 'hl-ripple';
      ripple.style.width = ripple.style.height = size + 'px';
      ripple.style.left = (e.clientX - rect.left - size/2) + 'px';
      ripple.style.top = (e.clientY - rect.top - size/2) + 'px';
      btn.appendChild(ripple);
      ripple.addEventListener('animationend', ()=> ripple.remove());
    });
  });

  /* ── Frame Buttons (21st.dev inspired) — corner chevrons ── */
  document.querySelectorAll('.frame-btn').forEach(btn=>{
    ['fb-tl','fb-tr','fb-bl','fb-br'].forEach(cls=>{
      const corner = document.createElement('span');
      corner.className = 'fb-corner ' + cls;
      btn.appendChild(corner);
    });
  });

  /* ── Slot Text (21st.dev inspired) — per-character roll ── */
  document.querySelectorAll('.slot-text').forEach(el=>{
    const text = el.getAttribute('data-slot-text') || el.textContent;
    const origText = el.textContent;
    el.textContent = '';
    text.split('').forEach(ch=>{
      const charWrap = document.createElement('span');
      charWrap.className = 'slot-char';
      const inner = document.createElement('span');
      inner.className = 'slot-char-inner';
      inner.textContent = ch === ' ' ? ' ' : ch;
      charWrap.appendChild(inner);
      el.appendChild(charWrap);
    });
    /* On hover, roll to alternate text if data-slot-hover exists */
    const hoverText = el.getAttribute('data-slot-hover');
    if(hoverText){
      el.addEventListener('mouseenter', ()=>{
        el.classList.add('slot-rolling');
        setTimeout(()=>{
          const chars = el.querySelectorAll('.slot-char-inner');
          hoverText.split('').forEach((ch,i)=>{
            if(chars[i]) chars[i].textContent = ch === ' ' ? ' ' : ch;
          });
          el.classList.remove('slot-rolling');
        }, 300);
      });
      el.addEventListener('mouseleave', ()=>{
        el.classList.add('slot-rolling');
        setTimeout(()=>{
          const chars = el.querySelectorAll('.slot-char-inner');
          origText.split('').forEach((ch,i)=>{
            if(chars[i]) chars[i].textContent = ch === ' ' ? ' ' : ch;
          });
          el.classList.remove('slot-rolling');
        }, 300);
      });
    }
  });

  /* ── Scroll Reel Testimonials (21st.dev inspired) ── */
  /* Per-character text animation */
  document.querySelectorAll('.reel-quote').forEach(el=>{
    const text = el.textContent;
    el.textContent = '';
    text.split('').forEach((ch,i)=>{
      const span = document.createElement('span');
      span.className = 'reel-char';
      span.textContent = ch === ' ' ? ' ' : ch;
      span.style.transitionDelay = (i * 18) + 'ms';
      el.appendChild(span);
    });
  });

  /* Reveal cards on scroll + counter-rotate columns */
  const reelCards = document.querySelectorAll('.reel-card');
  if(reelCards.length){
    const reelIO = new IntersectionObserver(entries=>{
      entries.forEach(entry=>{
        if(entry.isIntersecting){
          entry.target.classList.add('reel-visible');
          reelIO.unobserve(entry.target);
        }
      });
    }, {threshold:.15});
    reelCards.forEach(c=> reelIO.observe(c));
  }

  /* Counter-rotate columns on scroll */
  const reelCols = document.querySelectorAll('.reel-col');
  if(reelCols.length){
    let ticking = false;
    function updateReel(){
      const section = document.querySelector('.reel-section');
      if(!section) return;
      const rect = section.getBoundingClientRect();
      const vh = window.innerHeight;
      const progress = Math.max(0, Math.min(1, (vh - rect.top) / (vh + rect.height)));
      reelCols.forEach((col,i)=>{
        const dir = col.classList.contains('reel-col-up') ? -1 : 1;
        const offset = (progress - 0.5) * 80 * dir;
        col.style.transform = 'translateY(' + offset + 'px)';
      });
      ticking = false;
    }
    window.addEventListener('scroll', ()=>{
      if(!ticking){ requestAnimationFrame(updateReel); ticking = true; }
    }, {passive:true});
    updateReel();
  }
}

/* Wire header + footer + paper texture + initChrome */
function wireHeaderFooter(activePage, ppdbOpen){
  document.getElementById('site-header').innerHTML = renderHeader(activePage, ppdbOpen !== false);
  document.getElementById('site-footer').innerHTML = renderFooter();
  document.body.classList.add('paper');
  initChrome();
}

/* ── Lenis Smooth Scroll ──────────────────────────── */
