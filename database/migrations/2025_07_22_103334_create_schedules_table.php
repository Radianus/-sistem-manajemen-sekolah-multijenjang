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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('teaching_assignment_id')->constrained('teaching_assignments')->onDelete('cascade'); // Link ke mapel dan guru
            $table->enum('day_of_week', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']);
            $table->time('start_time');
            $table->time('end_time');
            $table->string('room_number')->nullable();
            $table->string('academic_year'); // Tahun ajaran untuk jadwal ini

            // Unique constraint: Satu kelas tidak bisa punya dua jadwal di waktu yang sama pada hari yang sama
            $table->unique(['school_class_id', 'day_of_week', 'start_time', 'end_time', 'academic_year'], 'class_daily_time_unique');

            // Unique constraint: Satu guru tidak bisa mengajar dua tempat di waktu yang sama (lebih kompleks, bisa ditambahkan validasi custom)
            // Untuk sementara, fokus pada kelas.

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
