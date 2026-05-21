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
        Schema::create('lkm_p2_specs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lkm_submission_id')->constrained('lkm_submissions')->onDelete('cascade');
            $table->string('batang_atas_rootstock')->nullable(); // Input Batang atas (rootstock) [cite: 725]
            $table->string('batang_bawah_scion')->nullable();     // Input Batang Bawah (scion) [cite: 725]
            $table->string('usia_batang_atas')->nullable();      // Input Usia Batang atas (rootstock) [cite: 725]
            $table->string('usia_batang_bawah')->nullable();     // Input Usia Batang Bawah (scion) [cite: 725]
            $table->integer('jumlah_mata_tunas')->nullable();     // Input Jumlah mata tunas [cite: 725]
            $table->timestamps();
        });

        // 2. Tabel Item Alat dan Bahan (Dynamic Input / Repeater Halaman 41)
        Schema::create('lkm_p2_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lkm_submission_id')->constrained('lkm_submissions')->onDelete('cascade');
            $table->string('nama_item')->nullable(); // Isi nama alat atau bahan [cite: 722]
            $table->enum('jenis', ['alat', 'bahan'])->nullable(); // Pembeda kategori [cite: 722]
            $table->timestamps();
        });

        // 3. Tabel Prosedur Kerja Lapangan (Halaman 41-42)
        Schema::create('lkm_p2_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lkm_submission_id')->constrained('lkm_submissions')->onDelete('cascade');
            $table->integer('step_number'); // Nomor urut langkah kerja (1-10) [cite: 727, 729]
            $table->string('nama_tahap')->nullable();   // Judul tahap pengerjaan [cite: 727]
            $table->text('penjelasan')->nullable();     // Deskripsi penjelasan langkah pengerjaan [cite: 727]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lkm_p2_specs');
        Schema::dropIfExists('lkm_p2_items');
        Schema::dropIfExists('lkm_p2_steps');
    }
};
