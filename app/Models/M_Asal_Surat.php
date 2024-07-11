<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_Asal_Surat extends Model
{
    use HasFactory;

    protected $table = 'asal_surat'; // Nama tabel yang sesuai
    protected $primaryKey = 'id_asal_surat'; // Kolom primary key yang sesuai
    public $timestamps = false; 
    protected $fillable = [
        'id_asal_surat', 'asal_surat', 
    ];

    public function disposisi()
    {
        return $this->hasMany(M_Disposisi::class, 'id_asal_surat');
    }
}