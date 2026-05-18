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
        Schema::create('lkm_p1_observations', function (Blueprint $table) { 
            $table->id();
            $table->foreignId('lkm_submission_id')->constrained('lkm_submissions')->onDelete('cascade');
            $table->string('nama_tanaman'); // Mangga, Alpukat, Durian [cite: 687]
            $table->string('organ');        // Batang, Akar, Daun, Bunga, Buah, Biji [cite: 687, 688, 691, 693, 695, 699]
            $table->text('morfologis')->nullable(); // Kolom input morfologis [cite: 687]
            $table->text('anatomis')->nullable();   // Kolom input anatomis [cite: 687]
            $table->timestamps();
        });

        Schema::create('lkm_p1_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lkm_submission_id')->constrained('lkm_submissions')->onDelete('cascade');
            $table->text('q1_jenis_tumbuhan_cocok'); // Jenis tumbuhan cocok untuk grafting [cite: 704]
            $table->text('q2_jaringan_terlibat');     // Jaringan tumbuhan yang terlibat [cite: 708]
            $table->text('q3_peran_kambium');          // Peran struktur jaringan kambium [cite: 711]
            $table->text('q4_pemilihan_batang_bawah'); // Pengaruh pemilihan batang bawah [cite: 713]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lkm_p1_observations');
        Schema::dropIfExists('lkm_p1_questions');
    }
};
