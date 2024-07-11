<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    use AuthenticatesUsers;

    public function showLaporan()
    {
        return view('laporan');
    }
}
