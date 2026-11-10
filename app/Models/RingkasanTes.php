<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class RingkasanTes extends Model
{
    protected $table = 'ringkasan_tes';
    protected $fillable = ['peserta_id','jenis_tes_id','rekomendasi','jumlah','jpm','kategori'];

    public function peserta()  { return $this->belongsTo(Peserta::class); }
    public function jenisTes() { return $this->belongsTo(JenisTes::class); }
}