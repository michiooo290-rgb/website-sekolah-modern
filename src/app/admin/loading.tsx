/**
 * Skeleton dashboard. Next.js mengalirkan berkas ini lebih dulu sementara
 * query Supabase di page.tsx masih berjalan, sehingga pengguna tidak melihat
 * layar diam setelah login.
 */
export default function AdminLoading() {
  return (
    <>
      <style>{"@keyframes sk-pulse{50%{opacity:.45}}.sk{background:var(--soft);border-radius:.55rem;animation:sk-pulse 1.5s ease-in-out infinite}@media (prefers-reduced-motion:reduce){.sk{animation:none}}"}</style>
      <div aria-busy="true" aria-label="Memuat dashboard">
        <div className="card welcome" style={{ minHeight: "9.5rem" }}>
          <span className="blob b1" />
          <span className="blob b2" />
          <div className="welcome-inner">
            <div style={{ flex: 1 }}>
              <div className="sk" style={{ width: "7rem", height: ".8rem", background: "rgba(247,243,233,.14)" }} />
              <div className="sk" style={{ width: "14rem", height: "1.9rem", margin: ".7rem 0", background: "rgba(247,243,233,.14)" }} />
              <div className="sk" style={{ width: "min(28rem,100%)", height: ".8rem", background: "rgba(247,243,233,.1)" }} />
            </div>
          </div>
        </div>

        <div className="stat-grid">
          {[0, 1, 2, 3].map((index) => (
            <div className="card stat" key={index}>
              <div className="stat-head">
                <div className="sk" style={{ width: "2.7rem", height: "2.7rem", borderRadius: ".8rem" }} />
              </div>
              <div className="sk" style={{ width: "3.5rem", height: "1.7rem", marginBottom: ".5rem" }} />
              <div className="sk" style={{ width: "5.5rem", height: ".75rem" }} />
            </div>
          ))}
        </div>

        <div className="card mb">
          <div className="summary">
            {[0, 1, 2, 3].map((index) => (
              <div className="sum-item" key={index}>
                <div className="sk" style={{ width: "2.45rem", height: "2.45rem", borderRadius: ".75rem", flex: "0 0 auto" }} />
                <div style={{ flex: 1 }}>
                  <div className="sk" style={{ width: "3rem", height: "1.1rem", marginBottom: ".4rem" }} />
                  <div className="sk" style={{ width: "5rem", height: ".7rem" }} />
                </div>
              </div>
            ))}
          </div>
        </div>

        <div className="two-col">
          {[0, 1].map((card) => (
            <div className="card" key={card}>
              <div className="sk" style={{ width: "9rem", height: "1rem", marginBottom: "1.4rem" }} />
              {[0, 1, 2, 3].map((row) => (
                <div key={row} style={{ display: "flex", justifyContent: "space-between", gap: "1rem", padding: ".75rem 0" }}>
                  <div className="sk" style={{ width: "min(60%,14rem)", height: ".85rem" }} />
                  <div className="sk" style={{ width: "4rem", height: ".85rem" }} />
                </div>
              ))}
            </div>
          ))}
        </div>
      </div>
    </>
  );
}
