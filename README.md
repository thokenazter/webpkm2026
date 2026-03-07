# BOK — Manajemen Kegiatan & Anggaran

Aplikasi internal untuk mengelola perencanaan dan realisasi kegiatan serta anggaran:
- POA (Plan of Action) per tahun dan per kegiatan
- RAB (Rencana Anggaran Biaya) dan alokasi anggaran
- LPJ (Laporan Pertanggungjawaban) beserta peserta dan dokumen
- Tiba Berangkat (surat tugas/berita acara) dan unduhan dokumen
- Ringkasan Saldo Pegawai berdasarkan partisipasi LPJ + entri opsional
- Pengumuman (announcements) dan persetujuan pengguna

## Fitur Utama
- POA: CRUD, penjadwalan bulanan, klaim, progress item
- RAB: master menu/komponen/kegiatan, ringkasan, ekspor Excel
- LPJ: pembuatan dari aktivitas, dokumen (preview/unduh/regenerate)
- Tiba Berangkat: auto from LPJ, quick update, unduh dokumen
- Saldo Pegawai: rekap transport, per diem, total saldo + entri opsional
- Peran & Akses: Super Admin, Admin, User (akses dibatasi via middleware)

## Teknologi
- Laravel 12 (PHP 8.2), Jetstream + Livewire 3, Tailwind CSS
- Filament 3 (admin tooling), Spatie Activitylog & Medialibrary
- Laravel Excel (maatwebsite/excel), PHPWord untuk dokumen

## Quick Start
Persyaratan: PHP 8.2+, Composer, Node.js 18+, MySQL/SQLite

1. Salin konfigurasi: `cp .env.example .env` lalu sesuaikan `DB_*`
2. Install dependensi backend: `composer install`
3. Install dependensi frontend: `npm install`
4. Generate key & migrasi + seeder: `php artisan key:generate && php artisan migrate --seed`
5. Jalankan aplikasi: `php artisan serve` (opsional: `npm run dev` untuk asset)

Akun awal (seeder):
- Super Admin: `admin@admin.com` / `12121212`
- User: `user1@admin.com` / `12121212` (contoh; ada beberapa user demo)

## Catatan
- Template ekspor RAB: `resources/templates/templaterab.xlsx`
- Beberapa rute hanya untuk Admin/Super Admin (master RAB, budget, pengumuman, bulk POA)
- Sesuaikan `APP_URL`, storage, dan queue untuk lingkungan produksi
