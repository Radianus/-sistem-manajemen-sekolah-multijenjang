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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('teaching_assignment_id')->constrained('teaching_assignments')->onDelete('cascade'); // Tugas untuk mata pelajaran/kelas/guru tertentu
            $table->timestamp('due_date'); // Batas waktu pengumpulan
            $table->decimal('max_score', 5, 2)->nullable(); // Skor maksimum jika tugas dinilai
            $table->string('file_path')->nullable(); // Path file materi/template tugas (misal: PDF, DOC)
            $table->foreignId('assigned_by_user_id')->constrained('users')->onDelete('restrict'); // Guru/Admin yang memberikan tugas
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};