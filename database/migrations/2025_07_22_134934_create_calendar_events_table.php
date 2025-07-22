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
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable(); // Untuk acara multi-hari
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('location')->nullable();
            $table->string('event_type'); // Cth: 'Ujian', 'Libur', 'Rapat', 'Kegiatan Sekolah'
            $table->string('target_roles')->nullable(); // Cth: 'all', 'siswa', 'guru,orang_tua' (comma-separated)
            $table->foreignId('created_by_user_id')->constrained('users')->onDelete('restrict'); // Siapa yang membuat acara

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendar_events');
    }
};
