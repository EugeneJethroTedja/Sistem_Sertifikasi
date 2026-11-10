<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisTes extends Model
{
    protected $table = 'jenis_tes';

    protected $fillable = [
        'kegiatan_id', 'kode', 'nama', 'urutan',
        'has_rekomendasi', 'has_jpm', 'header_col1', 'header_col2',
    ];

    protected $casts = [
        'has_rekomendasi' => 'boolean',
        'has_jpm'         => 'boolean',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }

    public function masterItems(): HasMany
    {
        return $this->hasMany(MasterItemTes::class)->orderBy('nomor');
    }

    public function hasilTes(): HasMany
    {
        return $this->hasMany(HasilTes::class);
    }

    public function ringkasanTes(): HasMany
    {
        return $this->hasMany(RingkasanTes::class);
    }

    // Scope: ambil jenis tes untuk kegiatan tertentu + yang global
    public function scopeUntukKegiatan($query, $kegiatanId)
    {
        return $query->where(function ($q) use ($kegiatanId) {
            $q->whereNull('kegiatan_id')          // global (potensi, kompetensi)
              ->orWhere('kegiatan_id', $kegiatanId); // custom kegiatan ini
        })->orderBy('urutan');
    }
}