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
        Schema::create('lkm_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->tinyInteger('pertemuan')->nullable()->unique();
            $table->string('title');
            $table->text('deskripsi')->nullable();
            $table->timestamp('open_at')->nullable();
            $table->timestamp('deadline_at')->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('allow_late_submit')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lkm_settings');
    }
};
