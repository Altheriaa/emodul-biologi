# Rencana Implementasi: Penyesuaian LKM (Lembar Kerja Mahasiswa) Berdasarkan PDF Terbaru

Rencana ini dibuat untuk merestrukturisasi isi pertanyaan, tabel pengamatan, dan tugas pada Lembar Kerja Mahasiswa (LKM) untuk modul **Teknik Grafting sebagai Eksplorasi Jaringan Tumbuhan** (pertemuan 1 hingga 4) agar sesuai dengan dokumen PDF yang dilampirkan oleh pengguna.

---

## Ringkasan Perubahan Utama

Sesuai dengan PDF, alur LKM dipecah menjadi:
1. **LKM Pertemuan 1**: Sintak 1-3 PjBL (Pertanyaan Esensial, Perencanaan Proyek, Penyusunan Jadwal)
2. **LKM Pertemuan 2**: Sintak 4 PjBL (Pelaksanaan & Monitoring Proyek Grafting)
3. **LKM Pertemuan 3**: Sintak 5 PjBL (Penilaian Hasil / Pengamatan & Dokumentasi Perkembangan)
4. **LKM Pertemuan 4**: Sintak 6 PjBL (Evaluasi & Refleksi, Penilaian Diri, Essay Refleksi)

Karena struktur data baru sangat berbeda dengan struktur data lama, kita akan membuat migrasi database baru untuk memperbarui skema tabel LKM agar kompatibel dan rapi sesuai struktur masing-masing pertemuan.

---

## Rencana Perubahan Database (Migration & Models)

Kita akan membuat migrasi baru untuk menyesuaikan tabel-tabel berikut:

### 1. LKM Pertemuan 1 (Sintak 1-3)
*   **`lkm_p1_questions`** (Diperbarui):
    *   `q1_jenis_tumbuhan_cocok` (Essay)
    *   `q2_jaringan_terlibat` (Essay)
    *   `q3_pemilihan_batang_bawah` (Essay)
    *   `q4_peran_kambium` (Essay)
    *   `q5_kondisi_lingkungan` (Essay) [NEW]
*   **`lkm_p1_specs`** [NEW] (Tabel Pemilihan Tanaman):
    *   `variabel` (Nama Spesies batang bawah, batang atas, usia, diameter, kondisi kambium, alasan kompatibilitas)
    *   `tanaman_a`
    *   `tanaman_b`
    *   `alasan_pemilihan`
*   **`lkm_p1_items`** [NEW] (Tabel Alat & Bahan):
    *   `alat`
    *   `bahan`
*   **`lkm_p1_procedures`** [NEW] (Tabel Prosedur Kerja):
    *   `step_number`
    *   `tahap`
    *   `penjelasan`
*   **`lkm_p1_schedules`** [NEW] (Tabel Jadwal Capaian):
    *   `pertemuan` (1-4)
    *   `target_kegiatan`

### 2. LKM Pertemuan 2 (Sintak 4)
*   **`lkm_p2_items`** (Persiapan Alat & Bahan):
    *   `alat_bahan`
*   **`lkm_p2_specs`** (Identifikasi Spesimen):
    *   `keterangan` (Nama Spesies, usia tanaman, diameter, jumlah mata tunas, kondisi fisik, kondisi kambium)
    *   `batang_bawah`
    *   `batang_atas`
    *   `alasan`
*   **`lkm_p2_procedures`** [NEW] (Prosedur Pelaksanaan Grafting):
    *   `step_number`
    *   `tahap_kegiatan`
    *   `kondisi_jaringan`
*   **`lkm_p2_monitorings`** [NEW] (Monitoring Proyek):
    *   `aspek` (Kondisi sambungan, kelembapan, lokasi penyimpanan, kekuatan sambungan, kondisi tanaman)
    *   `hasil_pengamatan`
*   **`lkm_p2_questions`** [NEW] (Esensial Questions - 8 Pertanyaan):
    *   `q1_ditutup_rapat`
    *   `q2_pengaruh_kelembapan`
    *   `q3_lokasi_penyimpanan`
    *   `q4_kekuatan_lemah`
    *   `q5_keberhasilan_kegagalan`
    *   `q6_peran_xilem`
    *   `q7_peran_epidermis`
    *   `q8_aktivitas_meristem`

### 3. LKM Pertemuan 3 (Sintak 5)
*   **`lkm_p3_growths`** [NEW] (Pengamatan Pertumbuhan Tunas & Daun):
    *   `parameter` (Jumlah tunas, panjang tunas, jumlah daun, ukuran daun, warna daun, kondisi daun, tekstur daun)
    *   `data_jumlah`
    *   `deskripsi_kondisi`
*   **`lkm_p3_scions`** [NEW] (Pengamatan Kondisi Batang Atas):
    *   `parameter` (Warna batang, turgiditas, pertumbuhan baru, kondisi sambungan, tanda nekrosis)
    *   `kondisi_deskripsi`
    *   `dokumentasi_path` (Foto)
*   **`lkm_p3_rootstocks`** [NEW] (Pengamatan Kondisi Batang Bawah):
    *   `parameter` (Warna batang, kondisi akar, tunas baru, kondisi sambungan, kondisi daun tersisa)
    *   `kondisi_deskripsi`
    *   `dokumentasi_path` (Foto)
*   **`lkm_p3_connections`** [NEW] (Pengamatan Kondisi Sambungan):
    *   `rincian_sambungan` (Uraian rinci)
    *   `is_tumbuh_tunas` (Boolean)
    *   `alasan`
    *   `foto_sambungan` (Upload Foto)
*   **`lkm_p3_questions`** [NEW] (Esensial Questions - 5 Pertanyaan):
    *   `q1_apakah_berhasil`
    *   `q2_indikator_keberhasilan`
    *   `q3_tunas_baru_muncul`
    *   `q4_hubungan_jaringan_pengangkut`
    *   `q5_faktor_penyebab_gagal`

### 4. LKM Pertemuan 4 (Sintak 6)
*   **`lkm_p4_analyses`** [NEW] (Analisis Keberhasilan):
    *   `variabel_analisis` (1. jumlah tunas berhasil, 2. jumlah/ukuran daun, 3. warna daun, 4. warna/kondisi batang atas, 5. warna/kondisi batang bawah, 6. kondisi sambungan, 7. terbentuknya kalus, 8. tidak ada nekrosis)
    *   `hasil_pengamatan`
*   **`lkm_p4_deep_questions`** [NEW] (Pertanyaan Analisis Mendalam - 5 Pertanyaan):
    *   `q1_tujuan_grafting`
    *   `q2_karakteristik_anatomi`
    *   `q3_kesejajaran_kambium`
    *   `q4_faktor_anatomi_inkompatibilitas`
    *   `q5_proses_penyembuhan`
*   **`lkm_p4_self_assessments`** [NEW] (Refleksi Penilaian Diri):
    *   `aspek` (pemahaman konsep jaringan, pemahaman prinsip grafting, dst.)
    *   `skor` (1 - 5)
    *   `catatan`
*   **`lkm_p4_reflections`** (Refleksi Essay - 10 Pertanyaan):
    *   `r1_pengalaman_baru`
    *   `r2_kesulitan`
    *   `r3_cara_mengatasi`
    *   `r4_manfaat_pjbl`
    *   `r5_perasaan`
    *   `r6_kesejajaran_kambium`
    *   `r7_peran_kutikula`
    *   `r8_perbedaan_sel_epidermis`
    *   `r9_kondisi_lingkungan`
    *   `r10_fungsi_sungkup`

---

## Rencana Perubahan Frontend (Inertia.js / Vue 3)

Kita akan memperbarui tampilan form pengerjaan mahasiswa di [Form.vue](file:///Users/altheria/Project%20Web%20Dev/SaaS-emodul-bio/resources/js/Pages/RoleMahasiswa/Pembelajaran/LKMGrafting/Form.vue):

1.  **LKM 1 Tab/View**:
    *   Form pengisian 5 Pertanyaan Esensial (Sintak 1).
    *   Tabel Spesimen Tanaman A & B beserta alasan (Sintak 2).
    *   Tabel Alat & Bahan (Sintak 2).
    *   Tabel Prosedur Kerja 1-10 (Sintak 3).
    *   Tabel Jadwal Capaian Pertemuan 1-4 (Sintak 3).
2.  **LKM 2 Tab/View**:
    *   Tabel Persiapan Alat & Bahan (Sintak 4).
    *   Tabel Identifikasi Spesimen (Sintak 4).
    *   Tabel Prosedur Pelaksanaan (Sintak 4).
    *   Tabel Monitoring Proyek (Sintak 4).
    *   Form pengisian 8 Pertanyaan Esensial (Sintak 4).
3.  **LKM 3 Tab/View** (Sebelumnya Kosong):
    *   Tabel Pengamatan Pertumbuhan Tunas & Daun (Sintak 5).
    *   Tabel Pengamatan Kondisi Batang Atas (Sintak 5).
    *   Tabel Pengamatan Kondisi Batang Bawah (Sintak 5).
    *   Form Pengamatan Kondisi Sambungan + Radio Button "Tumbuh Tunas" + Input Alasan (Sintak 5).
    *   Komponen Upload Foto Kondisi Sambungan (Sintak 5).
    *   Form pengisian 5 Pertanyaan Esensial (Sintak 5).
4.  **LKM 4 Tab/View**:
    *   Tabel Analisis Keberhasilan Grafting (Sintak 6).
    *   Form pengisian 5 Pertanyaan Analisis Mendalam (Sintak 6).
    *   Tabel Penilaian Diri (Skor 1-5 & Catatan) (Sintak 6).
    *   Form pengisian 10 Pertanyaan Refleksi (Sintak 6).
    *   Tautan atau informasi untuk mengunduh format Laporan Praktikum.

---

## Rencana Penyesuaian di Sisi Admin & Dosen
*   **`LKMGraftingController` Admin**: Diperbarui untuk meng-eager load relasi data yang baru saat admin/dosen mereview atau melihat detail submission.
*   **Halaman Review/Detail Submission Admin/Dosen**: Diperbarui agar menampilkan data pengisian LKM mahasiswa sesuai dengan layout tabel dan pertanyaan baru.

---

## Rencana Verifikasi

### Manual Verification
1.  Menjalankan migrasi database baru (`php artisan migrate`).
2.  Membuka halaman LKM Mahasiswa untuk Pertemuan 1, 2, 3, dan 4 untuk memverifikasi bahwa semua field tabel dan input essay tampil dengan benar sesuai PDF.
3.  Mengisi data uji (draft dan submit) pada LKM 1, 2, 3, dan 4 sebagai Mahasiswa.
4.  Memeriksa data di database apakah tersimpan dengan benar di tabel baru.
5.  Membuka halaman detail LKM Mahasiswa dari sisi Admin/Dosen untuk memastikan tampilan review LKM sesuai dengan data yang dikirimkan.
