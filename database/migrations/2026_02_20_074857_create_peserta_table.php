<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peserta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('kegiatan')->cascadeOnDelete();
            $table->integer('nomor_urut');
            $table->string('nama');
            $table->string('nip', 30);
            $table->string('jabatan')->nullable();
            $table->string('level_jabatan', 50)->nullable(); // ← BARU: Level jabatan (misal: 1, 2, 3)
            $table->string('pangkat', 100)->nullable();
            $table->string('golongan', 20)->nullable();
            $table->string('perangkat_daerah')->nullable();
            $table->string('nomor_sertifikat', 100)->nullable();
            $table->date('tanggal_sertifikat')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['kegiatan_id', 'nip']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peserta');
    }
};