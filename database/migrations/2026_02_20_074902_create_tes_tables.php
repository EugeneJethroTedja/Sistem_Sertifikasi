<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ── Jenis Tes ─────────────────────────────────────────────
        // kegiatan_id nullable → NULL = master global, diisi = khusus kegiatan itu
        Schema::create('jenis_tes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')
                  ->nullable()
                  ->constrained('kegiatan')
                  ->cascadeOnDelete();           // ← BARU: per-kegiatan
            $table->string('kode', 50);
            $table->string('nama');
            $table->integer('urutan')->default(0);
            $table->boolean('has_rekomendasi')->default(false);
            $table->boolean('has_jpm')->default(false);
            $table->string('header_col1', 100)->default('Aspek');
            $table->string('header_col2', 100)->default('Skala');
            $table->timestamps();

            // kode unik per kegiatan (NULL = master global)
            $table->unique(['kegiatan_id', 'kode']);
        });

        // ── Master Item per Jenis Tes ─────────────────────────────
        Schema::create('master_item_tes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_tes_id')->constrained('jenis_tes')->cascadeOnDelete();
            $table->integer('nomor');
            $table->string('nama_item')->default('');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ── Hasil Tes per Peserta ─────────────────────────────────
        Schema::create('hasil_tes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_id')->constrained('peserta')->cascadeOnDelete();
            $table->foreignId('jenis_tes_id')->constrained('jenis_tes');
            $table->integer('nomor');
            $table->string('nama_item');
            $table->string('nilai_text', 50)->nullable();
            $table->decimal('nilai_angka', 5, 2)->nullable();
            $table->timestamps();
        });

        // ── Ringkasan per Peserta per Jenis Tes ──────────────────
        Schema::create('ringkasan_tes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_id')->constrained('peserta')->cascadeOnDelete();
            $table->foreignId('jenis_tes_id')->constrained('jenis_tes');
            $table->string('rekomendasi')->nullable();
            $table->decimal('jumlah', 8, 2)->nullable();
            $table->decimal('standar', 8, 2)->nullable();  // ← BARU: nilai standar dari Excel
            $table->decimal('jpm', 5, 2)->nullable();
            $table->string('kategori', 100)->nullable();
            $table->timestamps();

            $table->unique(['peserta_id', 'jenis_tes_id']);
        });

        // ── Seed Data Jenis Tes Master (kegiatan_id = NULL) ───────
        DB::table('jenis_tes')->insert([
            [
                'kegiatan_id'     => null,
                'kode'            => 'potensi',
                'nama'            => 'Hasil Potensi',
                'urutan'          => 1,
                'has_rekomendasi' => 1,
                'has_jpm'         => 0,
                'header_col1'     => 'Aspek',
                'header_col2'     => 'Skala',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'kegiatan_id'     => null,
                'kode'            => 'kompetensi',
                'nama'            => 'Kompetensi Manajerial dan Sosial Kultural',
                'urutan'          => 2,
                'has_rekomendasi' => 0,
                'has_jpm'         => 1,
                'header_col1'     => 'Unsur',
                'header_col2'     => 'Nilai',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
        ]);

        // ── Seed Master Item Potensi ──────────────────────────────
        $potensiId    = DB::table('jenis_tes')->where('kode', 'potensi')->whereNull('kegiatan_id')->value('id');
        $kompetensiId = DB::table('jenis_tes')->where('kode', 'kompetensi')->whereNull('kegiatan_id')->value('id');

        $potensiItems = [];
        $namaPotensi  = ['Kemampuan Intelektual', '', '', '', '', '', '', ''];
        for ($i = 1; $i <= 8; $i++) {
            $potensiItems[] = [
                'jenis_tes_id' => $potensiId,
                'nomor'        => $i,
                'nama_item'    => $namaPotensi[$i - 1],
                'created_at'   => now(),
                'updated_at'   => now(),
            ];
        }
        DB::table('master_item_tes')->insert($potensiItems);

        // ── Seed Master Item Kompetensi ───────────────────────────
        $kompetensiItems = [];
        $namaKompetensi  = ['Integritas', 'Kerjasama', 'Komunikasi', '', '', '', '', '', ''];
        for ($i = 1; $i <= 9; $i++) {
            $kompetensiItems[] = [
                'jenis_tes_id' => $kompetensiId,
                'nomor'        => $i,
                'nama_item'    => $namaKompetensi[$i - 1],
                'created_at'   => now(),
                'updated_at'   => now(),
            ];
        }
        DB::table('master_item_tes')->insert($kompetensiItems);
    }

    public function down(): void
    {
        Schema::dropIfExists('ringkasan_tes');
        Schema::dropIfExists('hasil_tes');
        Schema::dropIfExists('master_item_tes');
        Schema::dropIfExists('jenis_tes');
    }
};