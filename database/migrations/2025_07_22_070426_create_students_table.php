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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('nis')->unique(); // Nomor Induk Siswa
            $table->string('nisn')->nullable()->unique(); // Nomor Induk Siswa Nasional (opsional)
            $table->enum('gender', ['Laki-laki', 'Perempuan']);
            $table->date('date_of_birth')->nullable();
            $table->text('address')->nullable();
            $table->string('phone_number')->nullable(); // Nomor telepon orang tua/siswa

            // Foreign Key ke tabel users (untuk akun pengguna siswa)
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');

            // Foreign Key ke tabel classes (untuk kelas siswa)
            $table->foreignId('school_class_id')->nullable()->constrained('classes')->onDelete('set null'); // Menggunakan school_class_id agar lebih jelas

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
