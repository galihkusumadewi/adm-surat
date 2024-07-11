<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_Surat_Keluar extends Model
{
    use HasFactory;

    protected $table = 'surat_keluar';
    protected $primaryKey = 'id_suratkeluar';
    public $timestamps = false; 

    protected $fillable = [
        'no_surat',
        'tgl_surat',
        'lampiran',
        'file_surat',
        'perihal',
        'keterangan',
        'id_asal_surat',
        'id_jenis',
        'id_sifat',
        'asal',
    ];

    public function asalSurat()
    {
        return $this->belongsTo(M_Asal_Surat::class, 'id_asal_surat', 'id_asal_surat');
    }
    public function sifatSurat()
    {
        return $this->belongsTo(M_Sifat_Surat::class, 'id_sifat', 'id_sifat');
    }

    public function jenisSurat()
    {
        return $this->belongsTo(M_Jenis_Surat::class, 'id_jenis', 'id_jenis');
    }
}