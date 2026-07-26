import AdminChrome from "./admin-chrome";
import { logout, requireAdmin } from "./actions";
import "./admin.css";

export default async function AdminLayout({
	children,
}: {
	children: React.ReactNode;
}) {
	const { supabase, profile } = await requireAdmin();

	const [pesanBaru, sekolah] = await Promise.all([
		supabase
			.from("pesan_kontak")
			.select("*", { count: "exact", head: true })
			.eq("dibaca", false),
		supabase
			.from("pengaturan")
			.select("nilai")
			.eq("kunci", "nama_sekolah")
			.maybeSingle(),
	]);

	const tanggal = new Intl.DateTimeFormat("id-ID", {
		weekday: "long",
		day: "numeric",
		month: "long",
		year: "numeric",
		timeZone: "Asia/Jakarta",
	}).format(new Date());

	return (
		<AdminChrome
			nama={profile.nama}
			role={profile.role ?? "admin"}
			sekolah={sekolah.data?.nilai ?? "SMA Putra Persada Batam"}
			tanggal={tanggal}
			unread={pesanBaru.count ?? 0}
			logout={logout}
		>
			{children}
		</AdminChrome>
	);
}
