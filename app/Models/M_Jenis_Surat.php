<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_Jenis_Surat extends Model
{
    use HasFactory;

    protected $table = 'jenis_surat'; // Nama tabel yang sesuai
    protected $primaryKey = 'id_jenis'; // Kolom primary key yang sesuai
    public $timestamps = false; // Jika tidak ada kolom created_at dan updated_at

    protected $fillable = [
        'id_jenis', 'jenis_surat', // Kolom lain yang relevan
    ];
}