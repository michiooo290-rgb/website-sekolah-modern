/* ===================== DATA (homepage) ===================== */
const stats = [['B.','Akreditasi'],['750+','Peserta Didik'],['45+','Pengajar']];

const keunggulan = [
  ['Akademik Unggul','Kurikulum Merdeka dengan bimbingan intensif persiapan UTBK/SNBT menuju PTN favorit.'],
  ['Karakter Islami','Pembiasaan shalat dhuha, tahfiz, dan pembinaan akhlak mulia sebagai napas keseharian.'],
  ['Bakat & Prestasi','25+ ekstrakurikuler serta pembinaan olimpiade, seni, dan olahraga tingkat provinsi.'],
  ['Lingkungan Nyaman','Fasilitas modern, guru pendamping, dan suasana belajar yang aman dan menyenangkan.'],
];
const program = [
  ['01','Peminatan IPS','Ekonomi · Geografi · Sosiologi · Sejarah — jalur hukum, bisnis, dan sosial.'],
];
const agenda = [
  ['15 Jun','Pembukaan PPDB 2026/2027','Pendaftaran jalur prestasi & reguler resmi dibuka.'],
  ['28 Jun','Tes Seleksi Calon Siswa','Tes akademik dan wawancara peserta didik baru.'],
  ['10 Jul','Pengumuman Hasil Seleksi','Pengumuman & daftar ulang siswa yang diterima.'],
  ['15 Jul','Masa Pengenalan Sekolah','MPLS bagi siswa baru tahun ajaran 2026/2027.'],
];
const kabar = [
  ['Prestasi','12 Jun 2026','Siswa Raih Emas Olimpiade Sains Provinsi Kepri','Perwakilan sekolah kembali mengharumkan nama di ajang OSN tingkat provinsi tahun ini.','osn-emas'],
  ['Kegiatan','08 Jun 2026','Pentas Seni & Perpisahan Kelas XII Berlangsung Meriah','Acara perpisahan yang meriah dengan pertunjukan seni dari siswa-siswi kelas XII.','pentas-seni'],
  ['Keagamaan','03 Jun 2026','Shalat Dhuha & Tahfiz Rutin Setiap Pagi','Kegiatan keagamaan pagi yang menjadi rutinitas siswa.','shalat-dhuha'],
  ['Beasiswa','20 Apr 2026','Siswa Berprestasi Raih Beasiswa Penuh Bina Lingkungan','Penghargaan bagi siswa berprestasi dari lingkungan sekolah.','beasiswa'],
];
const guru = [
  ['guru1.jpeg','Budi Santoso, M.Pd.','Kepala Sekolah'],
  ['guru2.jpeg','Siti Aminah, S.Pd.','Guru Matematika'],
  ['guru3.jpeg','Ahmad Fauzi, M.Pd.','Guru Fisika'],
  ['guru4.jpeg','Maya Kartika, S.Pd.','Guru Bahasa Inggris'],
  ['placeholder.jpeg','Rina Wulandari, S.Pd.','Guru Kimia'],
  ['placeholder.jpeg','Dedi Kurniawan, M.Pd.','Guru Biologi'],
  ['placeholder.jpeg','Lestari Putri, S.E., M.Pd.','Guru Ekonomi'],
  ['placeholder.jpeg','Hendra Gunawan, S.Pd.','Guru Bahasa Indonesia'],
  ['placeholder.jpeg','Nurhayati, S.Ag., M.Pd.','Guru PAI'],
];
const faq = [
  ['Kapan pendaftaran PPDB dibuka?','PPDB tahun ajaran 2026/2027 dibuka mulai 15 Juni 2026 melalui jalur prestasi, reguler, dan beasiswa.'],
  ['Apa saja peminatan yang tersedia?','Saat ini tersedia peminatan IPS (Ilmu Pengetahuan Sosial) dengan pendampingan intensif menuju PTN.'],
  ['Apakah ada program keagamaan?','Ya. Pembiasaan shalat dhuha, tahfiz Al-Quran, dan kajian rutin menjadi bagian keseharian siswa.'],
  ['Bagaimana cara menghubungi sekolah?','Anda dapat menghubungi kami melalui telepon, email, atau mengisi formulir pada halaman Kontak.'],
];

/* ===================== RENDER HOME ===================== */
function renderHomeContent(){
  return `
<section id="beranda" class="relative z-10 max-w-6xl mx-auto px-5 pt-12 sm:pt-16 pb-10">
  <div class="grid lg:grid-cols-12 gap-10 items-center">
    <div class="lg:col-span-7 reveal show">
      <p class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest text-leaf dark:text-brass-light uppercase mb-5"><span class="h-px w-8 bg-brass"></span> Sekolah Menengah Atas</p>
      <h1 class="font-serif font-semibold text-pine dark:text-cream leading-[1.05] text-[40px] sm:text-6xl lg:text-[68px]">Menumbuhkan<br><span class="italic text-leaf dark:text-brass-light">ilmu</span> & <span class="italic text-leaf dark:text-brass-light">akhlak</span><br>yang berbuah.</h1>
      <p class="mt-6 text-pine/70 dark:text-cream/70 max-w-md text-[15px] leading-relaxed">SMA Putra Persada Batam — ruang tumbuh bagi generasi cerdas dan berkarakter islami, siap melangkah ke perguruan tinggi terbaik.</p>
      <div class="mt-8 flex flex-wrap items-center gap-4">
        <a href="ppdb.php" class="bg-brass text-pine-deep font-semibold px-7 py-3.5 rounded-full hover:bg-brass-light transition">Pendaftaran 2026/2027</a>
        <a href="tentang.php" class="elink font-semibold text-pine dark:text-cream">Pelajari lebih lanjut →</a>
      </div>
    </div>
    <div class="lg:col-span-5 reveal show">
      <div class="relative mx-auto max-w-sm">
        <div class="absolute -top-6 -left-6 w-24 h-24 rounded-full bg-brass/30 blur-2xl"></div>
        <div class="absolute -bottom-8 -right-4 w-32 h-32 rounded-full bg-leaf/30 blur-2xl"></div>
        <div class="rounded-[2.5rem] overflow-hidden bg-gradient-to-b from-leaf to-pine p-12 flex items-center justify-center aspect-square shadow-2xl ring-1 ring-pine/10">
          <img src="logo.jpeg" alt="Logo SMA Putra Persada Batam" class="w-48 h-48 object-contain drop-shadow-2xl">
        </div>
        <div class="absolute -bottom-5 left-1/2 -translate-x-1/2 bg-cream dark:bg-pine-deep px-5 py-2.5 rounded-full shadow-lg ring-1 ring-pine/10 dark:ring-cream/10 text-xs font-semibold text-pine dark:text-cream whitespace-nowrap">Terakreditasi A</div>
      </div>
    </div>
  </div>
</section>

<section class="relative z-10 border-y border-pine/10 dark:border-cream/10">
  <div class="max-w-6xl mx-auto px-5 grid grid-cols-2 md:grid-cols-3 divide-x divide-pine/10 dark:divide-cream/10">
    ${stats.map(([n,l])=>`<div class="py-7 text-center"><p class="font-serif text-4xl text-pine dark:text-brass-light">${n}</p><p class="text-xs tracking-wide text-pine/60 dark:text-cream/60 mt-1 uppercase">${l}</p></div>`).join('')}
  </div>
</section>

<section id="tentang" class="relative z-10 max-w-6xl mx-auto px-5 py-20 sm:py-28">
  <div class="grid lg:grid-cols-2 gap-14 items-center">
    <div class="reveal">
      <div class="rounded-[2rem] bg-gradient-to-br from-pine to-pine-deep text-cream p-10 shadow-xl">
        <p class="font-serif text-6xl text-brass-light leading-none mb-2">"</p>
        <blockquote class="font-serif text-2xl sm:text-3xl leading-snug">Mendidik bukan sekadar mengisi gelas, tetapi menyalakan api semangat.</blockquote>
        <div class="mt-10 grid grid-cols-2 gap-6 border-t border-cream/15 pt-6">
          <div><p class="font-serif text-3xl text-brass-light">25+</p><p class="text-xs text-cream/60 mt-1">tahun mendidik</p></div>
          <div><p class="font-serif text-3xl text-brass-light">750+</p><p class="text-xs text-cream/60 mt-1">peserta didik</p></div>
        </div>
      </div>
    </div>
    <div class="reveal">
      <p class="text-xs font-semibold tracking-widest text-leaf dark:text-brass-light uppercase mb-4">Tentang Kami</p>
      <h2 class="font-serif text-3xl sm:text-4xl text-pine dark:text-cream leading-tight mb-5">Sekolah yang menyeimbangkan prestasi dan iman.</h2>
      <p class="text-pine/70 dark:text-cream/70 leading-relaxed mb-4">SMA Putra Persada Batam hadir untuk membentuk generasi yang cerdas secara akademik, kuat dalam karakter, dan teguh dalam keimanan. Setiap siswa kami dampingi untuk menemukan potensi terbaiknya.</p>
      <p class="text-pine/70 dark:text-cream/70 leading-relaxed mb-6">Dengan guru profesional dan lingkungan belajar yang mendukung, kami berkomitmen mengantar setiap siswa menuju masa depan terbaiknya.</p>
      <div class="grid grid-cols-2 gap-x-6 gap-y-3 text-sm">
        ${['Visi global, akar islami','Guru profesional','Fasilitas lengkap','Lingkungan aman'].map(t=>`<p class="flex items-center gap-2 text-pine/80 dark:text-cream/80"><span class="text-brass">✦</span> ${t}</p>`).join('')}
      </div>
      <a href="visi-misi.php" class="inline-block mt-6 elink font-semibold text-sm text-leaf dark:text-brass-light">Lihat Visi & Misi lengkap →</a>
    </div>
  </div>
</section>

<section class="relative z-10 bg-pine text-cream">
  <div class="max-w-6xl mx-auto px-5 py-20 sm:py-28">
    <div class="reveal max-w-xl mb-14">
      <p class="text-xs font-semibold tracking-widest text-brass-light uppercase mb-4">Mengapa Kami</p>
      <h2 class="font-serif text-3xl sm:text-4xl leading-tight">Empat alasan keluarga mempercayakan pendidikan di sini.</h2>
    </div>
    <div class="grid sm:grid-cols-2 gap-px bg-cream/10 rounded-2xl overflow-hidden reveal">
      ${keunggulan.map(([t,d],i)=>`<div class="bg-pine p-8 hover:bg-pine-deep transition group"><p class="numdot text-brass-light text-3xl mb-4">0${i+1}</p><h3 class="font-serif text-xl mb-2">${t}</h3><p class="text-cream/70 text-sm leading-relaxed">${d}</p></div>`).join('')}
    </div>
  </div>
</section>

<section id="program" class="relative z-10 max-w-6xl mx-auto px-5 py-20 sm:py-28">
  <div class="reveal text-center max-w-2xl mx-auto mb-16">
    <p class="text-xs font-semibold tracking-widest text-leaf dark:text-brass-light uppercase mb-4">Program Akademik</p>
    <h2 class="font-serif text-3xl sm:text-4xl text-pine dark:text-cream">Peminatan unggulan, satu tujuan: masa depanmu.</h2>
  </div>
  <div class="grid md:grid-cols-1 max-w-xl mx-auto gap-8 reveal">
    ${program.map(([n,t,d])=>`<div class="group relative text-center"><p class="font-serif text-7xl text-brass/25 dark:text-brass/40 leading-none mb-2">${n}</p><h3 class="font-serif text-2xl text-pine dark:text-cream mb-3">${t}</h3><p class="text-pine/70 dark:text-cream/70 text-sm leading-relaxed mb-4">${d}</p><div class="mt-6 h-px bg-pine/10 dark:bg-cream/10 group-hover:bg-brass transition"></div></div>`).join('')}
  </div>
  <p class="text-center mt-12 reveal"><a href="ekstrakurikuler.php" class="elink text-sm font-semibold text-leaf dark:text-brass-light">Lihat juga kegiatan ekstrakurikuler →</a></p>
</section>

<section id="guru" class="relative z-10 bg-pine-deep text-cream">
  <div class="max-w-6xl mx-auto px-5 py-20 sm:py-28">
    <div class="reveal text-center max-w-2xl mx-auto mb-14">
      <p class="text-xs font-semibold tracking-widest text-brass-light uppercase mb-3">Tenaga Pendidik</p>
      <h2 class="font-serif text-3xl sm:text-4xl">Guru & Pengajar Kami</h2>
      <p class="text-cream/70 mt-4 text-sm">Dibina oleh pendidik profesional yang berdedikasi membersamai setiap siswa.</p>
    </div>
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-6 reveal">
      ${guru.map(([img,n,role])=>`<figure class="group text-center"><div class="relative overflow-hidden rounded-2xl aspect-[3/4] ring-1 ring-cream/10 mb-4 bg-pine"><img src="${img}" alt="${n}" class="w-full h-full object-cover object-top group-hover:scale-105 transition duration-500"><div class="absolute inset-x-0 bottom-0 h-1/3 bg-gradient-to-t from-pine-deep/70 to-transparent"></div></div><figcaption><p class="font-serif text-lg text-cream leading-snug">${n}</p><p class="text-xs text-brass-light tracking-wide mt-0.5">${role}</p></figcaption></figure>`).join('')}
    </div>
  </div>
</section>

<section id="agenda" class="relative z-10 bg-cream-deep dark:bg-pine">
  <div class="max-w-6xl mx-auto px-5 py-20 sm:py-28 grid lg:grid-cols-3 gap-12">
    <div class="reveal">
      <p class="text-xs font-semibold tracking-widest text-leaf dark:text-brass-light uppercase mb-4">Agenda</p>
      <h2 class="font-serif text-3xl sm:text-4xl text-pine dark:text-cream leading-tight">Jadwal penting penerimaan siswa baru.</h2>
      <p class="text-pine/70 dark:text-cream/70 mt-4 text-sm">Catat tanggalnya dan jangan sampai terlewat.</p>
    </div>
    <div class="lg:col-span-2 reveal">
      <div class="relative pl-8 border-l-2 border-pine/15 dark:border-cream/15 space-y-8">
        ${agenda.map(([d,t,desc])=>`<div class="relative"><span class="absolute -left-[41px] top-1 w-4 h-4 rounded-full bg-brass ring-4 ring-cream-deep dark:ring-pine"></span><p class="font-serif text-brass dark:text-brass-light text-lg">${d}</p><h3 class="font-semibold text-pine dark:text-cream mt-0.5">${t}</h3><p class="text-sm text-pine/60 dark:text-cream/60">${desc}</p></div>`).join('')}
      </div>
    </div>
  </div>
</section>

<section id="kabar" class="relative z-10 max-w-6xl mx-auto px-5 py-20 sm:py-28">
  <div class="reveal flex items-end justify-between mb-12">
    <div><p class="text-xs font-semibold tracking-widest text-leaf dark:text-brass-light uppercase mb-3">Kabar Sekolah</p><h2 class="font-serif text-3xl sm:text-4xl text-pine dark:text-cream">Berita & Kegiatan Terbaru</h2></div>
    <a href="berita.php" class="hidden sm:inline elink font-semibold text-sm">Semua kabar →</a>
  </div>
  <div class="grid lg:grid-cols-2 gap-10 reveal">
    <a href="berita-detail.html?id=${kabar[0][4]}" class="group block bg-cream-deep dark:bg-pine rounded-[1.5rem] p-8 ring-1 ring-pine/10 dark:ring-cream/10">
      <span class="inline-block bg-brass text-pine-deep text-[11px] font-bold tracking-wide uppercase px-3 py-1 rounded-full mb-4">${kabar[0][0]}</span>
      <p class="text-xs text-pine/50 dark:text-cream/50 mb-2">${kabar[0][1]}</p>
      <h3 class="font-serif text-2xl sm:text-3xl text-pine dark:text-cream mb-3 leading-snug group-hover:text-leaf dark:group-hover:text-brass-light transition">${kabar[0][2]}</h3>
      <p class="text-pine/70 dark:text-cream/70 text-sm leading-relaxed mb-5">${kabar[0][3]}</p>
      <span class="elink text-sm font-semibold text-leaf dark:text-brass-light">Baca selengkapnya →</span>
    </a>
    <div class="flex flex-col divide-y divide-pine/10 dark:divide-cream/10">
      ${kabar.slice(1).map(([cat,date,title,,slug])=>`<a href="berita-detail.html?id=${slug}" class="group flex items-start gap-4 py-5 first:pt-0"><span class="numdot text-2xl text-brass/50 dark:text-brass/60 leading-none shrink-0 mt-1">✦</span><div><span class="text-[11px] font-semibold text-brass dark:text-brass-light tracking-wide uppercase">${cat} · ${date}</span><h3 class="font-serif text-lg text-pine dark:text-cream leading-snug mt-1 group-hover:text-leaf dark:group-hover:text-brass-light transition">${title}</h3></div></a>`).join('')}
    </div>
  </div>
</section>

<section class="relative z-10 max-w-3xl mx-auto px-5 py-20 sm:py-28">
  <div class="reveal text-center mb-12"><p class="text-xs font-semibold tracking-widest text-leaf dark:text-brass-light uppercase mb-3">Pertanyaan Umum</p><h2 class="font-serif text-3xl sm:text-4xl text-pine dark:text-cream">Yang sering ditanyakan.</h2></div>
  <div class="reveal divide-y divide-pine/10 dark:divide-cream/10 border-y border-pine/10 dark:border-cream/10">
    ${faq.map(([q,a])=>`<div class="acc-item"><button class="acc-trigger w-full flex items-center justify-between gap-4 py-5 text-left"><span class="font-serif text-lg text-pine dark:text-cream">${q}</span><span class="acc-icon shrink-0 text-2xl text-brass transition-transform">+</span></button><div class="acc-body"><p class="pb-5 text-pine/70 dark:text-cream/70 text-sm leading-relaxed">${a}</p></div></div>`).join('')}
  </div>
</section>

<section id="ppdb" class="relative z-10 max-w-6xl mx-auto px-5 pb-20 sm:pb-28">
  ${pageCTA('Mulai perjalananmu bersama Putra Persada.','Kuota terbatas untuk Tahun Ajaran 2026/2027. Tersedia jalur prestasi, reguler, dan beasiswa.','Daftar Sekarang')}
</section>`;
}
