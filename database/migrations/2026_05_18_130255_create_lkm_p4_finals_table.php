<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lkm_p4_finals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lkm_submission_id')->constrained('lkm_submissions')->onDelete('cascade');
            $table->boolean('is_berhasil'); // Berhasil atau Tidak Berhasil [cite: 759]
            $table->integer('jumlah_tunas_berhasil_tumbuh')->default(0); // Jumlah tunas yang berhasil tumbuh [cite: 759]
            $table->integer('jumlah_daun')->default(0); // Jumlah daun [cite: 759]
            $table->string('ukuran_daun')->nullable();   // Ukuran daun [cite: 759]
            $table->string('warna_daun')->nullable();    // Warna daun [cite: 759]
            $table->string('warna_batang_atas')->nullable();  // Warna batang atas [cite: 759]
            $table->string('warna_batang_bawah')->nullable(); // Warna batang bawah [cite: 759]
            $table->text('deskripsi_kondisi_tanaman')->nullable();        // Kondisi umum tanaman [cite: 759]
            $table->string('foto_final_grafting')->nullable();
            $table->timestamps();
        });

        Schema::create('lkm_p4_reflections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lkm_submission_id')->constrained('lkm_submissions')->onDelete('cascade');
            $table->text('r1_tujuan_grafting')->nullable();                  // Q1: Tujuan dilakukan grafting [cite: 764]
            $table->text('r2_karakteristik_anatomi_batang')->nullable();      // Q2: Karakteristik anatomi batang mempengaruhi [cite: 766]
            $table->text('r3_kesejajaran_kambium')->nullable();              // Q3: Mengapa kesejajaran kambium sangat penting [cite: 768]
            $table->text('r4_peran_hormon_auksin')->nullable();              // Q4: Peran hormon tanaman auksin [cite: 769]
            $table->text('r5_faktor_anatomi_ketidakcocokan')->nullable();    // Q5: Faktor anatomi menyebabkan ketidakcocokan [cite: 771]
            $table->text('r6_proses_tumbuhan_pulih')->nullable();            // Q6: Bagaimana tumbuhan dapat pulih [cite: 774]
            $table->text('r7_peran_kutikula')->nullable();                  // Q7: Peran kutikula dalam mencegah kehilangan air [cite: 775]
            $table->text('r8_anatomi_daun_mempengaruhi')->nullable();        // Q8: Bagaimana anatomi daun mempengaruhi [cite: 776]
            $table->text('r9_struktur_sel_epidermis')->nullable();           // Q9: Pengaruh perbedaan struktur sel epidermis [cite: 777]
            $table->text('r10_kondisi_lingkungan')->nullable();              // Q10: Pengaruh kondisi lingkungan suhu/kelembapan [cite: 779]
            $table->text('r11_fungsi_sungkup')->nullable();                  // Q11: Fungsi sungkup pada proses grafting [cite: 781]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lkm_p4_finals');
        Schema::dropIfExists('lkm_p4_reflections');
    }
};
