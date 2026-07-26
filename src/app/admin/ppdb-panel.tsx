"use client";

import { useState } from "react";

/**
 * Panel pengatur status PPDB.
 *
 * Ringkasan di bawah pilihan menjelaskan keadaan yang benar-benar dilihat
 * pengunjung, termasuk ketika pendaftaran tertutup otomatis karena tanggal
 * tutup sudah terlewat meski sakelar masih pada posisi dibuka.
 */
export default function PpdbPanel({
  manualOpen,
  closingDate,
  effectiveOpen,
  expired,
  closingLabel,
  action,
}: {
  manualOpen: boolean;
  closingDate: string | null;
  effectiveOpen: boolean;
  expired: boolean;
  closingLabel: string | null;
  action: (formData: FormData) => void | Promise<void>;
}) {
  const [open, setOpen] = useState(manualOpen);

  return (
    <article className="card mb-lg">
      <div className="section-head">
        <h2 className="section-title">Status Pendaftaran</h2>
        <span className={`status ${effectiveOpen ? "open" : "closed"}`}>
          <span className="dot" />
          <span>{effectiveOpen ? "Sedang dibuka" : "Sudah ditutup"}</span>
        </span>
      </div>

      <p className="lead" style={{ marginTop: 0 }}>
        {effectiveOpen
          ? closingLabel
            ? `Pengunjung melihat pendaftaran terbuka, dan akan tertutup sendiri setelah ${closingLabel}.`
            : "Pengunjung melihat pendaftaran terbuka tanpa batas tanggal."
          : expired
            ? `Pendaftaran tertutup otomatis karena tanggal tutup ${closingLabel} sudah terlewat.`
            : "Pendaftaran ditutup. Halaman PPDB menampilkan spanduk pemberitahuan."}
      </p>

      <form action={action} className="form">
        <div className="seg">
          <label className={open ? "seg-item active" : "seg-item"}>
            <input type="radio" name="status" value="buka" checked={open} onChange={() => setOpen(true)} />
            <span>Dibuka</span>
          </label>
          <label className={!open ? "seg-item active closed" : "seg-item"}>
            <input type="radio" name="status" value="tutup" checked={!open} onChange={() => setOpen(false)} />
            <span>Ditutup</span>
          </label>
        </div>

        {open && (
          <div className="field">
            <label htmlFor="tanggal_tutup">Tutup otomatis pada tanggal</label>
            <input
              id="tanggal_tutup"
              name="tanggal_tutup"
              type="date"
              defaultValue={closingDate ?? ""}
              min="2000-01-01"
              max="2100-12-31"
            />
            <p className="s">
              Kosongkan bila pendaftaran tidak dibatasi tanggal. Hari terakhir pendaftaran adalah tanggal
              yang kamu isi, dan penutupan berlaku sejak hari berikutnya menurut waktu Jakarta.
            </p>
          </div>
        )}
        {!open && <input type="hidden" name="tanggal_tutup" value={closingDate ?? ""} />}

        <div className="actions">
          <button className="button">Simpan status</button>
        </div>
      </form>
    </article>
  );
}
