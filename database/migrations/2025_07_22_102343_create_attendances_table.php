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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('teaching_assignment_id')->constrained('teaching_assignments')->onDelete('cascade'); // Absensi terkait penugasan mengajar (kelas-mapel-guru)
            $table->date('date'); // Tanggal absensi
            $table->enum('status', ['Hadir', 'Izin', 'Sakit', 'Alpha']);
            $table->text('notes')->nullable();
            $table->foreignId('recorded_by_teacher_id')->constrained('users')->onDelete('restrict'); // Guru yang mencatat absensi

            // Setiap siswa hanya punya satu status absensi per penugasan mengajar per tanggal
            $table->unique(['student_id', 'teaching_assignment_id', 'date'], 'attendance_unique_per_student_assignment_date');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
