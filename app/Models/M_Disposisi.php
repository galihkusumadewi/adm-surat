<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_Disposisi extends Model
{
    use HasFactory;

    protected $table = 'disposisi';
    protected $primaryKey = 'id_disposisi';
    public $timestamps = false; 

    protected $fillable = [
        'no_disposisi',
        'tanggal',
        'sifat_disposisi',
        'jenis_disposisi',
        'perihal',
        'instruksi',
        'keterangan',
        'id_suratmasuk',
        'id_asal_surat',
    ];

    public function asalSurat()
    {
        return $this->belongsTo(M_Asal_Surat::class, 'id_asal_surat', 'id_asal_surat');
    }

    public function suratMasuk()
    {
        return $this->belongsTo(M_Surat_Masuk::class, 'id_suratmasuk', 'id_suratmasuk');
    }

}