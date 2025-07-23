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
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('assignments')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->timestamp('submission_date'); // Tanggal pengumpulan
            $table->string('file_path')->nullable(); // Path file yang diupload siswa
            $table->text('content')->nullable(); // Jawaban teks langsung di form
            $table->decimal('score', 5, 2)->nullable(); // Nilai yang diberikan
            $table->text('feedback')->nullable(); // Feedback dari guru
            $table->foreignId('graded_by_user_id')->nullable()->constrained('users')->onDelete('set null'); // Guru yang menilai

            $table->unique(['assignment_id', 'student_id']); // Satu siswa hanya bisa 1 kali mengumpulkan per tugas
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};