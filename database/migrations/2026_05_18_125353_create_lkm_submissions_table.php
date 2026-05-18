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
        Schema::create('lkm_submissions', function (Blueprint $table) {
            $table->id();
            // Menggunakan relasi langsung ke mahasiswa_id sesuai keputusan arsitektur
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->onDelete('cascade');
            $table->foreignId('lkm_setting_id')->constrained('lkm_settings')->onDelete('cascade');
            $table->integer('pertemuan'); // Angka 1, 2, 3, atau 4
            $table->enum('status', ['draft', 'submitted'])->default('draft');
            $table->text('catatan_dosen')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
            
            // Mencegah duplikasi submit mahasiswa pada pertemuan yang sama
            $table->unique(['mahasiswa_id', 'lkm_setting_id', 'pertemuan'], 'unique_student_submission');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lkm_submissions');
    }
};
