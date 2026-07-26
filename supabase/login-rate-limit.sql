-- =============================================================
-- Pembatas percobaan login panel admin
-- Jalankan seluruh isi berkas ini di Supabase SQL Editor.
-- Aman dijalankan berulang kali (idempoten).
-- =============================================================

create table if not exists public.login_attempts (
  id bigserial primary key,
  email text not null,
  ip text not null default 'unknown',
  success boolean not null default false,
  created_at timestamptz not null default now()
);

create index if not exists login_attempts_email_ip_time
  on public.login_attempts (lower(email), ip, created_at desc);

create index if not exists login_attempts_ip_time
  on public.login_attempts (ip, created_at desc);

alter table public.login_attempts enable row level security;

-- Tidak ada policy insert/update/delete: klien tidak bisa menyentuh tabel ini
-- secara langsung. Penulisan hanya lewat fungsi SECURITY DEFINER di bawah.
-- Admin boleh membaca untuk keperluan halaman aktivitas.
drop policy if exists "admin baca login_attempts" on public.login_attempts;
create policy "admin baca login_attempts"
  on public.login_attempts for select
  using (public.is_admin());

-- -------------------------------------------------------------
-- Gerbang pemeriksaan sebelum login diproses.
--
-- Penguncian memakai pasangan email + IP (5 kegagalan / 15 menit), bukan
-- email saja. Kalau memakai email saja, orang asing bisa sengaja gagal
-- login berkali-kali untuk mengunci akun admin yang sah dari jauh.
-- Batas kedua per IP (15 kegagalan / 15 menit) menahan percobaan yang
-- menyapu banyak alamat email dari satu sumber.
-- -------------------------------------------------------------
create or replace function public.check_login_allowed(p_email text, p_ip text)
returns jsonb
language plpgsql
security definer
set search_path = public
as $$
declare
  v_email text := lower(trim(coalesce(p_email, '')));
  v_ip text := coalesce(nullif(trim(p_ip), ''), 'unknown');
  v_pair_fail int := 0;
  v_ip_fail int := 0;
  v_last timestamptz;
begin
  select count(*), max(created_at)
    into v_pair_fail, v_last
  from public.login_attempts
  where lower(email) = v_email
    and ip = v_ip
    and success = false
    and created_at > now() - interval '15 minutes';

  select count(*)
    into v_ip_fail
  from public.login_attempts
  where ip = v_ip
    and success = false
    and created_at > now() - interval '15 minutes';

  if v_pair_fail >= 5 or v_ip_fail >= 15 then
    return jsonb_build_object(
      'allowed', false,
      'retry_after_seconds',
      greatest(0, 900 - extract(epoch from (now() - coalesce(v_last, now())))::int)
    );
  end if;

  return jsonb_build_object('allowed', true, 'remaining', greatest(0, 5 - v_pair_fail));
end;
$$;

-- -------------------------------------------------------------
-- Pencatat hasil percobaan login.
-- Login yang berhasil membersihkan riwayat kegagalan pasangan itu,
-- sehingga admin yang cuma salah ketik tidak terus terbebani.
-- -------------------------------------------------------------
create or replace function public.record_login_attempt(p_email text, p_ip text, p_success boolean)
returns void
language plpgsql
security definer
set search_path = public
as $$
declare
  v_email text := lower(trim(coalesce(p_email, '')));
  v_ip text := coalesce(nullif(trim(p_ip), ''), 'unknown');
begin
  insert into public.login_attempts (email, ip, success)
  values (v_email, v_ip, coalesce(p_success, false));

  if coalesce(p_success, false) then
    delete from public.login_attempts
    where lower(email) = v_email and ip = v_ip and success = false;
  end if;

  -- Jaga tabel tetap ringan.
  delete from public.login_attempts
  where created_at < now() - interval '7 days';
end;
$$;

revoke all on function public.check_login_allowed(text, text) from public;
revoke all on function public.record_login_attempt(text, text, boolean) from public;
grant execute on function public.check_login_allowed(text, text) to anon, authenticated;
grant execute on function public.record_login_attempt(text, text, boolean) to anon, authenticated;
