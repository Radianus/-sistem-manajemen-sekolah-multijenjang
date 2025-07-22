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
        Schema::create('parent_student', function (Blueprint $table) {
            $table->foreignId('parent_user_id')->constrained('users')->onDelete('cascade'); // ID user yang berperan sebagai orang tua
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade'); // ID siswa

            $table->primary(['parent_user_id', 'student_id']); // Kombinasi ini harus unik
            $table->timestamps(); // Opsional, tapi bagus untuk audit
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parent_student');
    }
};
