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
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Contoh: "X IPA 1", "VII-B", "Kelas 5 SD"
            $table->string('level')->nullable(); // Contoh: "SD", "SMP", "SMA", "SMK"
            $table->string('grade_level')->nullable(); // Contoh: "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12"
            $table->string('academic_year')->nullable(); // Contoh: "2024/2025"
            $table->foreignId('homeroom_teacher_id')->nullable()->constrained('users')->onDelete('set null'); // Wali kelas, opsional
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
