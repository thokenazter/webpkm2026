# BOK — Analisa Aplikasi dan Ringkasan

## 1) Tujuan & Cakupan
Aplikasi internal untuk manajemen BOK Puskesmas yang mencakup perencanaan (POA), anggaran (RAB + alokasi), pelaksanaan dan pertanggungjawaban (LPJ), serta dokumen pendukung (Tiba Berangkat). Sistem juga menyediakan rekap saldo pegawai, pengumuman, kontrol akses berbasis peran, serta fasilitas ekspor dokumen Excel/Word.

## 2) Tumpukan Teknologi
- Backend: Laravel 12 (PHP 8.2), Sanctum, Jetstream + Livewire 3.
- Admin tooling: Filament 3.
- Paket utama: 
  - spatie/laravel-activitylog, spatie/laravel-medialibrary
  - maatwebsite/excel (ekspor Excel), PhpOffice (PhpSpreadsheet, PhpWord)
- Frontend: Vite, Tailwind CSS (forms, typography), axios.
- Script dev terpadu (serve, queue, log, vite) via `composer dev` (composer.json: scripts.dev).

## 3) Arsitektur & Pola
- MVC Laravel standar dengan Controllers per domain (LPJ, POA, RAB, TB, dsb) di `app/Http/Controllers`.
- Layer layanan untuk operasi kompleks:
  - Ekspor RAB (template, multi-sheet): `app/Services/RabTemplateExporter.php`, `app/Services/RabMasterTemplateExporter.php`, `app/Services/AllRabTemplateExporter.php`, `app/Services/MultiRabStackedTemplateExporter.php`.
  - Dokumen LPJ (PHPWord, TemplateProcessor): `app/Services/LpjDocumentService.php`.
  - Tiba Berangkat generator/allocator: `app/Services/TibaBerangkatService.php`.
  - Alokasi anggaran otomatis: `app/Services/BudgetAllocationService.php`.
- Helper domain:
  - Tanggal (parsing format Indonesia, kalender): `app/Helpers/DateHelper.php`.
  - Terbilang rupiah (lowercase/Title Case): `app/Helpers/TerbilangHelper.php`.
- Otorisasi & akses:
  - Middleware alias di `bootstrap/app.php`: `approved`, `admin`, `super_admin`.
  - Implementasi: `app/Http/Middleware/EnsureUserIsApproved.php`, `EnsureUserIsAdmin.php`, `EnsureUserIsSuperAdmin.php`.
  - Kebijakan (Policy) contoh: `app/Policies/LpjPolicy.php` (download/preview/regenerate berbasis pemilik data).
- Admin panel (Filament): `app/Providers/Filament/AdminPanelProvider.php`.

## 4) Fitur Utama
- RAB (Rencana Anggaran Biaya):
  - Master RAB dengan item dinamis dan faktor (orang/hari/desa/kali kegiatan), subtotal otomatis, total tersimpan.
  - Struktur komponen tetap (5 komponen) melalui `App\Models\Rab::components()`.
  - Ekspor Excel satu RAB atau master multi-sheet; dukungan template kustom.
  - Manajemen master menu/kegiatan RAB (rab_menus, rab_kegiatans) untuk pengelompokan.
- POA (Plan of Action):
  - Mengacu RAB; menyimpan schedule per bulan (JSON) termasuk count/amount/participant_ids, penandaan (marked), dan status klaim.
  - Partisipan per POA dan/atau override per bulan (participant_ids per bulan).
  - Aksi massal (lock/unlock klaim), penanda, carryover.
  - Klaim per bulan memicu pembuatan LPJ (SPPT, SPPD) otomatis.
- LPJ (Laporan Pertanggungjawaban):
  - SPPT/SPPD dengan peserta, nominal transport/per diem, dokumen Word (template) dengan variabel yang terisi otomatis.
  - Download/preview/regenerate; unduh banyak dokumen sebagai ZIP.
- Tiba Berangkat:
  - Dibentuk dari LPJ (single/pasangan SPPT-SPPD), nomor surat otomatis, alokasi tanggal/pejabat TTD per desa.
- Saldo Pegawai:
  - Rekap transport, per diem, total saldo dari LPJ (credited_employee_id), plus entri opsional.
- Pengumuman: CRUD + toggle aktif; endpoint publik untuk pengumuman aktif.
- Peran & Akses: Super Admin, Admin, User, dengan persetujuan (approved) sebelum akses fitur utama.

## 5) Alur Proses Kunci
- Perencanaan → Anggaran → Pelaksanaan → Pertanggungjawaban → Dokumen:
  1) RAB disusun (komponen/menu/kegiatan + item faktor) dan total dihitung. Admin dapat ekspor Excel (template tunggal/master/stacked).
  2) POA dibuat dari RAB untuk tahun tertentu, mengatur jadwal bulanan (count/amount/participants). Pengguna dapat menandai, mengunci klaim, dan memutakhirkan metadata bulanan.
  3) Klaim POA bulanan membuat LPJ SPPT dan/atau SPPD, menghitung peserta dan nominal berdasarkan faktor RAB serta input jumlah desa.
  4) Tiba Berangkat dapat dibuat otomatis dari LPJ, termasuk pembangkitan nomor dan detail per desa.
  5) Dokumen LPJ (Word .docx) dihasilkan dari template; dapat diunduh satuan atau bulk (ZIP).
- Borrowed name & credit (sesuai kebutuhan ASN):
  - Map “borrowed name” per bulan (`borrowed_map`) untuk mengganti nama pada dokumen, namun kredit saldo tetap ke pelaksana sebenarnya (credited_employee_id).
  - Teruji di `tests/Feature/PoaBorrowedNameClaimTest.php:16`.

## 6) Model Data (inti)
- RAB: `rabs`, `rab_items`, `rab_menus`, `rab_kegiatans`.
  - Item menyimpan `factors` (array) untuk komposisi kuantitas dan `unit_price` → `subtotal` → agregasi ke `rabs.total`.
- POA: `poas`, `poa_participants`.
  - `poas.schedule` (JSON) menyimpan meta per-bulan, termasuk partisipan spesifik bulan.
- LPJ: `lpjs`, `lpj_participants`, `lpj_villages` (pivot ke beberapa desa).
  - Peserta menyimpan `transport_amount`, `per_diem_rate`, `per_diem_days`, `per_diem_amount`, `total_amount` dan relasi `borrowed_employee_id`, `credited_employee_id`.
- Anggaran: `annual_budgets`, `budget_allocations` (alokasi RAB per tahun; unique composite `annual_budget_id+rab_id`).
- Master data: `employees`, `villages`, `pejabat_ttd`, `rate_settings` (tarif transport/per diem aktif), `activities` (sebagian historis; LPJ kini memakai string kegiatan manual), `global_holidays` (rencana kalender khusus).
- Dokumen TB: `tiba_berangkat`, `tiba_berangkat_detail`.

Rujukan migrasi: contoh `database/migrations/2025_09_28_000030_create_poas_table.php`, `..._create_rabs_table.php`, `..._create_lpj_participants_table.php`, `..._create_budget_allocations_table.php`.

## 7) Routing & Akses
- Rute web: `routes/web.php`.
  - Grup umum (auth + approved): dashboard, master dasar (employees, activities), LPJ, saldos, TB, RAB (index), alokasi (index), POA, dokumen LPJ (download/preview/regenerate/bulk), pejabat TTD.
  - Super Admin: CRUD penuh RAB, alokasi, ekspor RAB (beragam varian), bulk lock POA, manajemen user + approve.
  - Admin: master villages, rate-settings, master RAB menus/kegiatans, budgets, pengumuman.
  - Endpoint publik pengumuman aktif: `routes/web.php:10` (`/api/pengumuman/active`).
- Middleware alias disetel di `bootstrap/app.php:13`.
- Policy LPJ: misalnya `app/Policies/LpjPolicy.php:8` (akses berbasis `created_by` kecuali admin/super_admin).

## 8) Dokumen & Ekspor
- RAB Excel:
  - Ekspor per-RAB (FromView) dan master multi-sheet (summary per komponen, per-menu/per-kegiatan, daftar semua RAB).
  - Template kustom/stacked di `resources/templates/*.xlsx`.
- LPJ Word (.docx):
  - Template per tipe SPPT/SPPD dan jumlah peserta, dicari di `storage/app/templates`.
  - Variabel disuntik (nomor/tanggal/kegiatan/desa/peserta/terbilang) melalui `app/Services/LpjDocumentService.php`.
  - Bulk download ZIP dengan pengecekan ketersediaan ekstensi ZIP.

## 9) Pengembangan, Build & Deploy
- Setup ringkas (README): PHP 8.2+, Composer, Node 18+, MySQL/SQLite.
  - `cp .env.example .env`, `composer install`, `npm install`, `php artisan key:generate && php artisan migrate --seed`, `php artisan serve` + `npm run dev`.
- Script dev terpadu: composer `dev` menjalankan server, queue:listen, pail log, vite secara paralel.
- Konfigurasi contoh: `.env.example` → MySQL, session database, queue database, filesystem local.
- Template ekspor: `resources/templates`.

## 10) Pengujian
- Tersedia kumpulan tes Jetstream/Livewire standar dan pengujian domain penting:
  - Borrowed name & credit LPJ pada klaim POA: `tests/Feature/PoaBorrowedNameClaimTest.php:16`.

## 11) Keamanan & Observabilitas
- Otentikasi: Sanctum + Jetstream (2FA opsional), middleware `approved` memblokir akses sebelum disetujui admin.
- Otorisasi granular:
  - Policy LPJ membatasi view/update/delete/download ke pembuat (kecuali admin/super_admin).
  - Middleware `admin`/`super_admin` pada rute sensitif (CRUD RAB/allocations, user, bulk POA, pengumuman, dsb.).
- Logging aktivitas: Spatie Activitylog pada entitas utama (RAB, LPJ, Employee, RateSetting).
- Media/doc: penyimpanan di `storage/app/public` (LPJ docs), sanitasi nama file untuk keamanan.
- Ketahanan: cek ketersediaan ekstensi ZIP sebelum bulk download; validasi input ekstensif pada controller.

## 12) Temuan & Rekomendasi
- Konsistensi domain “kegiatan”:
  - LPJ kini memakai string kegiatan; pastikan konsistensi referensi antar RAB/POA/LPJ dan indeks pencarian.
- Kinerja & integritas data:
  - Pastikan indeks pada kolom filter utama (mis. `poas.year`, `rabs.komponen`, `rabs.rincian_menu`, `rabs.kegiatan`, foreign keys umum).
  - Validasi referensial sudah baik (FK, unique composite pada allocations), lanjutkan pola ini.
- Generasi dokumen:
  - Pertimbangkan antrian (queue) untuk pembuatan dokumen masal (ZIP) agar non-blocking.
  - Tambah halaman manajemen template (unggah/versi) untuk LPJ & RAB.
- Kalender & hari libur:
  - Sudah ada `global_holidays`; integrasikan di UI tanggal surat/kegiatan & validasi (lihat ide di `pengembangan.md`).
- Rate & faktor dinamis:
  - `RateSetting` sudah disiapkan; lanjutkan pemanfaatan rate aktif untuk perhitungan default.
- Akses:
  - Ada hard-code fallback admin email `admin@admin.com` (lihat `app/Models/User.php:49`). Pertimbangkan konfigurasi via env/role seutuhnya.
- Pengujian:
  - Tambah tes untuk klaim POA multi-partisipan/multi-bulan, ekspor master RAB, dan dokumen TB.

## 13) Berkas Rujukan Penting
- Rute: `routes/web.php`, `routes/api.php`.
- Controller kunci: 
  - POA: `app/Http/Controllers/PoaController.php:1`
  - RAB: `app/Http/Controllers/RabController.php:1`
  - LPJ: `app/Http/Controllers/LpjController.php:1`, dokumen: `app/Http/Controllers/LpjDocumentController.php:1`
  - Tiba Berangkat: `app/Http/Controllers/TibaBerangkatController.php:1`
  - Saldo Pegawai: `app/Http/Controllers/EmployeeSaldoController.php:1`
- Model inti: `app/Models/*.php` (RAB/POA/LPJ/Saldo/Budget/Rate/DSB).
- Layanan: `app/Services/*.php` (Ekspor, Dokumen, TB, Alokasi Budjet).
- Template: `resources/templates/*` (xlsx), `storage/app/templates/*` (docx).
- UI: `resources/views/*` (Blade + Livewire).

---
Dokumen ini merangkum arsitektur, fitur, serta alur utama aplikasi beserta rujukan file untuk memudahkan navigasi dan pengembangan lanjutan.
