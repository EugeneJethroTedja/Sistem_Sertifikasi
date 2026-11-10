<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Peserta extends Model
{
    protected $table = 'peserta';

    protected $fillable = [
        'kegiatan_id',
        'nomor_urut',
        'nama',
        'nip',
        'jabatan',
        'level_jabatan',      // ← DITAMBAHKAN
        'pangkat',
        'golongan',
        'perangkat_daerah',
        'nomor_sertifikat',
        'tanggal_sertifikat',
        'is_active',
    ];

    protected $casts = [
        'tanggal_sertifikat' => 'date',
        'is_active'          => 'boolean',
    ];

    protected static function booted(): void
    {
        // Generate nomor sertifikat HANYA saat CREATE baru,
        // DAN hanya jika nomor_sertifikat belum diset (agar import tidak overwrite)
        static::created(function (Peserta $peserta) {
            if (empty($peserta->nomor_sertifikat)) {
                $peserta->generateNomorSertifikat();
            }
        });
    }

    public function kegiatan(): BelongsTo
    {
        return $this->belongsTo(Kegiatan::class);
    }

    public function hasilTes(): HasMany
    {
        return $this->hasMany(HasilTes::class);
    }

    public function ringkasanTes(): HasMany
    {
        return $this->hasMany(RingkasanTes::class);
    }

    public function generateNomorSertifikat(): void
    {
        $k = $this->kegiatan;
        $nomor = "800.1.14.2/{$this->nomor_urut}/{$k->bulan_kegiatan}/{$k->tahun_kegiatan}";
        $this->updateQuietly([
            'nomor_sertifikat'   => $nomor,
            'tanggal_sertifikat' => $k->tanggal_selesai,
        ]);
    }

    public function getHasilPerJenisTes(): array
    {
        $jenisTesAll = JenisTes::orderBy('urutan')->get();
        $hasil = [];
        foreach ($jenisTesAll as $jt) {
            $items     = HasilTes::where('peserta_id', $this->id)->where('jenis_tes_id', $jt->id)->orderBy('nomor')->get();
            $ringkasan = RingkasanTes::where('peserta_id', $this->id)->where('jenis_tes_id', $jt->id)->first();
            if ($items->isNotEmpty()) {
                $hasil[] = [
                    'jenis_tes' => $jt,
                    'items'     => $items,
                    'ringkasan' => $ringkasan,
                ];
            }
        }
        return $hasil;
    }
}