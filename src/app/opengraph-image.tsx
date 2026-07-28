import { ImageResponse } from "next/og";

export const alt = "SMAS Putra Persada Batam — unggul, berkarakter, dan beriman";
export const size = { width: 1200, height: 630 };
export const contentType = "image/png";

/* Kartu pratinjau digambar di server, jadi tidak bergantung pada berkas
   gambar dan selalu tajam pada ukuran 1200x630 yang dipakai WhatsApp. */
export default async function Image() {
  return new ImageResponse(
    (
      <div
        style={{
          width: "100%",
          height: "100%",
          display: "flex",
          flexDirection: "column",
          justifyContent: "space-between",
          padding: "72px 80px",
          backgroundColor: "#0E3B2E",
          backgroundImage: "radial-gradient(circle at 85% 15%, rgba(201,162,39,0.35), transparent 55%)",
          color: "#F7F3E9",
          fontFamily: "Georgia, serif",
        }}
      >
        <div style={{ display: "flex", alignItems: "center", gap: 20 }}>
          <div style={{ width: 56, height: 6, backgroundColor: "#C9A227", borderRadius: 999 }} />
          <div style={{ fontSize: 24, letterSpacing: 6, color: "#E0BC45", textTransform: "uppercase" }}>
            Sekolah Menengah Atas Swasta
          </div>
        </div>

        <div style={{ display: "flex", flexDirection: "column" }}>
          <div style={{ fontSize: 76, lineHeight: 1.1, fontWeight: 600 }}>SMAS Putra Persada</div>
          <div style={{ fontSize: 76, lineHeight: 1.1, fontWeight: 600, color: "#E0BC45" }}>Batam</div>
          <div style={{ fontSize: 32, marginTop: 28, color: "rgba(247,243,233,0.75)" }}>
            Menumbuhkan ilmu &amp; akhlak yang berbuah.
          </div>
        </div>

        <div style={{ display: "flex", alignItems: "center", justifyContent: "space-between", fontSize: 26 }}>
          <div style={{ color: "rgba(247,243,233,0.6)" }}>Informasi PPDB, berita, dan profil sekolah</div>
          <div
            style={{
              display: "flex",
              backgroundColor: "#C9A227",
              color: "#08291F",
              padding: "14px 30px",
              borderRadius: 999,
              fontWeight: 700,
            }}
          >
            Kunjungi Situs
          </div>
        </div>
      </div>
    ),
    size,
  );
}
