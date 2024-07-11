<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_Jabatan extends Model
{
    use HasFactory;

    protected $table = 'jabatan'; 
    protected $primaryKey = 'id_jabatan'; 
    public $timestamps = false; 

    protected $fillable = [
        'id_jabatan', 'jabatan', 
    ];
}