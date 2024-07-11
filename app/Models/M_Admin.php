<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_Admin extends Model
{
    use HasFactory;

    protected $table = 'admins'; // Nama tabel di database

    protected $fillable = [
        'nama',
        'alamat',
        'no_hp',
        'username',
        'password',
    ];
}