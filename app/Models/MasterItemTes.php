<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MasterItemTes extends Model
{
    protected $table = 'master_item_tes';
    protected $fillable = ['jenis_tes_id', 'nomor', 'nama_item', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function jenisTes() { return $this->belongsTo(JenisTes::class); }
}