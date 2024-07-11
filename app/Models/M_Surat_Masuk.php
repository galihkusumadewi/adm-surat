<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_Surat_Masuk extends Model
{
    use HasFactory;

    protected $table = 'surat_masuk';
    protected $primaryKey = 'id_suratmasuk';
    public $timestamps = false; 

    protected $fillable = [
        'no_surat',
        'tanggal',
        'lampiran',
        'tujuan',
        'perihal',
        'keterangan',
        'file_surat',
        'id_jenis',
        'id_jabatan',
        'id_asal_surat',
        'id_sifat',
        'id_pengguna',
    ];

    public function asalSurat()
    {
        return $this->belongsTo(M_Asal_Surat::class, 'id_asal_surat', 'id_asal_surat');
    }

    public function sifatSurat()
    {
        return $this->belongsTo(M_Sifat_Surat::class, 'id_sifat', 'id_sifat');
    }

    public function jabatan()
    {
        return $this->belongsTo(M_Jabatan::class, 'id_jabatan', 'id_jabatan');
    }

    public function pengguna()
    {
        return $this->belongsTo(M_Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    public function jenisSurat()
    {
        return $this->belongsTo(M_Jenis_Surat::class, 'id_jenis', 'id_jenis');
    }
}
