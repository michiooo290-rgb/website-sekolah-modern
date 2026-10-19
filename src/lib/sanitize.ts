import sanitizeHtml from "sanitize-html";

const SAFE_HTML_TAGS = ["p", "br", "strong", "b", "em", "i", "ul", "ol", "li", "blockquote", "h2", "h3", "a"];

/** Satu konfigurasi sanitasi untuk isi berita, dipakai saat menyimpan dan saat menampilkan. */
const NEWS_HTML_OPTIONS: sanitizeHtml.IOptions = {
  allowedTags: SAFE_HTML_TAGS,
  allowedAttributes: { a: ["href", "target", "rel"] },
  allowedSchemes: ["http", "https", "mailto"],
  transformTags: { a: sanitizeHtml.simpleTransform("a", { rel: "noopener noreferrer" }) },
};

export function sanitizeNewsHtml(value: string) {
  return sanitizeHtml(value, NEWS_HTML_OPTIONS);
}