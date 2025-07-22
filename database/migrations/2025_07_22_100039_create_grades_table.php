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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('teaching_assignment_id')->constrained('teaching_assignments')->onDelete('cascade');
            $table->decimal('score', 5, 2);

            // --- PERBAIKI PANJANG KOLOM INI ---
            $table->string('grade_type', 50); // Misalnya 50 karakter sudah cukup untuk "Ulangan Harian"
            $table->string('semester', 10);   // Misalnya 10 karakter sudah cukup untuk "Ganjil" atau "Genap"
            $table->string('academic_year', 10); // Misalnya 10 karakter sudah cukup untuk "2024/2025"
            // ----------------------------------

            $table->foreignId('graded_by_teacher_id')->constrained('users')->onDelete('restrict');
            $table->text('notes')->nullable();

            // Unique constraint (ini yang menyebabkan error jika panjang kolomnya terlalu besar)
            $table->unique(['student_id', 'teaching_assignment_id', 'grade_type', 'semester', 'academic_year'], 'grade_unique_per_assignment_type_semester_year');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
