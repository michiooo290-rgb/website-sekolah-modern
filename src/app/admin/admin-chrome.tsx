"use client";

import Image from "next/image";
import Link from "next/link";
import { usePathname } from "next/navigation";
import { useEffect, useState } from "react";
import { Icon } from "./icons";

const navItems = [
	{ href: "/admin", icon: "grid", label: "Dashboard" },
	{ href: "/admin/pengaturan", icon: "cog", label: "Pengaturan" },
	{ href: "/admin/berita", icon: "news", label: "Berita" },
	{ href: "/admin/guru", icon: "users", label: "Guru & Staf" },
	{ href: "/admin/ekstrakurikuler", icon: "star", label: "Ekstrakurikuler" },
	{ href: "/admin/visi-misi", icon: "eye", label: "Visi & Misi" },
	{ href: "/admin/tentang", icon: "info", label: "Tentang" },
	{ href: "/admin/ppdb", icon: "doc", label: "PPDB" },
	{ href: "/admin/agenda", icon: "calendar", label: "Agenda" },
	{ href: "/admin/faq", icon: "sparkle", label: "FAQ" },
	{ href: "/admin/pesan", icon: "mail", label: "Pesan Masuk" },
	{ href: "/admin/aktivitas", icon: "chart", label: "Aktivitas" },
];

export default function AdminChrome({
	nama,
	role,
	sekolah,
	tanggal,
	unread,
	logout,
	children,
}: {
	nama: string;
	role: string;
	sekolah: string;
	tanggal: string;
	unread: number;
	logout: () => void | Promise<void>;
	children: React.ReactNode;
}) {
	const pathname = usePathname();
	const [open, setOpen] = useState(false);

	// Ikuti pilihan tema tersimpan; jika belum ada, pakai preferensi sistem.
	useEffect(() => {
		const stored = window.localStorage.getItem("admin-theme");
		const dark = stored
			? stored === "dark"
			: window.matchMedia("(prefers-color-scheme: dark)").matches;
		document.documentElement.classList.toggle("dark", dark);
	}, []);

	// Tutup sidebar mobile setiap pindah halaman.
	useEffect(() => {
		setOpen(false);
	}, [pathname]);

	function toggleTheme() {
		const dark = document.documentElement.classList.toggle("dark");
		window.localStorage.setItem("admin-theme", dark ? "dark" : "light");
	}

	const current = [...navItems]
		.sort((a, b) => b.href.length - a.href.length)
		.find((item) =>
			item.href === "/admin"
				? pathname === "/admin"
				: pathname.startsWith(item.href),
		);

	return (
		<div className="admin-shell">
			<div
				className={`sidebar-overlay${open ? " open" : ""}`}
				onClick={() => setOpen(false)}
				aria-hidden="true"
			/>

			<aside className={`admin-sidebar${open ? " open" : ""}`}>
				<div className="admin-brand">
					<Link href="/admin" className="admin-brand-link">
						<Image
							src="/assets/img/logo.jpeg"
							alt="Logo"
							width={40}
							height={40}
						/>
						<span>
							<strong>{sekolah}</strong>
							<small>Panel Admin</small>
						</span>
					</Link>
				</div>

				<nav className="admin-nav">
					{navItems.map((item) => (
						<Link
							key={item.href}
							href={item.href}
							className={current?.href === item.href ? "active" : undefined}
						>
							<Icon name={item.icon} />
							{item.label}
							{item.href === "/admin/pesan" && unread > 0 ? (
								<span className="nav-badge">{unread}</span>
							) : null}
						</Link>
					))}
				</nav>

				<div className="admin-side-foot">
					<div className="admin-user">
						<span className="avatar">{nama.charAt(0).toUpperCase()}</span>
						<span>
							<p className="u-name">{nama}</p>
							<p className="u-role">{role}</p>
						</span>
					</div>
					<form action={logout}>
						<button type="submit" className="side-link danger">
							<Icon name="logout" />
							Keluar
						</button>
					</form>
					<Link href="/" target="_blank" className="side-link">
						<Icon name="external" />
						Lihat Website
					</Link>
				</div>
			</aside>

			<div className="admin-main">
				<header className="admin-topbar">
					<div className="topbar-left">
						<button
							type="button"
							className="icon-btn menu-btn"
							onClick={() => setOpen((value) => !value)}
							aria-label="Buka menu navigasi"
						>
							<Icon name="menu" />
						</button>
						<h2>{current?.label ?? "Admin"}</h2>
					</div>
					<div className="topbar-right">
						<span className="topbar-date">{tanggal}</span>
						<button
							type="button"
							className="icon-btn"
							onClick={toggleTheme}
							aria-label="Ganti mode terang atau gelap"
							title="Ganti mode terang/gelap"
						>
							<Icon name="sun" className="i-sun" />
							<Icon name="moon" className="i-moon" />
						</button>
					</div>
				</header>

				<main className="admin-content">{children}</main>
			</div>
		</div>
	);
}
