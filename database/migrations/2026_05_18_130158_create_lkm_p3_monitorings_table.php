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
        Schema::create('lkm_p3_monitorings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lkm_submission_id')->constrained('lkm_submissions')->onDelete('cascade');
            
            // Pemantauan Tunas/Daun (Halaman 43)
            $table->integer('jumlah_tunas_daun'); // Jumlah tunas / daun [cite: 739]
            $table->text('deskripsi_tunas_daun'); // Deskripsi kondisi tunas / daun [cite: 739]
            
            // Pemantauan Batang Atas (Halaman 43)
            $table->string('kondisi_batang_atas'); // Kondisi Batang atas (scion) [cite: 741]
            $table->text('deskripsi_batang_atas'); // Deskripsi kondisi Batang atas (scion) [cite: 741]
            
            // Pemantauan Batang Bawah (Halaman 43)
            $table->string('kondisi_batang_bawah'); // Kondisi Batang bawah (rootstock) [cite: 743]
            $table->text('deskripsi_batang_bawah'); // Deskripsi kondisi Batang bawah [cite: 743]
            
            // Validasi Pertumbuhan Tunas Batang Bawah (Halaman 44)
            $table->boolean('is_batang_bawah_tumbuh_tunas'); // Apakah batang bawah tumbuh tunas? (Ya/Tidak) [cite: 745, 747]
            $table->text('alasan_tumbuh_tunas'); // Alasan penyebab jika tumbuh atau tidak [cite: 749]
            
            // Kondisi Sambungan (Halaman 44)
            $table->text('kondisi_sambungan_teramati'); // Uraian kondisi pada sambungan batang [cite: 751]
            
            // Upload Foto Progres Lapangan Fisik
            $table->string('foto_progres_grafting')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lkm_p3_monitorings');
    }
};
