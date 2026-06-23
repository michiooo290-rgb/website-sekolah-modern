const app = document.getElementById('app');
document.body.classList.add('paper');

/* ===================== DATA ===================== */
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
  ['Prestasi','12 Jun 2026','Siswa Raih Emas Olimpiade Sains Provinsi Kepri','Perwakilan sekolah kembali mengharumkan nama di ajang OSN tingkat provinsi tahun ini.'],
  ['Kegiatan','08 Jun 2026','Pentas Seni & Perpisahan Kelas XII Berlangsung Meriah',''],
  ['Keagamaan','03 Jun 2026','Shalat Dhuha & Tahfiz Rutin Setiap Pagi',''],
  ['Beasiswa','20 Apr 2026','Siswa Berprestasi Raih Beasiswa Penuh Bina Lingkungan',''],
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
  ['Apa saja peminatan yang tersedia?','Tersedia tiga peminatan: IPA, IPS, serta Bahasa & Budaya, masing-masing dengan pendampingan menuju PTN.'],
  ['Apakah ada program keagamaan?','Ya. Pembiasaan shalat dhuha, tahfiz Al-Quran, dan kajian rutin menjadi bagian keseharian siswa.'],
  ['Bagaimana cara menghubungi sekolah?','Anda dapat menghubungi kami melalui telepon, email, atau mengisi formulir pada bagian Kontak di bawah.'],
];

const misi = [
  'Menyelenggarakan pembelajaran aktif, kreatif, dan berkualitas berbasis Kurikulum Merdeka.',
  'Membina karakter islami melalui pembiasaan ibadah, tahfiz, dan keteladanan akhlak mulia.',
  'Mengembangkan potensi akademik dan non-akademik siswa secara optimal dan seimbang.',
  'Mempersiapkan lulusan yang siap bersaing menuju perguruan tinggi favorit.',
  'Menumbuhkan budaya disiplin, jujur, dan peduli terhadap sesama dan lingkungan.',
  'Menjalin kerja sama yang harmonis antara sekolah, orang tua, dan masyarakat.',
];
const tujuan = [
  ['Lulusan Berkualitas','Menghasilkan lulusan yang unggul secara akademik dan berakhlak mulia.'],
  ['Daya Saing Tinggi','Meningkatkan jumlah lulusan yang diterima di PTN dan PTS ternama.'],
  ['Karakter Kuat','Membentuk pribadi yang religius, mandiri, dan bertanggung jawab.'],
  ['Prestasi Berkelanjutan','Meraih prestasi akademik dan non-akademik tingkat daerah hingga nasional.'],
];
const nilai = [
  ['Religius','Menjadikan nilai keimanan sebagai dasar setiap perilaku.'],
  ['Integritas','Menjunjung kejujuran dan tanggung jawab dalam segala hal.'],
  ['Disiplin','Membiasakan ketertiban dan komitmen pada aturan.'],
  ['Peduli','Menumbuhkan empati terhadap sesama dan lingkungan.'],
  ['Kreatif','Mendorong inovasi dan semangat belajar tanpa henti.'],
];

const kategori = [
  ['Keagamaan','🕌','from-emerald-600 to-pine', [
    ['Rohis (Rohani Islam)','Kajian, kegiatan dakwah, dan pembinaan keislaman siswa.'],
    ['Tahfiz Al-Quran','Program menghafal dan memperbaiki bacaan Al-Quran.'],
    ['Nasyid & Marawis','Seni musik islami dan paduan suara religi.'],
  ]],
  ['Olahraga','⚽','from-leaf to-pine', [
    ['Futsal','Latihan rutin dan turnamen antar-pelajar tingkat kota.'],
    ['Basket','Pembinaan tim putra dan putri menuju kompetisi daerah.'],
    ['Bulu Tangkis','Latihan teknik dan sparring rutin setiap pekan.'],
    ['Bela Diri (Pencak Silat)','Pembinaan fisik, mental, dan sportivitas.'],
  ]],
  ['Seni & Budaya','🎨','from-amber-500 to-brass', [
    ['Paduan Suara','Latihan vokal dan tampil pada acara sekolah.'],
    ['Tari Tradisional','Melestarikan budaya melalui seni tari Nusantara.'],
    ['Teater & Musik','Pementasan drama dan band sekolah.'],
  ]],
  ['Akademik & Sains','🔬','from-teal-600 to-pine', [
    ['Klub Olimpiade (KIR)','Pembinaan menuju OSN dan kompetisi sains.'],
    ['English Club','Debat, percakapan, dan public speaking Bahasa Inggris.'],
    ['Jurnalistik','Pengelolaan mading, buletin, dan media sekolah.'],
  ]],
  ['Organisasi & Kepanduan','🚩','from-pine to-pine-deep', [
    ['OSIS','Organisasi Siswa Intra Sekolah sebagai wadah kepemimpinan.'],
    ['Pramuka','Pembinaan kemandirian, disiplin, dan kerja sama.'],
    ['Paskibra','Pasukan Pengibar Bendera dan latihan baris-berbaris.'],
    ['PMR (Palang Merah Remaja)','Pelatihan pertolongan pertama dan kepedulian sosial.'],
  ]],
];

/* ===================== PAGE: HOME ===================== */
function homeContent(){
  return `
<section id="beranda" class="relative z-10 max-w-6xl mx-auto px-5 pt-12 sm:pt-16 pb-10">
  <div class="grid lg:grid-cols-12 gap-10 items-center">
    <div class="lg:col-span-7 reveal show">
      <p class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest text-leaf dark:text-brass-light uppercase mb-5"><span class="h-px w-8 bg-brass"></span> Sekolah Menengah Atas</p>
      <h1 class="font-serif font-semibold text-pine dark:text-cream leading-[1.05] text-[40px] sm:text-6xl lg:text-[68px]">Menumbuhkan<br><span class="italic text-leaf dark:text-brass-light">ilmu</span> & <span class="italic text-leaf dark:text-brass-light">akhlak</span><br>yang berbuah.</h1>
      <p class="mt-6 text-pine/70 dark:text-cream/70 max-w-md text-[15px] leading-relaxed">SMA Putra Persada Batam — ruang tumbuh bagi generasi cerdas dan berkarakter islami, siap melangkah ke perguruan tinggi terbaik.</p>
      <div class="mt-8 flex flex-wrap items-center gap-4">
        <a href="#ppdb" class="bg-brass text-pine-deep font-semibold px-7 py-3.5 rounded-full hover:bg-brass-light transition">Pendaftaran 2026/2027</a>
        <a href="#tentang" class="elink font-semibold text-pine dark:text-cream">Pelajari lebih lanjut →</a>
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
  <div class="max-w-6xl mx-auto px-5 grid grid-cols-2 md:grid-cols-4 divide-x divide-pine/10 dark:divide-cream/10">
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
      <a href="#visi-misi" class="inline-block mt-6 elink font-semibold text-sm text-leaf dark:text-brass-light">Lihat Visi & Misi lengkap →</a>
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
    <h2 class="font-serif text-3xl sm:text-4xl text-pine dark:text-cream">Tiga peminatan, satu tujuan: masa depanmu.</h2>
  </div>
  <div class="grid md:grid-cols-3 gap-8 reveal">
    ${program.map(([n,t,d])=>`<div class="group relative"><p class="font-serif text-7xl text-brass/25 dark:text-brass/40 leading-none mb-2">${n}</p><h3 class="font-serif text-2xl text-pine dark:text-cream mb-3">${t}</h3><p class="text-pine/70 dark:text-cream/70 text-sm leading-relaxed mb-4">${d}</p><div class="mt-6 h-px bg-pine/10 dark:bg-cream/10 group-hover:bg-brass transition"></div></div>`).join('')}
  </div>
  <p class="text-center mt-12 reveal"><a href="#ekstrakurikuler" class="elink text-sm font-semibold text-leaf dark:text-brass-light">Lihat juga kegiatan ekstrakurikuler →</a></p>
</section>

<section id="guru" class="relative z-10 bg-pine-deep text-cream">
  <div class="max-w-6xl mx-auto px-5 py-20 sm:py-28">
    <div class="reveal text-center max-w-2xl mx-auto mb-14">
      <p class="text-xs font-semibold tracking-widest text-brass-light uppercase mb-3">Tenaga Pendidik</p>
      <h2 class="font-serif text-3xl sm:text-4xl">Guru & Pengajar Kami</h2>
      <p class="text-cream/70 mt-4 text-sm">Dibina oleh pendidik profesional yang berdedikasi membersamai setiap siswa.</p>
    </div>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 reveal">
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
    <a href="#" class="hidden sm:inline elink font-semibold text-sm">Semua kabar →</a>
  </div>
  <div class="grid lg:grid-cols-2 gap-10 reveal">
    <article class="group bg-cream-deep dark:bg-pine rounded-[1.5rem] p-8 ring-1 ring-pine/10 dark:ring-cream/10">
      <span class="inline-block bg-brass text-pine-deep text-[11px] font-bold tracking-wide uppercase px-3 py-1 rounded-full mb-4">${kabar[0][0]}</span>
      <p class="text-xs text-pine/50 dark:text-cream/50 mb-2">${kabar[0][1]}</p>
      <h3 class="font-serif text-2xl sm:text-3xl text-pine dark:text-cream mb-3 leading-snug group-hover:text-leaf dark:group-hover:text-brass-light transition">${kabar[0][2]}</h3>
      <p class="text-pine/70 dark:text-cream/70 text-sm leading-relaxed mb-5">${kabar[0][3]}</p>
      <a href="#" class="elink text-sm font-semibold text-leaf dark:text-brass-light">Baca selengkapnya →</a>
    </article>
    <div class="flex flex-col divide-y divide-pine/10 dark:divide-cream/10">
      ${kabar.slice(1).map(([cat,date,title])=>`<a href="#" class="group flex items-start gap-4 py-5 first:pt-0"><span class="numdot text-2xl text-brass/50 dark:text-brass/60 leading-none shrink-0 mt-1">✦</span><div><span class="text-[11px] font-semibold text-brass dark:text-brass-light tracking-wide uppercase">${cat} · ${date}</span><h3 class="font-serif text-lg text-pine dark:text-cream leading-snug mt-1 group-hover:text-leaf dark:group-hover:text-brass-light transition">${title}</h3></div></a>`).join('')}
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
  <div class="rounded-[2rem] bg-gradient-to-br from-leaf to-pine text-cream px-8 sm:px-14 py-14 text-center relative overflow-hidden reveal">
    <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full bg-brass/20 blur-3xl"></div>
    <p class="text-xs font-semibold tracking-widest text-brass-light uppercase mb-4">Penerimaan Peserta Didik Baru</p>
    <h2 class="font-serif text-3xl sm:text-5xl leading-tight max-w-2xl mx-auto">Mulai perjalananmu bersama Putra Persada.</h2>
    <p class="text-cream/80 mt-5 max-w-lg mx-auto">Kuota terbatas untuk Tahun Ajaran 2026/2027. Tersedia jalur prestasi, reguler, dan beasiswa.</p>
    <a href="#kontak" class="inline-block mt-8 bg-brass text-pine-deep font-semibold px-8 py-3.5 rounded-full hover:bg-brass-light transition">Daftar Sekarang</a>
  </div>
</section>`;
}

/* ===================== PAGE: VISI & MISI ===================== */
function visiContent(){
  return `
<section class="relative z-10 bg-gradient-to-br from-pine to-pine-deep text-cream overflow-hidden">
  <div class="absolute -top-10 -right-10 w-52 h-52 rounded-full bg-brass/15 blur-3xl"></div>
  <div class="max-w-6xl mx-auto px-5 py-16 sm:py-24 relative z-10">
    <nav class="text-xs text-cream/60 mb-5"><a href="#beranda" class="hover:text-brass-light">Beranda</a> <span class="mx-1">/</span> <span class="text-brass-light">Visi & Misi</span></nav>
    <p class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest text-brass-light uppercase mb-4"><span class="h-px w-8 bg-brass"></span> Arah & Tujuan Kami</p>
    <h1 class="font-serif text-4xl sm:text-6xl leading-tight max-w-3xl">Visi & Misi Sekolah</h1>
    <p class="text-cream/80 mt-5 max-w-xl">Landasan dan arah yang memandu setiap langkah SMA Putra Persada Batam dalam mendidik generasi.</p>
  </div>
</section>

<section class="relative z-10 max-w-5xl mx-auto px-5 py-20 sm:py-28">
  <div class="reveal text-center">
    <p class="text-xs font-semibold tracking-widest text-leaf dark:text-brass-light uppercase mb-5">Visi</p>
    <blockquote class="font-serif text-2xl sm:text-4xl leading-snug text-pine dark:text-cream max-w-3xl mx-auto"><span class="text-brass">"</span>Mewujudkan generasi yang cerdas, berkarakter, beriman, dan berdaya saing global.<span class="text-brass">"</span></blockquote>
    <div class="w-16 h-1 bg-brass rounded-full mx-auto mt-8"></div>
  </div>
</section>

<section class="relative z-10 bg-pine text-cream">
  <div class="max-w-6xl mx-auto px-5 py-20 sm:py-28">
    <div class="reveal max-w-xl mb-12"><p class="text-xs font-semibold tracking-widest text-brass-light uppercase mb-4">Misi</p><h2 class="font-serif text-3xl sm:text-4xl leading-tight">Langkah nyata mewujudkan visi.</h2></div>
    <div class="grid sm:grid-cols-2 gap-x-10 gap-y-8 reveal">
      ${misi.map((m,i)=>`<div class="flex gap-5"><span class="numdot text-brass-light text-3xl leading-none shrink-0">0${i+1}</span><p class="text-cream/85 leading-relaxed pt-1">${m}</p></div>`).join('')}
    </div>
  </div>
</section>

<section class="relative z-10 max-w-6xl mx-auto px-5 py-20 sm:py-28">
  <div class="reveal text-center max-w-2xl mx-auto mb-14"><p class="text-xs font-semibold tracking-widest text-leaf dark:text-brass-light uppercase mb-4">Tujuan</p><h2 class="font-serif text-3xl sm:text-4xl text-pine dark:text-cream">Hasil yang ingin kami capai.</h2></div>
  <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 reveal">
    ${tujuan.map(([t,d],i)=>`<div class="rounded-2xl border border-pine/10 dark:border-cream/10 p-7 hover:border-brass transition bg-cream-deep/40 dark:bg-pine/40"><p class="numdot text-brass text-3xl mb-3">0${i+1}</p><h3 class="font-serif text-lg text-pine dark:text-cream mb-2">${t}</h3><p class="text-sm text-pine/70 dark:text-cream/70 leading-relaxed">${d}</p></div>`).join('')}
  </div>
</section>

<section class="relative z-10 bg-cream-deep dark:bg-pine">
  <div class="max-w-6xl mx-auto px-5 py-20 sm:py-28">
    <div class="reveal text-center max-w-2xl mx-auto mb-14"><p class="text-xs font-semibold tracking-widest text-leaf dark:text-brass-light uppercase mb-4">Nilai-Nilai</p><h2 class="font-serif text-3xl sm:text-4xl text-pine dark:text-cream">Karakter yang kami tanamkan.</h2></div>
    <div class="flex flex-wrap justify-center gap-4 reveal">
      ${nilai.map(([t,d])=>`<div class="bg-cream dark:bg-pine-deep rounded-2xl px-6 py-5 ring-1 ring-pine/10 dark:ring-cream/10 max-w-xs"><p class="font-serif text-xl text-pine dark:text-brass-light mb-1">${t}</p><p class="text-xs text-pine/65 dark:text-cream/65 leading-relaxed">${d}</p></div>`).join('')}
    </div>
  </div>
</section>

<section class="relative z-10 max-w-6xl mx-auto px-5 py-20 sm:py-28">
  <div class="rounded-[2rem] bg-gradient-to-br from-leaf to-pine text-cream px-8 sm:px-14 py-14 text-center relative overflow-hidden reveal">
    <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full bg-brass/20 blur-3xl"></div>
    <h2 class="font-serif text-3xl sm:text-5xl leading-tight max-w-2xl mx-auto">Jadilah bagian dari visi kami.</h2>
    <p class="text-cream/80 mt-5 max-w-lg mx-auto">Bergabunglah bersama SMA Putra Persada Batam pada Tahun Ajaran 2026/2027.</p>
    <a href="#ppdb" class="inline-block mt-8 bg-brass text-pine-deep font-semibold px-8 py-3.5 rounded-full hover:bg-brass-light transition">Daftar PPDB</a>
  </div>
</section>`;
}

/* ===================== PAGE: EKSTRAKURIKULER ===================== */
function ekskulContent(){
  const total = kategori.reduce((s,k)=> s + k[3].length, 0);
  return `
<section class="relative z-10 bg-gradient-to-br from-pine to-pine-deep text-cream overflow-hidden">
  <div class="absolute -bottom-10 -left-10 w-52 h-52 rounded-full bg-leaf/25 blur-3xl"></div>
  <div class="absolute -top-10 -right-10 w-52 h-52 rounded-full bg-brass/15 blur-3xl"></div>
  <div class="max-w-6xl mx-auto px-5 py-16 sm:py-24 relative z-10">
    <nav class="text-xs text-cream/60 mb-5"><a href="#beranda" class="hover:text-brass-light">Beranda</a> <span class="mx-1">/</span> <span class="text-brass-light">Ekstrakurikuler</span></nav>
    <p class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest text-brass-light uppercase mb-4"><span class="h-px w-8 bg-brass"></span> Wadah Bakat & Minat</p>
    <h1 class="font-serif text-4xl sm:text-6xl leading-tight max-w-3xl">Ekstrakurikuler</h1>
    <p class="text-cream/80 mt-5 max-w-xl">Lebih dari ${total} kegiatan untuk mengembangkan bakat, minat, dan karakter siswa di luar jam pelajaran.</p>
    <div class="mt-8 flex flex-wrap gap-8">
      <div><p class="font-serif text-4xl text-brass-light">${kategori.length}</p><p class="text-xs text-cream/60 uppercase tracking-wide">Kategori</p></div>
      <div><p class="font-serif text-4xl text-brass-light">${total}+</p><p class="text-xs text-cream/60 uppercase tracking-wide">Pilihan Kegiatan</p></div>
    </div>
  </div>
</section>

<div class="relative z-10 max-w-6xl mx-auto px-5 py-20 sm:py-24 space-y-20">
  ${kategori.map(([nama,icon,grad,items])=>`
    <section class="reveal">
      <div class="flex items-center gap-4 mb-8">
        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br ${grad} flex items-center justify-center text-2xl shadow-lg">${icon}</div>
        <div><h2 class="font-serif text-2xl sm:text-3xl text-pine dark:text-cream">${nama}</h2><p class="text-xs text-pine/55 dark:text-cream/55">${items.length} kegiatan</p></div>
      </div>
      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
        ${items.map(([t,d])=>`<div class="group rounded-2xl border border-pine/10 dark:border-cream/10 p-6 hover:border-brass hover:shadow-lg transition bg-cream-deep/40 dark:bg-pine/40"><div class="flex items-start gap-3"><span class="text-brass text-lg mt-0.5">✦</span><div><h3 class="font-serif text-lg text-pine dark:text-cream leading-snug mb-1.5 group-hover:text-leaf dark:group-hover:text-brass-light transition">${t}</h3><p class="text-sm text-pine/70 dark:text-cream/70 leading-relaxed">${d}</p></div></div></div>`).join('')}
      </div>
    </section>`).join('')}
</div>

<section class="relative z-10 bg-pine text-cream">
  <div class="max-w-6xl mx-auto px-5 py-20 sm:py-24 grid md:grid-cols-3 gap-10">
    <div class="reveal"><p class="text-xs font-semibold tracking-widest text-brass-light uppercase mb-4">Informasi</p><h2 class="font-serif text-3xl leading-tight">Bagaimana cara bergabung?</h2></div>
    <div class="md:col-span-2 reveal grid sm:grid-cols-3 gap-6">
      ${[['Pilih Kegiatan','Pilih ekstrakurikuler sesuai minat dan bakatmu.'],['Daftar','Isi formulir pendaftaran di awal tahun ajaran.'],['Ikuti Jadwal','Hadir pada jadwal latihan rutin bersama pembina.']].map(([t,d],i)=>`<div class="bg-pine-deep/60 rounded-2xl p-6 ring-1 ring-cream/10"><p class="numdot text-brass-light text-3xl mb-3">0${i+1}</p><h3 class="font-serif text-lg mb-2">${t}</h3><p class="text-sm text-cream/70 leading-relaxed">${d}</p></div>`).join('')}
    </div>
  </div>
</section>

<section class="relative z-10 max-w-6xl mx-auto px-5 py-20 sm:py-28">
  <div class="rounded-[2rem] bg-gradient-to-br from-leaf to-pine text-cream px-8 sm:px-14 py-14 text-center relative overflow-hidden reveal">
    <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full bg-brass/20 blur-3xl"></div>
    <h2 class="font-serif text-3xl sm:text-5xl leading-tight max-w-2xl mx-auto">Temukan bakatmu bersama kami.</h2>
    <p class="text-cream/80 mt-5 max-w-lg mx-auto">Jadilah bagian dari SMA Putra Persada Batam dan kembangkan potensimu seluas-luasnya.</p>
    <a href="#ppdb" class="inline-block mt-8 bg-brass text-pine-deep font-semibold px-8 py-3.5 rounded-full hover:bg-brass-light transition">Daftar PPDB</a>
  </div>
</section>`;
}

/* ===================== ROUTER ===================== */
const PAGES = {
  'visi-misi': ['#visi-misi', visiContent],
  'ekstrakurikuler': ['#ekstrakurikuler', ekskulContent],
};
let current = null;

function renderPage(key){
  const [active, contentFn] = PAGES[key];
  app.innerHTML = chromeHeader(active) + contentFn() + chromeFooter();
  initChrome();
  current = key;
  window.scrollTo({top:0});
}
function renderHome(){
  app.innerHTML = chromeHeader('#beranda') + homeContent() + chromeFooter();
  initChrome();
  current = 'home';
}

function route(){
  const h = decodeURIComponent(location.hash.replace(/^#/,''));
  if (PAGES[h]){ if(current!==h) renderPage(h); else window.scrollTo({top:0}); return; }
  // home (default) or a home-section anchor
  if (current!=='home') renderHome();
  if (h){
    const el = document.getElementById(h);
    if (el) setTimeout(()=> el.scrollIntoView({behavior:'smooth'}), 30);
  } else {
    window.scrollTo({top:0});
  }
}

addEventListener('hashchange', route);
route();
