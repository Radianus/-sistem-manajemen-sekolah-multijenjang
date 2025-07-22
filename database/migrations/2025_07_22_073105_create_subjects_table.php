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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Nama mata pelajaran, cth: "Matematika", "Bahasa Indonesia"
            $table->string('code')->nullable()->unique(); // Kode mata pelajaran, cth: "MTK", "BIN"
            $table->text('description')->nullable();
            $table->string('level')->nullable(); // Jenjang pendidikan (SD, SMP, SMA, SMK), opsional
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
