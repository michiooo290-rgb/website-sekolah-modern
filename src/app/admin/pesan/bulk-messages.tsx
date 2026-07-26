"use client";

import { useState } from "react";

export type MessageRow = {
  id: number;
  subjek: string;
  nama: string;
  kontak: string[];
  pesan: string;
  dibaca: boolean;
  waktu: string;
};

/**
 * Daftar pesan masuk dengan pilihan massal.
 *
 * Kotak centang bernama "ids" berada di dalam satu formulir, sehingga hanya
 * pesan yang tercentang yang terkirim. Tombol hapus memakai action formulir,
 * sedangkan tombol tandai dibaca memakai formAction sehingga keduanya dapat
 * berbagi pilihan yang sama.
 */
export default function BulkMessages({
  rows,
  deleteAction,
  readAction,
}: {
  rows: MessageRow[];
  deleteAction: (formData: FormData) => void | Promise<void>;
  readAction: (formData: FormData) => void | Promise<void>;
}) {
  const [selected, setSelected] = useState<number[]>([]);

  const allSelected = rows.length > 0 && selected.length === rows.length;
  const someSelected = selected.length > 0 && !allSelected;
  const belumDibaca = rows.filter((row) => !row.dibaca).length;

  function toggleAll() {
    setSelected(allSelected ? [] : rows.map((row) => row.id));
  }

  function toggleOne(id: number) {
    setSelected((current) =>
      current.includes(id) ? current.filter((value) => value !== id) : [...current, id],
    );
  }

  if (rows.length === 0) {
    return <p className="empty">Belum ada pesan dari pengunjung.</p>;
  }

  return (
    <form
      action={deleteAction}
      onSubmit={(event) => {
        /* Konfirmasi hanya untuk penghapusan; penanda dibaca tidak merusak apa pun. */
        const nativeEvent = event.nativeEvent as SubmitEvent;
        const penekan = nativeEvent.submitter as HTMLButtonElement | null;
        if (penekan?.value === "baca") return;

        const jumlah = selected.length;
        if (jumlah === 0) {
          event.preventDefault();
          return;
        }
        const konfirmasi = window.confirm(
          jumlah === 1
            ? "Hapus 1 pesan yang dipilih? Tindakan ini tidak dapat dibatalkan."
            : `Hapus ${jumlah} pesan yang dipilih? Tindakan ini tidak dapat dibatalkan.`,
        );
        if (!konfirmasi) event.preventDefault();
      }}
    >
      <div className="bulk-bar">
        <label className="bulk-all">
          <input
            type="checkbox"
            checked={allSelected}
            ref={(node) => {
              if (node) node.indeterminate = someSelected;
            }}
            onChange={toggleAll}
          />
          <span>Pilih semua</span>
        </label>
        <span className="bulk-count">
          {selected.length > 0
            ? `${selected.length} dari ${rows.length} dipilih`
            : `${rows.length} pesan${belumDibaca ? ` · ${belumDibaca} belum dibaca` : ""}`}
        </span>
        <button className="button secondary" value="baca" formAction={readAction} disabled={selected.length === 0}>
          Tandai dibaca
        </button>
        <button className="button danger" value="hapus" disabled={selected.length === 0}>
          Hapus terpilih
        </button>
      </div>

      <div className="grid two">
        {rows.map((row) => {
          const picked = selected.includes(row.id);
          return (
            <article
              className="card"
              key={row.id}
              style={{
                opacity: row.dibaca && !picked ? 0.8 : 1,
                outline: picked ? "2px solid var(--leaf, #2F7D52)" : undefined,
                outlineOffset: picked ? "2px" : undefined,
              }}
            >
              <label className="bulk-all" style={{ marginBottom: ".6rem" }}>
                <input
                  type="checkbox"
                  name="ids"
                  value={row.id}
                  checked={picked}
                  onChange={() => toggleOne(row.id)}
                  aria-label={`Pilih pesan dari ${row.nama}`}
                />
                <span className="tag">{row.dibaca ? "Sudah dibaca" : "Baru"}</span>
              </label>
              <h3>{row.subjek}</h3>
              <p>
                <strong>{row.nama}</strong>
                {row.kontak.map((detail) => (
                  <span key={detail}> &middot; {detail}</span>
                ))}
              </p>
              <p>{row.pesan}</p>
              {row.waktu && <p className="when">{row.waktu}</p>}
            </article>
          );
        })}
      </div>
    </form>
  );
}
