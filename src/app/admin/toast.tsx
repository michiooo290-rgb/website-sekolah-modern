"use client";

import { useEffect, useState } from "react";
import { usePathname, useRouter } from "next/navigation";

export type ToastTone = "ok" | "error";

/**
 * Popup notifikasi hasil aksi admin.
 *
 * Pesan dikirim server melalui query string. Setelah tampil, query string
 * dibersihkan dengan router.replace supaya popup yang sama tidak muncul lagi
 * ketika pengguna menyegarkan halaman atau menekan tombol kembali.
 */
export default function Toast({
  message,
  tone = "ok",
  duration = 4500,
}: {
  message: string;
  tone?: ToastTone;
  duration?: number;
}) {
  const [open, setOpen] = useState(true);
  const router = useRouter();
  const pathname = usePathname();

  useEffect(() => {
    const hide = setTimeout(() => setOpen(false), duration);
    const clean = setTimeout(() => router.replace(pathname, { scroll: false }), duration + 400);
    return () => {
      clearTimeout(hide);
      clearTimeout(clean);
    };
  }, [duration, pathname, router]);

  if (!open) return null;

  return (
    <div className="toast-wrap" role="status" aria-live="polite">
      <div className={`toast ${tone}`}>
        <span className="toast-ico" aria-hidden="true">
          {tone === "ok" ? (
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" strokeWidth="2.4" strokeLinecap="round" strokeLinejoin="round">
              <path d="M20 6 9 17l-5-5" />
            </svg>
          ) : (
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" strokeWidth="2.4" strokeLinecap="round" strokeLinejoin="round">
              <path d="M12 8v5" />
              <path d="M12 16.5h.01" />
            </svg>
          )}
        </span>
        <p>{message}</p>
        <button type="button" className="toast-x" onClick={() => setOpen(false)} aria-label="Tutup notifikasi">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" strokeWidth="2.2" strokeLinecap="round">
            <path d="M18 6 6 18M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>
  );
}
