import { LegacyShell } from "@/components/legacy-shell";
import { getSettings } from "@/lib/data";
import { resolvePpdbStatus } from "@/lib/ppdb-status";

export default async function PublicLayout({ children }: { children: React.ReactNode }) {
  const settings = await getSettings();
  const ppdb = resolvePpdbStatus(settings);
  return <LegacyShell ppdbOpen={ppdb.open}>{children}</LegacyShell>;
}
