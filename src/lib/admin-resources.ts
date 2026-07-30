export type FieldConfig={name:string;label:string;type?:"text"|"textarea"|"date"|"number"|"select"|"file";options?:string[];required?:boolean};
export type ResourceConfig={label:string;table:string;fields:FieldConfig[];order?:string};

export const resources:Record<string,ResourceConfig>={
  berita:{label:"Berita",table:"berita",order:"tanggal",fields:[{name:"judul",label:"Judul",required:true},{name:"slug",label:"Slug"},{name:"kategori",label:"Kategori",required:true},{name:"isi",label:"Isi",type:"textarea",required:true},{name:"gambar",label:"Gambar",type:"file"},{name:"tanggal",label:"Tanggal",type:"date",required:true}]},
  guru:{label:"Guru",table:"guru",order:"urutan",fields:[{name:"nama",label:"Nama",required:true},{name:"jabatan",label:"Jabatan",required:true},{name:"mapel",label:"Mata Pelajaran"},{name:"foto",label:"Foto",type:"file"},{name:"urutan",label:"Urutan",type:"number"}]},
  ekstrakurikuler:{label:"Ekstrakurikuler",table:"ekstrakurikuler",order:"nama",fields:[{name:"nama",label:"Nama",required:true},{name:"kategori",label:"Kategori",required:true},{name:"deskripsi",label:"Deskripsi",type:"textarea",required:true},{name:"pembina",label:"Pembina"},{name:"jadwal",label:"Jadwal"}]},
  "visi-misi":{label:"Visi & Misi",table:"visi_misi",order:"urutan",fields:[{name:"tipe",label:"Tipe",type:"select",options:["visi","misi","tujuan","nilai"],required:true},{name:"judul",label:"Judul"},{name:"isi",label:"Isi",type:"textarea",required:true},{name:"urutan",label:"Urutan",type:"number"}]},
  tentang:{label:"Tentang",table:"tentang",order:"id",fields:[{name:"bagian",label:"Bagian",type:"select",options:["sejarah","sambutan","fasilitas"],required:true},{name:"judul",label:"Judul"},{name:"isi",label:"Isi",type:"textarea",required:true}]},
  ppdb:{label:"PPDB",table:"ppdb_info",order:"urutan",fields:[{name:"bagian",label:"Bagian",type:"select",options:["syarat","jadwal","alur","lokasi","faq"],required:true},{name:"judul",label:"Judul"},{name:"isi",label:"Isi",type:"textarea",required:true},{name:"tanggal",label:"Tanggal/Keterangan"},{name:"file_formulir",label:"File",type:"file"},{name:"urutan",label:"Urutan",type:"number"}]},
  agenda:{label:"Agenda",table:"agenda",order:"urutan",fields:[{name:"tanggal",label:"Tanggal",required:true},{name:"judul",label:"Judul",required:true},{name:"deskripsi",label:"Deskripsi",type:"textarea"},{name:"urutan",label:"Urutan",type:"number"}]},
  faq:{label:"FAQ",table:"faq",order:"urutan",fields:[{name:"pertanyaan",label:"Pertanyaan",required:true},{name:"jawaban",label:"Jawaban",type:"textarea",required:true},{name:"urutan",label:"Urutan",type:"number"}]},
  pengaturan:{label:"Pengaturan",table:"pengaturan",order:"kunci",fields:[{name:"kunci",label:"Kunci",required:true},{name:"nilai",label:"Nilai",type:"textarea",required:true}]},
};

export function slugify(value:string){return value.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g,"").replace(/[^a-z0-9]+/g,"-").replace(/(^-|-$)/g,"")}
