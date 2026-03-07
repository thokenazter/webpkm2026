Letakkan file template Excel Anda di sini dengan nama:

- templaterab.xlsx

Petunjuk placeholder yang didukung di dalam template:

- [[KOMPONEN]]       → akan diganti dengan nilai komponen
- [[RINCIAN_MENU]]   → akan diganti dengan nilai rincian menu
- [[KEGIATAN]]       → akan diganti dengan nilai kegiatan
- [[TOTAL]]          → akan diganti sebagai ANGKA total RAB (tipe numeric)
- [[ITEMS]]          → baris penanda tempat tabel item akan diisi.

Cara kerja [[ITEMS]]:
- Isi satu baris dengan [[ITEMS]] pada salah satu sel (misal kolom A) dan siapkan format kolom A..E pada baris itu sesuai keinginan.
- Sistem akan menggunakan baris ini sebagai "baris contoh" untuk menyalin gaya ke baris-baris item yang dihasilkan.
- Data yang diisikan kolom:
  - A: No
  - B: Nama Item
  - C: Rincian Faktor (contoh: "Orang x 2 × Desa x 3 × Kali x 12")
  - D: Harga Satuan (angka)
  - E: Sub Total (angka)

Catatan:
- Jika template tidak ditemukan, sistem akan menggunakan export standar berbasis tabel (tanpa template).

Tambahan: Placeholder granular per item (opsional)

- [[ITEM_COUNT]] → jumlah item dalam RAB
- Per item (1-based): gunakan ITEM1, ITEM2, dst.
  - [[ITEM1_LABEL]]            → nama item (mis. "Transport Darat")
  - [[ITEM1_UNIT_PRICE]]       → harga satuan (numeric)
  - [[ITEM1_SUBTOTAL]]         → subtotal (numeric)
  - [[ITEM1_FACTOR_PHRASE]]    → frasa faktor (mis. "Orang x 2 × Desa x 3 × Kali Kegiatan x 12")
  - [[ITEM1_FACTOR1_LABEL]]    → label faktor pertama (mis. "Orang")
  - [[ITEM1_FACTOR1_VALUE]]    → nilai faktor pertama (numeric, mis. 2)
  - [[ITEM1_FACTOR2_LABEL]]    → label faktor kedua
  - [[ITEM1_FACTOR2_VALUE]]    → nilai faktor kedua
  - ... dan seterusnya

Catatan penting:
- Jika sel berisi tepat placeholder numeric (mis. [[ITEM1_UNIT_PRICE]]), nilai akan ditulis sebagai angka (bukan teks), sehingga format angka Excel bisa diterapkan.
- Jika placeholder numeric berada di dalam kalimat (mis. "Sebanyak [[ITEM1_FACTOR1_VALUE]] [[ITEM1_FACTOR1_LABEL]]"), ia akan diganti sebagai teks angka murni.
