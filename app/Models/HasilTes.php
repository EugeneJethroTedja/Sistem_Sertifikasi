<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class HasilTes extends Model
{
    protected $table = 'hasil_tes';
    protected $fillable = ['peserta_id','jenis_tes_id','nomor','nama_item','nilai_text','nilai_angka'];

    public function peserta()  { return $this->belongsTo(Peserta::class); }
    public function jenisTes() { return $this->belongsTo(JenisTes::class); }
}