# Panduan Lengkap Sistem Export Template RAB BOK Puskesmas

## 📋 Daftar Isi
1. [Pengantar](#pengantar)
2. [Fitur Utama](#fitur-utama)
3. [Cara Menggunakan](#cara-menggunakan)
4. [Panduan Template Kustom](#panduan-template-kustom)
5. [Daftar Placeholder](#daftar-placeholder)
6. [Contoh Implementasi](#contoh-implementasi)
7. [Troubleshooting](#troubleshooting)
8. [FAQ](#faq)

---

## 🎯 Pengantar

Sistem Export Template RAB (Rencana Anggaran Biaya) BOK Puskesmas adalah fitur yang memungkinkan pengguna untuk mengekspor semua data RAB dalam format Excel yang terstruktur dan profesional. Sistem ini mendukung dua mode export:
- **Template Otomatis**: Template yang dibangkitkan secara otomatis oleh sistem
- **Template Kustom**: Template yang disiapkan sendiri oleh pengguna

### **Keunggulan Sistem:**
- ✅ Export multi-sheet dalam satu file Excel
- ✅ Format profesional dan konsisten
- ✅ Data terstruktur per komponen RAB
- ✅ Dashboard summary dengan statistik lengkap
- ✅ Dukungan template kustom
- ✅ Update data real-time

---

## 🚀 Fitur Utama

### **1. Export Template Otomatis**
- Membuat template Excel secara otomatis tanpa perlu file template
- Format standar yang sudah dioptimalkan
- Siap digunakan langsung

### **2. Export Template Kustom**
- Gunakan template Excel yang sudah Anda siapkan
- Kontrol penuh atas tampilan dan format
- Dukungan branding dan styling kustom

### **3. Struktur Multi-Sheet**
- **Sheet Summary**: Dashboard statistik keseluruhan
- **Sheet Komponen**: Detail data per komponen RAB
- **Format Konsisten**: Standardisasi format di semua sheet

### **4. Dynamic Placeholder System**
- Penggantian data otomatis menggunakan placeholder
- Update data real-time setiap export
- Fleksibilitas dalam penempatan data

---

## 📖 Cara Menggunakan

### **Persyaratan Akses**
- Harus login sebagai **Super Admin**
- Memiliki hak akses ke menu RAB

### **Langkah-Langkah Export Template:**

1. **Login ke Sistem**
   ```
   Masuk dengan akun Super Admin
   ```

2. **Akses Halaman RAB**
   ```
   Buka menu RAB → Daftar RAB (/rabs)
   ```

3. **Klik Tombol Export**
   - Tombol **Export Template** (hijau emerald)
   - Tombol **Export All** (ungu) untuk export tanpa template

4. **Download File**
   - File akan diunduh otomatis
   - Format nama: `MASTER_RAB_TEMPLATE_TANGGAL_WAKTU.xlsx`

### **Hasil Export:**
- 1 file Excel dengan multiple sheets
- Sheet Summary (Dashboard)
- Sheet per Komponen (5 sheets)
- Data lengkap semua RAB

---

## 🎨 Panduan Template Kustom

### **Membuat Template Kustom:**

1. **Buat File Excel Baru**
   - Gunakan Microsoft Excel atau LibreOffice Calc
   - Simpan dengan nama: `master_rab_template.xlsx`

2. **Struktur Sheet yang Direkomendasikan:**

   #### **Sheet 1: Summary**
   ```
   Cell A1: MASTER DASHBOARD RAB BOK PUSKESMAS
   Cell A2: Export Date: [[EXPORT_DATE]]
   Row 4: Headers (No, Komponen, Jumlah RAB, Total Anggaran, Rata-rata)
   Cell A5: [[COMPONENT_DATA]]
   ```

   #### **Sheet 2-6: Komponen**
   ```
   Cell A1: [[COMPONENT_NAME]]
   Cell A2: Total RAB: [[TOTAL_RABS]] | Total Budget: Rp [[TOTAL_BUDGET]]
   Row 4: Headers (No, Rincian Menu, Kegiatan, Item, Faktor, Harga, Sub Total)
   Cell A5: [[ITEM_DATA]]
   ```

3. **Menyimpan Template:**
   ```
   Lokasi: resources/templates/master_rab_template.xlsx
   Format: .xlsx
   ```

### **Tips Template Kustom:**
- Gunakan warna untuk headers
- Set lebar kolom yang sesuai
- Tambahkan logo atau branding
- Format angka untuk kolom numerik
- Gunakan font yang konsisten

---

## 🏷️ Daftar Placeholder

### **Global Placeholders:**
| Placeholder | Deskripsi | Contoh Output |
|-------------|-----------|---------------|
| `[[EXPORT_DATE]]` | Tanggal dan waktu export | 15 Oktober 2025 01:30 |
| `[[COMPONENT_NAME]]` | Nama komponen RAB | Peningkatan Layanan Kesehatan |
| `[[TOTAL_BUDGET]]` | Total anggaran (format) | Rp 1.500.000 |
| `[[TOTAL_BUDGET_NUMERIC]]` | Total anggaran (numeric) | 1500000 |
| `[[TOTAL_RABS]]` | Total jumlah RAB (format) | 25 |
| `[[TOTAL_RABS_NUMERIC]]` | Total jumlah RAB (numeric) | 25 |

### **Data Placeholders:**
| Placeholder | Fungsi | Lokasi |
|-------------|---------|---------|
| `[[COMPONENT_DATA]]` | Penanda data komponen di sheet Summary | Cell A5 sheet Summary |
| `[[ITEM_DATA]]` | Penanda data item di sheet komponen | Cell A5 sheet komponen |

### **Cara Penggunaan:**
1. Tempatkan placeholder di cell yang diinginkan
2. Sistem akan mengganti placeholder dengan data aktual
3. Format akan disesuaikan dengan tipe placeholder

---

## 💡 Contoh Implementasi

### **Contoh Sheet Summary:**
```
A1: MASTER DASHBOARD RAB BOK PUSKESMAS
A2: Export Date: [[EXPORT_DATE]]

A4: No
B4: Komponen
C4: Jumlah RAB
D4: Total Anggaran (Rp)
E4: Rata-rata (Rp)

A5: [[COMPONENT_DATA]]
```

### **Contoh Sheet Komponen:**
```
A1: [[COMPONENT_NAME]]
A2: Total RAB: [[TOTAL_RABS]] | Total Budget: Rp [[TOTAL_BUDGET]]

A4: No
B4: Rincian Menu
C4: Kegiatan
D4: Item
E4: Faktor Perkalian
F4: Harga Satuan (Rp)
G4: Sub Total (Rp)

A5: [[ITEM_DATA]]
```

### **Hasil Setelah Export:**
```
A1: Peningkatan Layanan Kesehatan Sesuai Siklus Hidup
A2: Total RAB: 15 | Total Budget: Rp 45.500.000

Data akan diisi otomatis dari database:
- No: Nomor urut
- Rincian Menu: Nama menu kegiatan
- Kegiatan: Detail kegiatan
- Item: Item RAB dengan harga
- Faktor Perkalian: Faktor perhitungan (orang x kali)
- Harga Satuan: Harga per item
- Sub Total: Total per item
```

---

## 🔧 Troubleshooting

### **Masalah Umum & Solusi:**

#### **1. Template Tidak Ditemukan**
**Masalah:** Muncul error "Template file not found"
**Solusi:**
- Pastikan file `master_rab_template.xlsx` ada di folder `resources/templates/`
- Jika tidak ada, sistem akan menggunakan template otomatis

#### **2. Export Gagal**
**Masalah:** Muncul error "Internal Server Error"
**Solusi:**
- Pastikan login sebagai Super Admin
- Periksa koneksi internet
- Refresh halaman dan coba lagi

#### **3. Format Tidak Sesuai**
**Masalah:** Tampilan Excel tidak sesuai harapan
**Solusi:**
- Periksa penulisan placeholder (harus persis)
- Pastikan nama sheet sesuai dengan komponen
- Cek format file (harus .xlsx)

#### **4. Data Tidak Muncul**
**Masalah:** Cells kosong setelah export
**Solusi:**
- Pastikan placeholder ditulis dengan benar
- Cek posisi placeholder `[[DATA]]`
- Pastikan ada data RAB di sistem

#### **5. Permission Error**
**Masalah:** Tidak bisa akses menu export
**Solusi:**
- Pastikan login sebagai Super Admin
- Hubungi administrator sistem

### **Debugging Tips:**
- Periksa Laravel log untuk error detail
- Test dengan template otomatis terlebih dahulu
- Validasi struktur template sebelum digunakan

---

## ❓ FAQ (Frequently Asked Questions)

#### **Q: Apa saja syarat untuk menggunakan fitur export template?**
A: Anda harus login sebagai Super Admin dan memiliki akses ke menu RAB.

#### **Q: Bisakah saya membuat template dengan warna dan styling kustom?**
A: Ya, Anda bisa membuat template dengan warna, font, dan styling sesuai keinginan.

#### **Q: Berapa banyak sheet yang akan dihasilkan?**
A: Total 6 sheets: 1 sheet Summary + 5 sheets untuk setiap komponen RAB.

#### **Q: Apa yang terjadi jika template kustom tidak ditemukan?**
A: Sistem akan otomatis membuat template standar sebagai fallback.

#### **Q: Bisakah saya menambahkan logo perusahaan di template?**
A: Ya, Anda bisa menambahkan logo, branding, dan elemen grafis lainnya.

#### **Q: Format apa yang didukung untuk template?**
A: Hanya format .xlsx yang didukung untuk kompatibilitas maksimal.

#### **Q: Bagaimana cara memperbarui data di template?**
A: Data akan otomatis diperbarui setiap kali Anda melakukan export.

#### **Q: Apakah ada batasan ukuran file export?**
A: Tidak ada batasan spesifik, tetapi disarankan untuk data yang wajar (kurang dari 10MB).

#### **Q: Bisakah saya mengubah nama sheet di template kustom?**
A: Nama sheet harus sesuai dengan nama komponen RAB yang ada di sistem.

#### **Q: Apakah data sensitif aman dalam export template?**
A: Ya, hanya Super Admin yang bisa mengakses fitur export ini.

---

## 📞 Bantuan Teknis

Jika Anda mengalami masalah yang tidak tercantum dalam panduan ini:

1. **Hubungi Administrator Sistem**
2. **Periksa Dokumentasi Lengkap** di folder `resources/templates/`
3. **Lihat Error Log** untuk detail teknis
4. **Backup Data** sebelum melakukan perubahan besar

---

## 📝 Changelog

### **Version 1.0 (15 Oktober 2025)**
- ✅ Fitur export template otomatis
- ✅ Dukungan template kustom
- ✅ Sistem placeholder dinamis
- ✅ Multi-sheet export
- ✅ Dokumentasi lengkap

---

**© 2025 - Sistem RAB BOK Puskesmas**
*Direvisi: 15 Oktober 2025*