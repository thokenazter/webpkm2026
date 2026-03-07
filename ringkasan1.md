# Ringkasan Tabel Penting Aplikasi

Dokumen ini merangkum tabel-tabel utama beserta kolom kunci dan relasinya.

## A. RAB (Anggaran)

- Tabel: rabs
  - Kolom kunci: id, komponen, rab_menu_id, rab_kegiatan_id, rincian_menu, kegiatan, total(decimal), metadata(json), created_by(FK users), timestamps
  - Indeks: created_by
  - Relasi: hasMany rab_items; belongsTo rab_menus, rab_kegiatans, users(created_by)

- Tabel: rab_items
  - Kolom kunci: id, rab_id(FK), label, type, factors(json), unit_price(decimal), subtotal(decimal), meta(json), timestamps
  - Indeks: type
  - Relasi: belongsTo rabs

- Tabel: rab_menus
  - Kolom kunci: id, component_key, name, timestamps
  - Indeks: (component_key, name)
  - Relasi: hasMany rab_kegiatans; digunakan sebagai referensi pada rabs.rab_menu_id

- Tabel: rab_kegiatans
  - Kolom kunci: id, rab_menu_id(FK), name, timestamps
  - Indeks: (rab_menu_id, name)
  - Relasi: belongsTo rab_menus; digunakan sebagai referensi pada rabs.rab_kegiatan_id

## B. POA (Perencanaan Kegiatan Tahunan)

- Tabel: poas
  - Kolom kunci: id, year, annual_budget_id(FK), rab_id(FK), nomor_surat, kegiatan, output_target, schedule(json), item_progress(json), planned_total(decimal), created_by(FK), timestamps
  - Indeks: year
  - Relasi: belongsTo annual_budgets, rabs, users(created_by); hasMany poa_participants

- Tabel: poa_participants
  - Kolom kunci: id, poa_id(FK), employee_id(FK), role, borrowed_employee_id(FK nullable), note, timestamps
  - Relasi: belongsTo poas, employees; borrowed_employee_id menunjuk employees (opsional)

## C. LPJ (Pertanggungjawaban)

- Tabel: lpjs
  - Kolom kunci: id, type(enum: SPPT|SPPD), kegiatan, no_surat(unique), tanggal_surat(string), tanggal_kegiatan(string nullable), transport_mode(string nullable), jumlah_desa_darat(int), desa_tujuan_darat(text nullable), jumlah_desa_seberang(int), desa_tujuan_seberang(text nullable), created_by(FK users nullable), timestamps
  - Relasi: belongsTo users(created_by); many-to-many ke villages melalui lpj_villages; hasMany lpj_participants

- Tabel: lpj_participants
  - Kolom kunci: id, lpj_id(FK), employee_id(FK), credited_employee_id(FK nullable), role(enum), lama_tugas_hari(smallInt), transport_amount(decimal), per_diem_rate(decimal), per_diem_days(smallInt), per_diem_amount(decimal), total_amount(decimal), timestamps
  - Relasi: belongsTo lpjs; belongsTo employees (employee_id); belongsTo employees (credited_employee_id)
  - Catatan: credited_employee_id menampung penerima kredit saldo; nama dokumen bisa memakai “borrowed name” melalui logika POA saat klaim

- Tabel: lpj_villages (pivot)
  - Kolom kunci: id, lpj_id(FK), village_id(FK), timestamps
  - Unique: (lpj_id, village_id)
  - Relasi: belongsTo lpjs, villages

## D. Anggaran Tahunan & Alokasi

- Tabel: annual_budgets
  - Kolom kunci: id, year, name(default: Pagu BOK), amount(decimal), description(text), timestamps
  - Indeks/Unique: year; unique(year, name)
  - Relasi: hasMany budget_allocations

- Tabel: budget_allocations
  - Kolom kunci: id, annual_budget_id(FK), rab_id(FK), allocated_amount(decimal), notes(text), timestamps
  - Unique: (annual_budget_id, rab_id)
  - Relasi: belongsTo annual_budgets, rabs

## E. Master Data & Pendukung

- Tabel: employees
  - Kolom kunci: id, nama, nip(unique), tanggal_lahir(date), pangkat_golongan, jabatan, timestamps
  - Relasi: digunakan oleh poa_participants, lpj_participants, users(employee_id), employee_saldo_entries

- Tabel: villages
  - Kolom kunci: id, nama, kecamatan, akses(enum: DARAT|SEBERANG), transport_standard(decimal), timestamps
  - Relasi: many-to-many dengan lpjs melalui lpj_villages

- Tabel: rate_settings
  - Kolom kunci: id, key(unique) [transport_rate|per_diem_rate], name, value(decimal), description, is_active(bool), timestamps
  - Tujuan: sumber tarif dinamis transport/per diem yang aktif

- Tabel: per_diem_rates (legacy/opsional)
  - Kolom kunci: id, pangkat_golongan, rate_per_day(decimal), valid_from(date), valid_to(date nullable), timestamps

- Tabel: pejabat_ttd
  - Kolom kunci: id, nama, desa, jabatan, timestamps
  - Relasi: digunakan oleh tiba_berangkat_detail

- Tabel: tiba_berangkat
  - Kolom kunci: id, no_surat(unique), created_by(FK users nullable), timestamps
  - Relasi: hasMany tiba_berangkat_detail; belongsTo users(created_by)

- Tabel: tiba_berangkat_detail
  - Kolom kunci: id, tiba_berangkat_id(FK), pejabat_ttd_id(FK), tanggal_kunjungan(date), timestamps
  - Relasi: belongsTo tiba_berangkat, pejabat_ttd

- Tabel: pengumumans
  - Kolom kunci: id, judul, isi, is_active(bool), tanggal_mulai(date nullable), tanggal_selesai(date nullable), prioritas(enum low|medium|high), warna_tema(string), timestamps

- Tabel: employee_saldo_entries
  - Kolom kunci: id, employee_id(FK), poa_id(FK nullable), rab_item_id(nullable), year(int), month(tinyInt), category(opsional), label, amount(decimal), description, created_by(nullable), timestamps
  - Indeks: (employee_id, year, month); (poa_id, rab_item_id)

- Tabel: activities (historis)
  - Kolom kunci: id, kode(unique), nama, sumber_dana(enum), timestamps
  - Catatan: LPJ kini memakai string ‘kegiatan’ manual; tabel ini bisa tetap untuk referensi historis

- Tabel: global_holidays
  - Kolom kunci: id, date(unique), name(nullable), timestamps
  - Tujuan: kalender hari libur global untuk validasi tanggal

## F. Pengguna & Sistem (Standar Laravel/Jetstream)

- Tabel: users
  - Kolom kunci: id, name, email(unique), role(default 'user'), approved_at(timestamp nullable), password, current_team_id(nullable), profile_photo_path(nullable), two_factor_* kolom, employee_id(FK nullable), timestamps
  - Relasi: optional belongsTo employees(employee_id)

- Tabel: sessions, password_reset_tokens, personal_access_tokens
  - Standar untuk sesi, reset password, token API (Sanctum)

- Tabel: jobs, job_batches, failed_jobs
  - Antrian dan batch pekerjaan

- Tabel: cache, cache_locks
  - Penyimpanan cache database (opsional)

- Tabel: media (Spatie Media Library)
  - Kolom kunci: id, model_type/model_id(morph), uuid(unique nullable), collection_name, name, file_name, mime_type, disk, conversions_disk, size, manipulations(json), custom_properties(json), generated_conversions(json), responsive_images(json), order_column(index), timestamps

- Tabel: activity_log (Spatie Activitylog)
  - Kolom kunci: id, log_name, description(text), subject(morph), causer(morph), properties(json), event, batch_uuid, timestamps; index(log_name)

## G. Catatan Relasi Utama (ringkas)

- RAB → Item: rabs (1) — (n) rab_items
- RAB → Menu/Kegiatan: rabs (n) — (1) rab_menus / rab_kegiatans
- POA → RAB/Anggaran: poas (n) — (1) rabs / annual_budgets
- POA → Peserta: poas (1) — (n) poa_participants (employee_id; borrowed_employee_id opsional)
- LPJ → Peserta: lpjs (1) — (n) lpj_participants (employee_id; credited_employee_id opsional)
- LPJ ↔ Desa: lpjs (n) — (n) villages (pivot lpj_villages)
- Anggaran Tahunan → Alokasi: annual_budgets (1) — (n) budget_allocations (ke rabs)
- TB (Tiba Berangkat) → Detail: tiba_berangkat (1) — (n) tiba_berangkat_detail (pejabat_ttd)

---
Dokumen ini menyoroti struktur data inti untuk navigasi, integrasi, dan validasi skema basis data.
