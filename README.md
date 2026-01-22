# 🏥 Website Puskesmas Rawat Inap Kabalsiang Benjuring

Website resmi Puskesmas Rawat Inap Kabalsiang Benjuring, Kecamatan Aru Utara Timur Batuley, Kabupaten Kepulauan Aru, Maluku.

## 📋 Tentang

Website ini dibangun untuk memberikan informasi lengkap tentang layanan kesehatan yang tersedia di Puskesmas Rawat Inap Kabalsiang Benjuring. Dengan menerapkan **Integrasi Layanan Primer (ILP)**, Puskesmas melayani seluruh siklus kehidupan masyarakat dari ibu dan anak hingga lanjut usia.

### Status Puskesmas
- 🏠 **Wilayah Kerja:** 5 Desa
- 🛏️ **Jenis Layanan:** Rawat Inap + UGD 24 Jam
- 🏆 **Akreditasi:** UTAMA (Kemenkes RI)
- 👥 **Populasi Dilayani:** ±3.188 Jiwa

## ✨ Fitur Website

### Halaman Publik
- 🏠 **Beranda** - Hero section, info klaster, berita & galeri carousel
- 📚 **Layanan Klaster** - 5 klaster layanan kesehatan terintegrasi
- 👥 **Struktur Organisasi** - Tim dan kepemimpinan Puskesmas
- 📰 **Berita & Kegiatan** - Informasi terkini dengan kategori dan pencarian
- 🖼️ **Galeri** - Dokumentasi kegiatan dengan lightbox
- 📧 **Hubungi Kami** - Form kontak untuk pertanyaan dan masukan

### Panel Admin
- 📊 **Dashboard** - Statistik berita, galeri, dan pesan
- ✏️ **CRUD Berita** - Kelola artikel dengan rich text editor
- 🖼️ **CRUD Galeri** - Upload dan kelola foto kegiatan
- 🏷️ **Kategori** - Organisasi konten
- 📬 **Pesan Masuk** - Baca dan balas pesan pengunjung

## 🛠️ Teknologi

| Teknologi | Versi | Deskripsi |
|-----------|-------|-----------|
| Laravel | 11.x | PHP Framework |
| TailwindCSS | 3.x | Utility-first CSS |
| Alpine.js | 3.x | Lightweight JS framework |
| Swiper.js | 11.x | Modern carousel |
| SQLite | - | Database |
| Vite | 5.x | Build tool |

## 🚀 Instalasi

### Prasyarat
- PHP 8.2+
- Composer
- Node.js 18+
- npm/pnpm

### Langkah Instalasi

```bash
# Clone repository
git clone https://github.com/thokenazter/webpkm2026.git
cd webpkm2026

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Database
php artisan migrate
php artisan db:seed --class=AdminSeeder

# Build assets
npm run build

# Jalankan server
php artisan serve
```

### Akses Admin Panel
- URL: `/admin`
- Email: `admin@puskesmas.id`
- Password: `password`

## 📁 Struktur Folder

```
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          # Controller admin panel
│   │   ├── BeritaController.php
│   │   ├── ContactController.php
│   │   └── HomeController.php
│   └── Models/             # Eloquent models
├── resources/
│   ├── views/
│   │   ├── admin/          # Views admin panel
│   │   ├── berita/         # Halaman berita
│   │   ├── components/     # Komponen reusable
│   │   ├── klaster/        # Halaman klaster
│   │   └── home.blade.php  # Halaman utama
│   └── css/app.css         # Styling TailwindCSS
└── public/
    └── images/             # Asset gambar
```

## 🎨 Fitur UI/UX

- ✅ **Responsive Design** - Optimal di desktop, tablet, dan mobile
- ✅ **Glassmorphism Navbar** - iOS-style frosted glass effect
- ✅ **Modern Cards** - Shadcn-inspired design system
- ✅ **Smooth Animations** - Hover effects dan transitions
- ✅ **Lightbox Gallery** - Zoom foto dengan overlay modal
- ✅ **Auto-play Carousel** - Swiper.js untuk galeri

## 📝 Layanan Klaster

1. **Klaster 1** - Manajemen Puskesmas
2. **Klaster 2** - Kesehatan Ibu dan Anak
3. **Klaster 3** - Usia Dewasa dan Lanjut Usia
4. **Klaster 4** - Penyakit Menular & Kesehatan Lingkungan
5. **Lintas Klaster** - Program lintas sektor

## 📄 Lisensi

Proyek ini dikembangkan untuk Puskesmas Rawat Inap Kabalsiang Benjuring.

---

**© 2026 Beta | Development**  
Puskesmas Rawat Inap Kabalsiang Benjuring  
Desa Benjuring, Kecamatan Aru Utara Timur Batuley
