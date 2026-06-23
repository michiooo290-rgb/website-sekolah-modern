/* ===================== DATA (ekstrakurikuler) ===================== */
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

/* ===================== RENDER EKSKUL ===================== */
function renderEkskulContent(){
  const total = kategori.reduce((s,k)=> s + k[3].length, 0);
  return `
${pageBanner('Ekstrakurikuler','Wadah Bakat & Minat','Ekstrakurikuler',`Lebih dari ${total} kegiatan untuk mengembangkan bakat, minat, dan karakter siswa di luar jam pelajaran.`)}
<section class="relative z-10 max-w-6xl mx-auto px-5 -mt-8 mb-8">
  <div class="flex flex-wrap gap-8">
    <div><p class="font-serif text-4xl text-brass-light">${kategori.length}</p><p class="text-xs text-cream/60 uppercase tracking-wide">Kategori</p></div>
    <div><p class="font-serif text-4xl text-brass-light">${total}+</p><p class="text-xs text-cream/60 uppercase tracking-wide">Pilihan Kegiatan</p></div>
  </div>
</section>

<div class="relative z-10 max-w-6xl mx-auto px-5 py-12 sm:py-16 space-y-20">
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

${pageCTA('Temukan bakatmu bersama kami.','Jadilah bagian dari SMA Putra Persada Batam dan kembangkan potensimu seluas-luasnya.','Daftar PPDB')}`;
}
