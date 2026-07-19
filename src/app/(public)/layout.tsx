import { LegacyShell } from "@/components/legacy-shell";
import { getSettings } from "@/lib/data";

export default async function PublicLayout({children}:{children:React.ReactNode}){const settings=await getSettings();return <LegacyShell ppdbOpen={settings.ppdb_status!=="tutup"}>{children}</LegacyShell>}
