<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Restructure all LKM tables to match updated PDF structure.
     *
     * LKM 1 = Sintak 1-3 PjBL (Pertanyaan Esensial, Perencanaan, Jadwal)
     * LKM 2 = Sintak 4 PjBL (Pelaksanaan & Monitoring)
     * LKM 3 = Sintak 5 PjBL (Penilaian Hasil / Pengamatan)
     * LKM 4 = Sintak 6 PjBL (Evaluasi & Refleksi)
     */
    public function up(): void
    {
        // ============================================================
        // DROP OLD TABLES (that will be replaced)
        // ============================================================
        Schema::dropIfExists('lkm_p4_reflections');
        Schema::dropIfExists('lkm_p4_finals');
        Schema::dropIfExists('lkm_p3_monitorings');
        Schema::dropIfExists('lkm_p2_steps');
        Schema::dropIfExists('lkm_p2_items');
        Schema::dropIfExists('lkm_p2_specs');
        Schema::dropIfExists('lkm_p1_questions');
        Schema::dropIfExists('lkm_p1_observations');

        // ============================================================
        // LKM PERTEMUAN 1 (Sintak 1-3)
        // ============================================================

        // Sintak 1: Pertanyaan Esensial (5 essay questions)
        Schema::create('lkm_p1_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lkm_submission_id')->constrained('lkm_submissions')->onDelete('cascade');
            $table->text('q1_jenis_tumbuhan_cocok')->nullable();
            $table->text('q2_jaringan_terlibat')->nullable();
            $table->text('q3_pemilihan_batang_bawah')->nullable();
            $table->text('q4_peran_kambium')->nullable();
            $table->text('q5_kondisi_lingkungan')->nullable();
            $table->timestamps();
        });

        // Sintak 2: Pemilihan Tanaman (Tabel spesimen A & B)
        Schema::create('lkm_p1_specs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lkm_submission_id')->constrained('lkm_submissions')->onDelete('cascade');
            $table->string('variabel');
            $table->text('tanaman_a')->nullable();
            $table->text('tanaman_b')->nullable();
            $table->text('alasan_pemilihan')->nullable();
            $table->timestamps();
        });

        // Sintak 2: Alat & Bahan
        Schema::create('lkm_p1_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lkm_submission_id')->constrained('lkm_submissions')->onDelete('cascade');
            $table->integer('nomor')->default(1);
            $table->string('alat')->nullable();
            $table->string('bahan')->nullable();
            $table->timestamps();
        });

        // Sintak 3: Prosedur Kerja
        Schema::create('lkm_p1_procedures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lkm_submission_id')->constrained('lkm_submissions')->onDelete('cascade');
            $table->integer('step_number');
            $table->string('tahap')->nullable();
            $table->text('penjelasan')->nullable();
            $table->timestamps();
        });

        // Sintak 3: Jadwal Capaian
        Schema::create('lkm_p1_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lkm_submission_id')->constrained('lkm_submissions')->onDelete('cascade');
            $table->integer('pertemuan_ke');
            $table->text('target_kegiatan')->nullable();
            $table->timestamps();
        });

        // ============================================================
        // LKM PERTEMUAN 2 (Sintak 4)
        // ============================================================

        // Persiapan Alat & Bahan (single column list)
        Schema::create('lkm_p2_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lkm_submission_id')->constrained('lkm_submissions')->onDelete('cascade');
            $table->integer('nomor')->default(1);
            $table->string('nama_item')->nullable();
            $table->timestamps();
        });

        // Identifikasi Spesimen (keterangan + batang_bawah + batang_atas + alasan)
        Schema::create('lkm_p2_specs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lkm_submission_id')->constrained('lkm_submissions')->onDelete('cascade');
            $table->string('keterangan');
            $table->text('batang_bawah')->nullable();
            $table->text('batang_atas')->nullable();
            $table->text('alasan')->nullable();
            $table->timestamps();
        });

        // Prosedur Pelaksanaan Grafting
        Schema::create('lkm_p2_procedures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lkm_submission_id')->constrained('lkm_submissions')->onDelete('cascade');
            $table->integer('step_number');
            $table->text('tahap_kegiatan')->nullable();
            $table->text('kondisi_jaringan')->nullable();
            $table->timestamps();
        });

        // Monitoring Proyek
        Schema::create('lkm_p2_monitorings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lkm_submission_id')->constrained('lkm_submissions')->onDelete('cascade');
            $table->string('aspek');
            $table->text('hasil_pengamatan')->nullable();
            $table->timestamps();
        });

        // Pertanyaan Esensial P2 (8 essay questions)
        Schema::create('lkm_p2_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lkm_submission_id')->constrained('lkm_submissions')->onDelete('cascade');
            $table->text('q1_ditutup_rapat')->nullable();
            $table->text('q2_pengaruh_kelembapan')->nullable();
            $table->text('q3_lokasi_penyimpanan')->nullable();
            $table->text('q4_kekuatan_lemah')->nullable();
            $table->text('q5_keberhasilan_kegagalan')->nullable();
            $table->text('q6_peran_xilem')->nullable();
            $table->text('q7_peran_epidermis')->nullable();
            $table->text('q8_aktivitas_meristem')->nullable();
            $table->timestamps();
        });

        // ============================================================
        // LKM PERTEMUAN 3 (Sintak 5)
        // ============================================================

        // Pengamatan Pertumbuhan Tunas & Daun
        Schema::create('lkm_p3_growths', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lkm_submission_id')->constrained('lkm_submissions')->onDelete('cascade');
            $table->string('parameter');
            $table->string('data_jumlah')->nullable();
            $table->text('deskripsi_kondisi')->nullable();
            $table->timestamps();
        });

        // Pengamatan Kondisi Batang Atas (Scion)
        Schema::create('lkm_p3_scions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lkm_submission_id')->constrained('lkm_submissions')->onDelete('cascade');
            $table->string('parameter');
            $table->text('kondisi_deskripsi')->nullable();
            $table->string('dokumentasi_path')->nullable();
            $table->timestamps();
        });

        // Pengamatan Kondisi Batang Bawah (Rootstock)
        Schema::create('lkm_p3_rootstocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lkm_submission_id')->constrained('lkm_submissions')->onDelete('cascade');
            $table->string('parameter');
            $table->text('kondisi_deskripsi')->nullable();
            $table->string('dokumentasi_path')->nullable();
            $table->timestamps();
        });

        // Pengamatan Kondisi Sambungan
        Schema::create('lkm_p3_connections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lkm_submission_id')->constrained('lkm_submissions')->onDelete('cascade');
            $table->text('rincian_sambungan')->nullable();
            $table->boolean('is_tumbuh_tunas')->nullable();
            $table->text('alasan')->nullable();
            $table->string('foto_sambungan')->nullable();
            $table->timestamps();
        });

        // Pertanyaan Esensial P3 (5 essay questions)
        Schema::create('lkm_p3_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lkm_submission_id')->constrained('lkm_submissions')->onDelete('cascade');
            $table->text('q1_apakah_berhasil')->nullable();
            $table->text('q2_indikator_keberhasilan')->nullable();
            $table->text('q3_tunas_baru_muncul')->nullable();
            $table->text('q4_hubungan_jaringan_pengangkut')->nullable();
            $table->text('q5_faktor_penyebab_gagal')->nullable();
            $table->timestamps();
        });

        // ============================================================
        // LKM PERTEMUAN 4 (Sintak 6)
        // ============================================================

        // Analisis Keberhasilan
        Schema::create('lkm_p4_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lkm_submission_id')->constrained('lkm_submissions')->onDelete('cascade');
            $table->string('variabel_analisis');
            $table->text('hasil_pengamatan')->nullable();
            $table->timestamps();
        });

        // Pertanyaan Analisis Mendalam (5 questions)
        Schema::create('lkm_p4_deep_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lkm_submission_id')->constrained('lkm_submissions')->onDelete('cascade');
            $table->text('q1_tujuan_grafting')->nullable();
            $table->text('q2_karakteristik_anatomi')->nullable();
            $table->text('q3_kesejajaran_kambium')->nullable();
            $table->text('q4_faktor_anatomi_inkompatibilitas')->nullable();
            $table->text('q5_proses_penyembuhan')->nullable();
            $table->timestamps();
        });

        // Penilaian Diri (Self Assessment)
        Schema::create('lkm_p4_self_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lkm_submission_id')->constrained('lkm_submissions')->onDelete('cascade');
            $table->string('aspek');
            $table->integer('skor')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        // Refleksi Essay (10 questions)
        Schema::create('lkm_p4_reflections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lkm_submission_id')->constrained('lkm_submissions')->onDelete('cascade');
            $table->text('r1_pengalaman_baru')->nullable();
            $table->text('r2_kesulitan')->nullable();
            $table->text('r3_cara_mengatasi')->nullable();
            $table->text('r4_manfaat_pjbl')->nullable();
            $table->text('r5_perasaan')->nullable();
            $table->text('r6_kesejajaran_kambium')->nullable();
            $table->text('r7_peran_kutikula')->nullable();
            $table->text('r8_perbedaan_sel_epidermis')->nullable();
            $table->text('r9_kondisi_lingkungan')->nullable();
            $table->text('r10_fungsi_sungkup')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop new P4 tables
        Schema::dropIfExists('lkm_p4_reflections');
        Schema::dropIfExists('lkm_p4_self_assessments');
        Schema::dropIfExists('lkm_p4_deep_questions');
        Schema::dropIfExists('lkm_p4_analyses');

        // Drop new P3 tables
        Schema::dropIfExists('lkm_p3_questions');
        Schema::dropIfExists('lkm_p3_connections');
        Schema::dropIfExists('lkm_p3_rootstocks');
        Schema::dropIfExists('lkm_p3_scions');
        Schema::dropIfExists('lkm_p3_growths');

        // Drop new P2 tables
        Schema::dropIfExists('lkm_p2_questions');
        Schema::dropIfExists('lkm_p2_monitorings');
        Schema::dropIfExists('lkm_p2_procedures');
        Schema::dropIfExists('lkm_p2_specs');
        Schema::dropIfExists('lkm_p2_items');

        // Drop new P1 tables
        Schema::dropIfExists('lkm_p1_schedules');
        Schema::dropIfExists('lkm_p1_procedures');
        Schema::dropIfExists('lkm_p1_items');
        Schema::dropIfExists('lkm_p1_specs');
        Schema::dropIfExists('lkm_p1_questions');

        // Recreate old tables would require separate migration; skip for rollback
    }
};
