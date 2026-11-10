<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kegiatan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kegiatan');
            $table->string('jenis_kegiatan')->nullable();
            $table->string('penyelenggara')->nullable();       // ← BARU
            $table->string('tempat')->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->string('bulan_kegiatan')->nullable();
            $table->string('tahun_kegiatan')->nullable();
            $table->integer('nomor_urut_start')->default(1);   // ← BARU
            $table->string('jabatan_kepala')->nullable();
            $table->string('nama_kepala')->nullable();
            $table->string('pangkat_kepala')->nullable();
            $table->string('nip_kepala')->nullable();
            $table->longText('ttd_base64')->nullable();  // ← sudah include di sini
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kegiatan');
    }
};