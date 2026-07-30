import Link from "next/link";
import { PPDB_CLOSING_KEY, PPDB_STATUS_KEY, resolvePpdbStatus } from "@/lib/ppdb-status";
import { requireAdmin } from "./actions";
import { Icon } from "./icons";

export const metadata = { title: "Dashboard Admin" };

type BeritaRow = {
	judul: string;
	kategori: string;
	tanggal: string;
	dilihat: number | null;
};

type PesanRow = {
	id: number;
	nama: string;
	subjek: string | null;
	tanggal: string;
	dibaca: boolean;
};

const pad = (value: number) => String(value).padStart(2, "0");
const angka = (value: number) => value.toLocaleString("id-ID");
const tglPendek = (value: string) =>
	new Intl.DateTimeFormat("id-ID", {
		day: "numeric",
		month: "short",
		year: "numeric",
		timeZone: "Asia/Jakarta",
	}).format(new Date(value));

/**
 * Bagian kosong sebaiknya menuntun ke langkah berikutnya, bukan berhenti pada
 * satu kalimat. Karena itu setiap keadaan kosong membawa ikon dan tautan.
 */
function EmptyState({
	icon,
	text,
	href,
	action,
}: {
	icon: string;
	text: string;
	href?: string;
	action?: string;
}) {
	return (
		<div className="empty-state">
			<span className="es-ico">
				<Icon name={icon} />
			</span>
			<p>{text}</p>
			{href && action ? (
				<Link className="es-act" href={href}>
					<Icon name="plus" />
					{action}
				</Link>
			) : null}
		</div>
	);
}

export default async function Dashboard() {
	const { supabase, profile } = await requireAdmin();

	// Waktu Jakarta (WIB) tanpa bergantung pada zona waktu server.
	const jakarta = new Date(Date.now() + 7 * 60 * 60 * 1000);
	const tahun = jakarta.getUTCFullYear();
	const bulan = jakarta.getUTCMonth();
	const awalBulan = `${tahun}-${pad(bulan + 1)}-01`;
	const awalBulanDepan =
		bulan === 11 ? `${tahun + 1}-01-01` : `${tahun}-${pad(bulan + 2)}-01`;
	const jam = jakarta.getUTCHours();
	const sapaan =
		jam < 11
			? "Selamat pagi"
			: jam < 15
				? "Selamat siang"
				: jam < 19
					? "Selamat sore"
					: "Selamat malam";

	const [
		beritaTotal,
		guruTotal,
		ekskulTotal,
		pesanTotal,
		pesanBaru,
		tayanganRes,
		beritaBulanRes,
		pesanBulanRes,
		kategoriRes,
		terbaruRes,
		populerRes,
		pesanRes,
		ppdbRes,
	] = await Promise.all([
		supabase.from("berita").select("*", { count: "exact", head: true }),
		supabase.from("guru").select("*", { count: "exact", head: true }),
		supabase
			.from("ekstrakurikuler")
			.select("*", { count: "exact", head: true }),
		supabase.from("pesan_kontak").select("*", { count: "exact", head: true }),
		supabase
			.from("pesan_kontak")
			.select("*", { count: "exact", head: true })
			.eq("dibaca", false),
		supabase.from("berita").select("dilihat"),
		supabase
			.from("berita")
			.select("*", { count: "exact", head: true })
			.gte("tanggal", awalBulan)
			.lt("tanggal", awalBulanDepan),
		supabase
			.from("pesan_kontak")
			.select("*", { count: "exact", head: true })
			.gte("tanggal", awalBulan)
			.lt("tanggal", awalBulanDepan),
		supabase.from("ekstrakurikuler").select("kategori"),
		supabase
			.from("berita")
			.select("judul,kategori,tanggal,dilihat")
			.order("tanggal", { ascending: false })
			.limit(5),
		supabase
			.from("berita")
			.select("judul,kategori,tanggal,dilihat")
			.order("dilihat", { ascending: false })
			.order("tanggal", { ascending: false })
			.limit(5),
		supabase
			.from("pesan_kontak")
			.select("id,nama,subjek,tanggal,dibaca")
			.order("tanggal", { ascending: false })
			.limit(5),
		supabase
			.from("pengaturan")
			.select("kunci,nilai")
			.in("kunci", [PPDB_STATUS_KEY, PPDB_CLOSING_KEY]),
	]);

	const tayangan = ((tayanganRes.data ?? []) as { dilihat: number | null }[]).reduce(
		(total, row) => total + (row.dilihat ?? 0),
		0,
	);
	const kategoriEkskul = new Set(
		((kategoriRes.data ?? []) as { kategori: string | null }[])
			.map((row) => row.kategori)
			.filter((value): value is string => Boolean(value)),
	).size;
	const terbaru = (terbaruRes.data ?? []) as BeritaRow[];
	const populer = (populerRes.data ?? []) as BeritaRow[];
	const pesan = (pesanRes.data ?? []) as PesanRow[];
	const unread = pesanBaru.count ?? 0;
	const ppdb = resolvePpdbStatus(
		Object.fromEntries(
			((ppdbRes.data ?? []) as { kunci: string; nilai: string | null }[]).map((row) => [
				row.kunci,
				row.nilai,
			]),
		),
	);
	const maxDilihat = Math.max(1, populer[0]?.dilihat ?? 0);

	const kartu = [
		{
			label: "Berita",
			value: beritaTotal.count ?? 0,
			icon: "news",
			color: "brass",
			href: "/admin/berita",
			badge: 0,
		},
		{
			label: "Guru & Staf",
			value: guruTotal.count ?? 0,
			icon: "users",
			color: "leaf",
			href: "/admin/guru",
			badge: 0,
		},
		{
			label: "Ekstrakurikuler",
			value: ekskulTotal.count ?? 0,
			icon: "star",
			color: "brass",
			href: "/admin/ekstrakurikuler",
			badge: 0,
		},
		{
			label: "Pesan Masuk",
			value: pesanTotal.count ?? 0,
			icon: "mail",
			color: "leaf",
			href: "/admin/pesan",
			badge: unread,
		},
	];

	const ringkas = [
		{
			label: "Total Tayangan Berita",
			value: angka(tayangan),
			sub: "akumulasi semua berita",
			icon: "eye",
		},
		{
			label: "Berita Bulan Ini",
			value: angka(beritaBulanRes.count ?? 0),
			sub: "dipublikasikan",
			icon: "calendar",
		},
		{
			label: "Pesan Bulan Ini",
			value: angka(pesanBulanRes.count ?? 0),
			sub: "diterima",
			icon: "mail",
		},
		{
			label: "Kategori Ekskul",
			value: angka(kategoriEkskul),
			sub: "kelompok kegiatan",
			icon: "grid",
		},
	];

	const quick = [
		{ href: "/admin/berita", label: "Tulis Berita", icon: "plus" },
		{ href: "/admin/guru", label: "Tambah Guru", icon: "userPlus" },
		{ href: "/admin/ekstrakurikuler", label: "Ekskul", icon: "star" },
		{ href: "/admin/ppdb", label: "PPDB", icon: "doc" },
		{ href: "/admin/pengaturan", label: "Pengaturan", icon: "cog" },
	];

	const ppdbKeterangan = ppdb.open
		? ppdb.closingLabel
			? `tutup otomatis setelah ${ppdb.closingLabel}`
			: "tanpa batas tanggal"
		: ppdb.expired
			? `berakhir ${ppdb.closingLabel}`
			: "ditutup manual";

	return (
		<>
			<div className="card welcome">
				<span className="blob b1" />
				<span className="blob b2" />
				<div className="welcome-inner">
					<div>
						<p className="greet">{sapaan},</p>
						<h2>
							{profile.nama}
							<Icon name="sparkle" />
						</h2>
						<p className="lead">
							Berikut ringkasan aktivitas website sekolah hari ini. Kelola
							konten dengan mudah dari satu tempat.
						</p>
						<Link
							href="/admin/ppdb"
							className={`status ${ppdb.open ? "open" : "closed"}`}
							title="Atur status pendaftaran"
						>
							<span className="dot" />
							<span>
								PPDB: {ppdb.open ? "Terbuka" : "Tertutup"} &middot; {ppdbKeterangan}
							</span>
						</Link>
					</div>
					<div>
						{unread > 0 ? (
							<Link className="btn-brass" href="/admin/pesan">
								<Icon name="mail" />
								{unread} pesan baru
							</Link>
						) : (
							<Link className="btn-brass" href="/admin/berita">
								<Icon name="plus" />
								Tulis Berita
							</Link>
						)}
					</div>
				</div>
			</div>

			<div className="stat-grid">
				{kartu.map((item) => (
					<Link key={item.href} href={item.href} className="card stat">
						<span className={`bar ${item.color}`} />
						<div className="stat-head">
							<span className={`stat-ico ${item.color}`}>
								<Icon name={item.icon} />
							</span>
							{item.badge > 0 ? (
								<span className="pill-red">+{item.badge}</span>
							) : (
								<Icon name="chevron" className="chev" />
							)}
						</div>
						<p className="num">{angka(item.value)}</p>
						<p className="lbl">{item.label}</p>
					</Link>
				))}
			</div>

			<div className="card mb-lg">
				<div className="summary">
					{ringkas.map((item) => (
						<div className="sum-item" key={item.label}>
							<span className="sum-ico">
								<Icon name={item.icon} />
							</span>
							<div>
								<p className="v">{item.value}</p>
								<p className="l">{item.label}</p>
								<p className="s">{item.sub}</p>
							</div>
						</div>
					))}
				</div>
			</div>

			{/* Peringkat memerlukan ruang lebih lebar, akses cepat cukup sempit. */}
			<div className="dash-two">
				<div className="card">
					<div className="section-head">
						<h3 className="section-title">
							<span className="ico">
								<Icon name="chart" />
							</span>
							Berita Terpopuler
						</h3>
						<Link className="link-more" href="/admin/berita">
							Kelola &rarr;
						</Link>
					</div>
					{populer.length === 0 || tayangan === 0 ? (
						<EmptyState
							icon="chart"
							text="Belum ada data kunjungan. Peringkat muncul setelah berita mulai dibaca pengunjung."
							href="/admin/berita"
							action="Tulis berita"
						/>
					) : (
						<div className="rank">
							{populer.map((row, index) => {
								const persen = Math.max(
									6,
									Math.round(((row.dilihat ?? 0) / maxDilihat) * 100),
								);
								return (
									<div className="rank-row" key={row.judul}>
										<span className={`rank-no${index === 0 ? " top" : ""}`}>
											{index + 1}
										</span>
										<div className="rank-body">
											<div className="rank-head">
												<p className="rank-title">
													{row.judul} <span>&middot; {row.kategori}</span>
												</p>
												<span className="views">
													<Icon name="eye" />
													{angka(row.dilihat ?? 0)}
												</span>
											</div>
											<div className="meter">
												<span
													className={index === 0 ? "top" : undefined}
													style={{ width: `${persen}%` }}
												/>
											</div>
										</div>
									</div>
								);
							})}
						</div>
					)}
				</div>

				<div className="card">
					<h3 className="section-title">Akses Cepat</h3>
					<div className="quick">
						{quick.map((item) => (
							<Link key={item.href + item.label} href={item.href}>
								<span className="qi">
									<Icon name={item.icon} />
								</span>
								<span>{item.label}</span>
							</Link>
						))}
					</div>
				</div>
			</div>

			<div className="two-col">
				<div className="card">
					<div className="section-head">
						<h3 className="section-title">
							<span className="ico">
								<Icon name="news" />
							</span>
							Berita Terbaru
						</h3>
						<Link className="link-more" href="/admin/berita">
							Kelola &rarr;
						</Link>
					</div>
					{terbaru.length === 0 ? (
						<EmptyState
							icon="news"
							text="Belum ada berita yang dipublikasikan."
							href="/admin/berita"
							action="Tulis berita pertama"
						/>
					) : (
						<div>
							{terbaru.map((row) => (
								<div className="list-row" key={row.judul}>
									<div>
										<p className="t">{row.judul}</p>
										<p className="m">
											<span className="tag">{row.kategori}</span>
											{tglPendek(row.tanggal)}
										</p>
									</div>
									<span className="views">
										<Icon name="eye" />
										{angka(row.dilihat ?? 0)}
									</span>
								</div>
							))}
						</div>
					)}
				</div>

				<div className="card">
					<div className="section-head">
						<h3 className="section-title">
							<span className="ico">
								<Icon name="mail" />
							</span>
							Pesan Terbaru
						</h3>
						<Link className="link-more" href="/admin/pesan">
							Lihat semua &rarr;
						</Link>
					</div>
					{pesan.length === 0 ? (
						<EmptyState
							icon="mail"
							text="Belum ada pesan masuk. Pesan dari formulir kontak akan tampil di sini."
						/>
					) : (
						<div>
							{pesan.map((row) => (
								<Link className="list-row" href="/admin/pesan" key={row.id}>
									<div className="pesan-left">
										<span className="avatar">
											{row.nama.charAt(0).toUpperCase()}
										</span>
										<div>
											<p className="t">
												{row.nama}
												{!row.dibaca ? (
													<span className="unread" title="Belum dibaca" />
												) : null}
											</p>
											<p className="m">{row.subjek || "Tanpa subjek"}</p>
										</div>
									</div>
									<span className="when">{tglPendek(row.tanggal)}</span>
								</Link>
							))}
						</div>
					)}
				</div>
			</div>
		</>
	);
}
