import sanitizeHtml from "sanitize-html";

const SAFE_HTML_TAGS = ["p", "br", "strong", "b", "em", "i", "ul", "ol", "li", "blockquote", "h2", "h3", "a"];

/** Satu konfigurasi sanitasi untuk semua kolom "isi" (berita, tentang, PPDB), dipakai saat menyimpan dan saat menampilkan. */
const CONTENT_HTML_OPTIONS: sanitizeHtml.IOptions = {
  allowedTags: SAFE_HTML_TAGS,
  allowedAttributes: { a: ["href", "target", "rel"] },
  allowedSchemes: ["http", "https", "mailto"],
  transformTags: { a: sanitizeHtml.simpleTransform("a", { rel: "noopener noreferrer" }) },
};

export function sanitizeContentHtml(value: string) {
  return sanitizeHtml(value, CONTENT_HTML_OPTIONS);
}
/** Nama lama, tetap dipakai oleh kode yang sudah ada. */
export const sanitizeNewsHtml = sanitizeContentHtml;
