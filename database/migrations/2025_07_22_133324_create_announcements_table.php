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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->timestamp('published_at')->nullable(); // Tanggal pengumuman mulai tampil
            $table->timestamp('expires_at')->nullable(); // Tanggal pengumuman berakhir tampil
            $table->foreignId('created_by_user_id')->constrained('users')->onDelete('restrict'); // Siapa yang membuat pengumuman
            $table->string('target_roles')->nullable(); // Target audiens, cth: 'all', 'guru', 'siswa,orang_tua' (comma-separated)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
