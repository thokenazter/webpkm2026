# Master RAB Template Export

## Cara Membuat Template Excel Multi-Sheet

### 1. Buat File Template
Buat file Excel baru dengan nama `master_rab_template.xlsx` di folder `resources/templates/`

### 2. Struktur Template yang Direkomendasikan

#### **Sheet 1: Summary Dashboard**
- Nama sheet: `Summary` atau `Summary Dashboard`
- Headers:
  - Cell A1: `MASTER DASHBOARD RAB BOK PUSKESMAS`
  - Cell A2: `[[EXPORT_DATE]]` (akan diganti dengan tanggal export)
- Data Summary:
  - Cell A4: `No`
  - Cell B4: `Komponen`
  - Cell C4: `Jumlah RAB`
  - Cell D4: `Total Anggaran (Rp)`
  - Cell E4: `Rata-rata (Rp)`
- Placeholder untuk data:
  - Cell A5: `[[COMPONENT_DATA]]` (baris penanda untuk data komponen)

#### **Sheet 2-n: Sheet per Komponen**
Buat sheet untuk setiap komponen RAB:
- Nama sheet sesuai dengan nama komponen:
  - `Peningkatan Layanan Kesehatan Sesuai Siklus Hidup`
  - `Surveilans, respons penyakit dan kesehatan lingkungan`
  - `Pemberian Makanan Tambahan (PMT) berbahan pangan lokal`
  - `MANAGEMEN PUSKESMAS`
  - `INSENTIF UKM`

- Headers untuk setiap sheet:
  - Cell A1: `[[COMPONENT_NAME]]` (akan diganti dengan nama komponen)
  - Cell A2: `Total RAB: [[TOTAL_RABS]] | Total Budget: [[TOTAL_BUDGET]]`
  - Cell A4: `No`
  - Cell B4: `Rincian Menu`
  - Cell C4: `Kegiatan`
  - Cell D4: `Item`
  - Cell E4: `Faktor Perkalian`
  - Cell F4: `Harga Satuan (Rp)`
  - Cell G4: `Sub Total (Rp)`
- Placeholder untuk data:
  - Cell A5: `[[ITEM_DATA]]` (baris penanda untuk data item)

### 3. Placeholder yang Didukung

#### **Global Placeholders:**
- `[[EXPORT_DATE]]` - Tanggal dan waktu export
- `[[COMPONENT_NAME]]` - Nama komponen
- `[[TOTAL_BUDGET]]` - Total anggaran (format: Rp 1.000.000)
- `[[TOTAL_BUDGET_NUMERIC]]` - Total anggaran (numeric)
- `[[TOTAL_RABS]]` - Total jumlah RAB (format: 10)
- `[[TOTAL_RABS_NUMERIC]]` - Total jumlah RAB (numeric)

#### **Data Placeholders:**
- `[[COMPONENT_DATA]]` - Penanda untuk data komponen di sheet Summary
- `[[ITEM_DATA]]` - Penanda untuk data item RAB di sheet komponen

### 4. Styling Template (Opsional)
- Gunakan warna dan formatting untuk header
- Set lebar kolom yang sesuai
- Tambahkan branding jika perlu
- Format angka untuk kolom numeric

### 5. Contoh Penggunaan Placeholder

#### Di Sheet Summary:
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

#### Di Sheet Komponen:
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

### 6. Cara Kerja Sistem
1. Jika template tidak ada, sistem akan generate template otomatis
2. Sistem akan mengganti semua placeholder dengan data aktual
3. Data akan diisi secara otomatis dengan format yang sudah ada
4. File Excel akan diunduh dengan nama: `MASTER_RAB_TEMPLATE_YYYY-MM-DD_HH-i.xlsx`

### 7. Keuntungan Menggunakan Template
- Kontrol penuh atas tampilan dan format
- Branding dan styling kustom
- Struktur sheet yang sesuai kebutuhan
- Header dan footer yang diinginkan
- Format angka dan tanggal yang konsisten

### 8. Catatan Penting
- Pastikan sheet names sesuai dengan nama komponen yang ada di sistem
- Placeholder harus ditulis persis seperti contoh (termasuk kurung siku)
- Jangan mengubah struktur placeholder yang sudah ada
- File template akan digunakan sebagai dasar, data akan mengisi placeholder yang ada