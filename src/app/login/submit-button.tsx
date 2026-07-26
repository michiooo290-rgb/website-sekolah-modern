"use client";

import { useFormStatus } from "react-dom";

/**
 * Tombol kirim yang tahu status form induknya. Saat server action berjalan,
 * tombol dinonaktifkan sehingga pengguna tidak mengirim login dua kali.
 */
export function SubmitButton() {
  const { pending } = useFormStatus();

  return (
    <button className="button" type="submit" disabled={pending} aria-busy={pending}>
      {pending ? "Memverifikasi\u2026" : "Masuk ke dashboard"}
    </button>
  );
}
