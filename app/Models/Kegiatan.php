<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Kegiatan extends Model
{
    protected $table = 'kegiatan';

    protected $fillable = [
        'nama_kegiatan', 'jenis_kegiatan', 'penyelenggara', 'tempat',
        'tanggal_mulai', 'tanggal_selesai', 'nomor_urut_start',
        'tahun_kegiatan', 'bulan_kegiatan',
        'nama_kepala', 'jabatan_kepala', 'pangkat_kepala', 'nip_kepala',
        'is_active',
        'ttd_base64', // 👈 TAMBAHKAN INI
    ];

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
        'is_active'       => 'boolean',
    ];

    public function peserta(): HasMany
    {
        return $this->hasMany(Peserta::class);
    }

    public function getNamaBulanAttribute(): string
    {
        $bulan = [
            1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',
            5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',
            9=>'September',10=>'Oktober',11=>'November',12=>'Desember',
        ];
        return $bulan[$this->bulan_kegiatan] ?? '';
    }

    public function getTanggalRangeAttribute(): string
    {
        $mulai   = Carbon::parse($this->tanggal_mulai)->isoFormat('D MMMM Y');
        $selesai = Carbon::parse($this->tanggal_selesai)->isoFormat('D MMMM Y');
        return "{$mulai} s.d. {$selesai}";
    }

    public function getNextNomorUrut(): int
    {
        // Query langsung ke DB, tidak pakai cache relationship
        $max = Peserta::where('kegiatan_id', $this->id)->max('nomor_urut');
        return ($max ?? ($this->nomor_urut_start - 1)) + 1;
    }
}