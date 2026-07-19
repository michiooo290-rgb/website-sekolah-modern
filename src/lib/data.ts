import { createServerSupabase } from "./supabase";
import { fallbackActivities, fallbackNews, fallbackSettings, fallbackTeachers, fallbackVision } from "./fallback-data";
import type { AboutItem, Activity, News, PpdbItem, SettingMap, Teacher, VisionItem } from "./types";

export async function getSettings(): Promise<SettingMap> {
  const supabase = await createServerSupabase();
  if (!supabase) return fallbackSettings;
  const { data } = await supabase.from("pengaturan").select("kunci,nilai");
  if (!data?.length) return fallbackSettings;
  return Object.fromEntries(data.map((row) => [row.kunci, row.nilai]));
}

export async function getNews(limit?: number): Promise<News[]> {
  const supabase = await createServerSupabase();
  if (!supabase) return limit ? fallbackNews.slice(0, limit) : fallbackNews;
  let query = supabase.from("berita").select("*").order("tanggal", { ascending: false });
  if (limit) query = query.limit(limit);
  const { data } = await query;
  return data?.length ? (data as News[]) : fallbackNews;
}

export async function getNewsBySlug(slug: string): Promise<News | null> {
  const supabase = await createServerSupabase();
  if (!supabase) return fallbackNews.find((item) => item.slug === slug) ?? null;
  const { data } = await supabase.from("berita").select("*").eq("slug", slug).maybeSingle();
  return data as News | null;
}

export async function getTeachers(): Promise<Teacher[]> {
  const supabase = await createServerSupabase();
  if (!supabase) return fallbackTeachers;
  const { data } = await supabase.from("guru").select("*").order("urutan");
  return data?.length ? (data as Teacher[]) : fallbackTeachers;
}

export async function getActivities(): Promise<Activity[]> {
  const supabase = await createServerSupabase();
  if (!supabase) return fallbackActivities;
  const { data } = await supabase.from("ekstrakurikuler").select("*").order("kategori").order("nama");
  return data?.length ? (data as Activity[]) : fallbackActivities;
}

export async function getVision(): Promise<VisionItem[]> {
  const supabase = await createServerSupabase();
  if (!supabase) return fallbackVision;
  const { data } = await supabase.from("visi_misi").select("*").order("urutan");
  return data?.length ? (data as VisionItem[]) : fallbackVision;
}

export async function getAbout(): Promise<AboutItem[]> {
  const supabase = await createServerSupabase();
  if (!supabase) return [];
  const { data } = await supabase.from("tentang").select("*").order("id");
  return (data ?? []) as AboutItem[];
}

export async function getPpdb(): Promise<PpdbItem[]> {
  const supabase = await createServerSupabase();
  if (!supabase) return [];
  const { data } = await supabase.from("ppdb_info").select("*").order("urutan");
  return (data ?? []) as PpdbItem[];
}

export function imageUrl(path: string | null | undefined, fallback = "/assets/img/placeholder-berita.svg") {
  if (!path) return fallback;
  if (path.startsWith("http") || path.startsWith("/")) return path;
  if (path.startsWith("assets/")) return `/${path}`;
  const base = process.env.NEXT_PUBLIC_SUPABASE_URL;
  return base ? `${base}/storage/v1/object/public/media/${path}` : fallback;
}

export function formatDate(value: string) {
  return new Intl.DateTimeFormat("id-ID", { day: "numeric", month: "long", year: "numeric" }).format(new Date(value));
}

export function plainText(value: string) {
  return value.replace(/<[^>]*>/g, " ").replace(/\s+/g, " ").trim();
}
