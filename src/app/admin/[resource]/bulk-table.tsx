"use client";

import { useState } from "react";

export type BulkRow = {
  id: number;
  cells: Array<{ label: string; value: string }>;
};

/**
 * Tabel kelola konten dengan pilihan massal.
 *
 * Kotak centang bernama "ids" berada di dalam satu form, sehingga hanya baris
 * yang tercentang yang ikut terkirim ke server action penghapusan.
 */
export default function BulkTable({
  resource,
  label,
  rows,
  action,
}: {
  resource: string;
  label: string;
  rows: BulkRow[];
  action: (formData: FormData) => void | Promise<void>;
}) {
  const [selected, setSelected] = useState<number[]>([]);

  const allSelected = rows.length > 0 && selected.length === rows.length;
  const someSelected = selected.length > 0 && !allSelected;

  function toggleAll() {
    setSelected(allSelected ? [] : rows.map((row) => row.id));
  }

  function toggleOne(id: number) {
    setSelected((current) =>
      current.includes(id) ? current.filter((value) => value !== id) : [...current, id],
    );
  }

  if (rows.length === 0) {
    return (
      <div className="table-wrap">
        <p className="empty">Belum ada data {label.toLowerCase()}.</p>
      </div>
    );
  }

  return (
    <form
      action={action}
      onSubmit={(event) => {
        const jumlah = selected.length;
        if (jumlah === 0) {
          event.preventDefault();
          return;
        }
        const konfirmasi = window.confirm(
          jumlah === 1
            ? "Hapus 1 data yang dipilih? Tindakan ini tidak dapat dibatalkan."
            : `Hapus ${jumlah} data yang dipilih? Tindakan ini tidak dapat dibatalkan.`,
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
          {selected.length > 0 ? `${selected.length} dari ${rows.length} dipilih` : `${rows.length} data`}
        </span>
        <button className="button danger" disabled={selected.length === 0}>
          Hapus terpilih
        </button>
      </div>

      <div className="table-wrap">
        <table>
          <thead>
            <tr>
              <th className="pick-col">
                <input type="checkbox" checked={allSelected} onChange={toggleAll} aria-label="Pilih semua baris" />
              </th>
              <th>ID</th>
              <th>Data</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            {rows.map((row) => {
              const picked = selected.includes(row.id);
              return (
                <tr key={row.id} className={picked ? "picked" : undefined}>
                  <td className="pick-col">
                    <input
                      type="checkbox"
                      name="ids"
                      value={row.id}
                      checked={picked}
                      onChange={() => toggleOne(row.id)}
                      aria-label={`Pilih data nomor ${row.id}`}
                    />
                  </td>
                  <td>{row.id}</td>
                  <td>
                    {row.cells.map((cell) => (
                      <div key={cell.label}>
                        <strong>{cell.label}:</strong> {cell.value}
                      </div>
                    ))}
                  </td>
                  <td>
                    <a className="button secondary" href={`/admin/${resource}?edit=${row.id}`}>
                      Edit
                    </a>
                  </td>
                </tr>
              );
            })}
          </tbody>
        </table>
      </div>
    </form>
  );
}
