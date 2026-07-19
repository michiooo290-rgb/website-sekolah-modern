-- Security hardening: public contact submission must use a validated,
-- rate-limited function instead of inserting into the table directly.

drop policy if exists "publik kirim pesan" on public.pesan_kontak;

create index if not exists pesan_email_tanggal_idx
  on public.pesan_kontak (lower(email), tanggal desc);

create or replace function public.submit_contact_message(
  p_nama text,
  p_email text,
  p_telepon text default null,
  p_subjek text default null,
  p_pesan text default null
)
returns void
language plpgsql
security definer
set search_path = ''
as $$
declare
  v_email text := lower(trim(coalesce(p_email, '')));
  v_nama text := trim(coalesce(p_nama, ''));
  v_pesan text := trim(coalesce(p_pesan, ''));
begin
  if char_length(v_nama) not between 2 and 100
    or char_length(v_email) > 254
    or v_email !~ '^[A-Za-z0-9.!#$%&''*+/=?^_`{|}~-]+@[A-Za-z0-9](?:[A-Za-z0-9-]{0,61}[A-Za-z0-9])?(?:\.[A-Za-z0-9](?:[A-Za-z0-9-]{0,61}[A-Za-z0-9])?)+$'
    or char_length(coalesce(p_telepon, '')) > 30
    or char_length(coalesce(p_subjek, '')) > 100
    or char_length(v_pesan) not between 10 and 3000
  then
    raise exception 'CONTACT_INVALID';
  end if;

  -- Serialize attempts for the same email to avoid concurrent bypasses.
  perform pg_catalog.pg_advisory_xact_lock(pg_catalog.hashtextextended(v_email, 0));

  if (
    select count(*)
    from public.pesan_kontak
    where lower(email) = v_email
      and tanggal > pg_catalog.now() - interval '15 minutes'
  ) >= 3 then
    raise exception 'CONTACT_RATE_LIMITED';
  end if;

  if exists (
    select 1
    from public.pesan_kontak
    where lower(email) = v_email
      and lower(trim(pesan)) = lower(v_pesan)
      and tanggal > pg_catalog.now() - interval '1 hour'
  ) then
    raise exception 'CONTACT_RATE_LIMITED';
  end if;

  insert into public.pesan_kontak (nama, email, telepon, subjek, pesan)
  values (
    v_nama,
    v_email,
    nullif(trim(coalesce(p_telepon, '')), ''),
    nullif(trim(coalesce(p_subjek, '')), ''),
    v_pesan
  );
end;
$$;

revoke all on function public.submit_contact_message(text, text, text, text, text) from public;
grant execute on function public.submit_contact_message(text, text, text, text, text) to anon, authenticated;

