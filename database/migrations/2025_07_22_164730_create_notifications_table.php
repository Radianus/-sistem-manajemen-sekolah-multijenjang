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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Penerima notifikasi
            $table->string('type'); // Tipe notifikasi (e.g., 'new_announcement', 'new_grade', 'new_message')
            $table->string('title'); // Judul singkat notifikasi
            $table->text('message'); // Isi pesan notifikasi
            $table->string('link')->nullable(); // URL yang akan dituju saat notifikasi diklik
            $table->timestamp('read_at')->nullable(); // Kapan notifikasi dibaca
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
