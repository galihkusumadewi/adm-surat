<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_Pengguna extends Model
{
    use HasFactory;

    protected $table = 'pengguna'; // Nama tabel yang sesuai
    protected $primaryKey = 'id_pengguna'; // Kolom primary key yang sesuai
    protected $foreignKey = 'id_jabatan'; // Kolom foreign key yang sesuai
    public $timestamps = false; // Jika tidak ada kolom created_at dan updated_at

    protected $fillable = [
        'id_pengguna', 'id_pengguna',
        'nama', 'nama',
        'NIK', 'NIK',
        'alamat', 'alamat',
        'no_hp', 'no_hp',
        'id_jabatan', 'id_jabatan',
    ];

    public function jabatan()
    {
        return $this->belongsTo(M_Jabatan::class, 'id_jabatan', 'id_jabatan');
    }
}