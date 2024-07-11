<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\JabatanController;
use App\Http\Controllers\Admin\AsalSuratController;
use App\Http\Controllers\Admin\DisposisiController;
use App\Http\Controllers\Admin\JenisSuratController;
use App\Http\Controllers\Admin\SifatSuratController;
use App\Http\Controllers\Admin\SuratMasukController;
use App\Http\Controllers\Admin\SuratKeluarController;
use App\Http\Controllers\Admin\OlahDataAdminController;
use App\Http\Controllers\Admin\OlahDataPenggunaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
| These routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


Auth::routes();

//ROUTE UNTUK ADMIN
Route::group(['prefix' => 'dashboard/admin', 'middleware' => ['auth', 'check_jabatan:1']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('admin.home');

    Route::group(['prefix' => 'profile'], function () {
        Route::get('/', [HomeController::class, 'profile'])->name('admin.profile');
        Route::post('update', [HomeController::class, 'updateprofile'])->name('admin.profile.update');
    });

    Route::prefix('akun')
        ->as('admin.akun.')
        ->group(function () {
            Route::get('/', [AkunController::class, 'index'])->name('index');
            Route::post('showdata', [AkunController::class, 'dataTable'])->name('dataTable');
            Route::match(['get', 'post'], 'tambah', [AkunController::class, 'tambahAkun'])->name('add');
            Route::match(['get', 'post'], '{id}/ubah', [AkunController::class, 'ubahAkun'])->name('edit');
            Route::delete('{id}/hapus', [AkunController::class, 'hapusAkun'])->name('delete');
        });

    Route::prefix('jenis_surat')
        ->as('admin.jenis.')
        ->group(function () {
            Route::get('/', [JenisSuratController::class, 'admin_getJenisSurat'])->name('jenis_surat');
            Route::post('tambah', [JenisSuratController::class, 'tambahJenis'])->name('add');
            Route::delete('hapus/{id_jenis}', [JenisSuratController::class, 'hapusJenis'])->name('delete');
            Route::put('update/{id_jenis}', [JenisSuratController::class, 'updateJenis'])->name('update');
        });

    Route::prefix('sifat_surat')
        ->as('admin.sifat.')
        ->group(function () {
            Route::get('/', [SifatSuratController::class, 'admin_getSifatSurat'])->name('sifat_surat');
            Route::post('tambah', [SifatSuratController::class, 'tambahSifat'])->name('add');
            Route::delete('hapus/{id_sifat}', [SifatSuratController::class, 'hapusSifat'])->name('delete');
            Route::put('update/{id_sifat}', [SifatSuratController::class, 'updateSifat'])->name('update');
        });

    Route::prefix('jabatan')
        ->as('admin.jabatan.')
        ->group(function () {
            Route::get('/', [JabatanController::class, 'admin_getJabatan'])->name('jabatan');
            Route::post('tambah', [JabatanController::class, 'tambahJabatan'])->name('add');
            Route::delete('hapus/{id_jabatan}', [JabatanController::class, 'hapusJabatan'])->name('delete');
            Route::put('update/{id_jabatan}', [JabatanController::class, 'updateJabatan'])->name('update');
        });

    Route::prefix('olah_data_pengguna')
        ->as('admin.pengguna.')
        ->group(function () {
            Route::get('/', [OlahDataPenggunaController::class, 'admin_getPengguna'])->name('pengguna');
            Route::post('tambah', [OlahDataPenggunaController::class, 'tambahPengguna'])->name('add');
            Route::delete('hapus/{id_pengguna}', [OlahDataPenggunaController::class, 'hapusPengguna'])->name('delete');
            Route::put('update/{id_pengguna}', [OlahDataPenggunaController::class, 'updatePengguna'])->name('update');
            Route::get('detail/{id_pengguna}', [OlahDataPenggunaController::class, 'showDetail'])->name('detail');
        });

    Route::prefix('asal_surat')
        ->as('admin.asal.')
        ->group(function () {
            Route::get('/', [AsalSuratController::class, 'admin_getAsalSurat'])->name('asal_surat');
            Route::post('tambah', [AsalSuratController::class, 'tambahAsal'])->name('add');
            Route::delete('hapus/{id_asal_surat}', [AsalSuratController::class, 'hapusAsal'])->name('delete');
            Route::put('update/{id_asal_surat}', [AsalSuratController::class, 'updateAsal'])->name('update');
        });

        Route::prefix('surat_masuk')
            ->as('admin.masuk.')
            ->group(function () {
                Route::get('/', [SuratMasukController::class, 'admin_getSuratMasuk'])->name('surat_masuk');
                Route::get('tambah', [SuratMasukController::class, 'showTambahSuratMasuk'])->name('add');
                Route::post('tambah_surat_masuk', [SuratMasukController::class, 'tambahSuratMasuk'])->name('tambah');
                Route::delete('hapus/{id_suratmasuk}', [SuratMasukController::class, 'hapusSuratMasuk'])->name('delete');
                Route::put('update/{id_suratmasuk}', [SuratMasukController::class, 'updateSuratMasuk'])->name('update');
                Route::get('edit/{id_suratmasuk}', [SuratMasukController::class, 'showEditSuratMasuk'])->name('edit');
                Route::get('detail/{id_suratmasuk}', [SuratMasukController::class, 'showDetail'])->name('detail');
            });

    

    Route::prefix('surat_keluar')
        ->as('admin.keluar.')
        ->group(function () {
            Route::get('/', [SuratKeluarController::class, 'admin_getSuratKeluar'])->name('surat_keluar');
            Route::get('tambah', [SuratKeluarController::class, 'showtambahSuratKeluar'])->name('add');
            Route::post('tambah_surat_keluar', [SuratKeluarController::class, 'tambahSuratKeluar'])->name('tambah');
            Route::delete('hapus/{id_suratkeluar}', [SuratKeluarController::class, 'hapusSuratKeluar'])->name('delete');
            Route::put('update/{id_suratkeluar}', [SuratKeluarController::class, 'updateSuratKeluar'])->name('update');
            Route::get('edit/{id_suratkeluar}', [SuratKeluarController::class, 'showEditSuratKeluar'])->name('edit');
            Route::get('detail/{id_suratkeluar}', [SuratKeluarController::class, 'showDetail'])->name('detail');
        });

    Route::prefix('disposisi')
        ->as('admin.disposisi.')
        ->group(function () {
            Route::get('/', [DisposisiController::class, 'admin_getDisposisi'])->name('disposisi');
            Route::get('tambah/{id_suratmasuk}', [DisposisiController::class, 'showTambahDisposisi'])->name('tambah_disposisi');
            Route::post('tambah/{id_suratmasuk}', [DisposisiController::class, 'tambahDisposisi'])->name('add');
            Route::delete('hapus/{id_disposisi}', [DisposisiController::class, 'hapusDisposisi'])->name('delete');
            Route::put('update/{id_disposisi}', [DisposisiController::class, 'updateDisposisi'])->name('update');
            Route::get('edit/{id_disposisi}', [DisposisiController::class, 'showEditDisposisi'])->name('edit');
            Route::get('detail/{id_disposisi}', [DisposisiController::class, 'showDetail'])->name('detail');
        });
    

    Route::prefix('olah_data_admin')
        ->as('admin.data_admin.')
        ->group(function () {
            Route::get('/', [OlahDataAdminController::class, 'getAdmin'])->name('data_admin');
            Route::post('tambah', [OlahDataAdminController::class, 'tambahAdmin'])->name('add');
            Route::delete('hapus/{id}', [OlahDataAdminController::class, 'hapusAdmin'])->name('delete');
            Route::put('update/{id}', [OlahDataAdminController::class, 'updateAdmin'])->name('update');
            Route::get('detail/{id}', [OlahDataAdminController::class, 'showDetail'])->name('detail');
        });  

    // Logout Route
    Route::post('logout', [HomeController::class, 'logout'])->name('admin.logout');
});

// GROUP ROUTES UNTUK KABAG
Route::group(['prefix' => 'dashboard/kabag', 'middleware' => ['auth', 'check.jabatan:2']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('kabag.home');

    Route::group(['prefix' => 'profile'], function () {
        Route::get('/', [HomeController::class, 'profile'])->name('kabag.profile');
        Route::post('update', [HomeController::class, 'updateprofile'])->name('kabag.profile.update');
    });

    Route::prefix('surat_masuk')
        ->as('kabag.masuk.')
        ->group(function () {
            Route::get('/', [SuratMasukController::class, 'kabag_getSuratMasuk'])->name('surat_masuk');
            Route::get('detail/{id_suratmasuk}', [SuratMasukController::class, 'showDetail'])->name('detail');
        });

    Route::prefix('surat_keluar')
        ->as('kabag.keluar.')
        ->group(function () {
            Route::get('/', [SuratKeluarController::class, 'kabag_getSuratKeluar'])->name('surat_keluar');
            Route::get('detail/{id_suratkeluar}', [SuratKeluarController::class, 'showDetail'])->name('detail');
        });

    Route::prefix('disposisi')
        ->as('kabag.disposisi.')
        ->group(function () {
            Route::get('/', [DisposisiController::class, 'kabag_getDisposisi'])->name('disposisi');
            Route::get('detail/{id_disposisi}', [DisposisiController::class, 'showDetail'])->name('detail');
        });
        

    // Logout Route
    Route::post('logout', [HomeController::class, 'logout'])->name('kabag.logout');
});

// GROUP ROUTES UNTUK KEPALA DINAS
Route::group(['prefix' => 'dashboard/kadin', 'middleware' => ['auth', 'check.jabatan:3']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('kadin.home');

    Route::group(['prefix' => 'profile'], function () {
        Route::get('/', [HomeController::class, 'profile'])->name('kadin.profile');
        Route::post('update', [HomeController::class, 'updateprofile'])->name('kadin.profile.update');
    });

    Route::prefix('olah_data_pengguna')
    ->as('kadin.pengguna.')
    ->group(function () {
        Route::get('/', [OlahDataPenggunaController::class, 'kadin_getPengguna'])->name('pengguna');
        Route::get('detail/{id_pengguna}', [OlahDataPenggunaController::class, 'showDetail'])->name('detail');
    });

    Route::prefix('surat_masuk')
        ->as('kadin.masuk.')
        ->group(function () {
            Route::get('/', [SuratMasukController::class, 'kadin_getSuratMasuk'])->name('surat_masuk');
            Route::get('tambah', [SuratMasukController::class, 'showtambahSuratMasuk'])->name('add');
            Route::post('tambah_surat_masuk', [SuratMasukController::class, 'tambahSuratMasuk'])->name('tambah');
            Route::delete('hapus/{id_suratmasuk}', [SuratMasukController::class, 'hapusSuratMasuk'])->name('delete');
            Route::put('update/{id_suratmasuk}', [SuratMasukController::class, 'updateSuratMasuk'])->name('update');
            Route::get('edit/{id_suratmasuk}', [SuratMasukController::class, 'showEditSuratMasuk'])->name('edit');
            Route::get('detail/{id_suratmasuk}', [SuratMasukController::class, 'showDetail'])->name('detail');
        });

        Route::prefix('surat_keluar')
        ->as('kadin.keluar.')
        ->group(function () {
            Route::get('/', [SuratKeluarController::class, 'kadin_getSuratKeluar'])->name('surat_keluar');
            Route::get('tambah', [SuratKeluarController::class, 'showtambahSuratKeluar'])->name('add');
            Route::post('tambah_surat_keluar', [SuratKeluarController::class, 'tambahSuratKeluar'])->name('tambah');
            Route::delete('hapus/{id_suratkeluar}', [SuratKeluarController::class, 'hapusSuratKeluar'])->name('delete');
            Route::put('update/{id_suratkeluar}', [SuratKeluarController::class, 'updateSuratKeluar'])->name('update');
            Route::get('edit/{id_suratkeluar}', [SuratKeluarController::class, 'showEditSuratKeluar'])->name('edit');
            Route::get('detail/{id_suratkeluar}', [SuratKeluarController::class, 'showDetail'])->name('detail');
        });

    Route::prefix('disposisi')
        ->as('kadin.disposisi.')
        ->group(function () {
            Route::get('/', [DisposisiController::class, 'kadin_getDisposisi'])->name('disposisi');
            Route::get('tambah/{id_suratmasuk}', [DisposisiController::class, 'showTambahDisposisi'])->name('tambah_disposisi');
            Route::post('tambah/{id_suratmasuk}', [DisposisiController::class, 'tambahDisposisi'])->name('add');
            Route::delete('hapus/{id_disposisi}', [DisposisiController::class, 'hapusDisposisi'])->name('delete');
            Route::put('update/{id_disposisi}', [DisposisiController::class, 'updateDisposisi'])->name('update');
            Route::get('edit/{id_disposisi}', [DisposisiController::class, 'showEditDisposisi'])->name('edit');
            Route::get('detail/{id_disposisi}', [DisposisiController::class, 'showDetail'])->name('detail');
        });

    // Logout Route
    Route::post('logout', [HomeController::class, 'logout'])->name('kadin.logout');
});

// GROUP ROUTES UNTUK SEKRETARIS
Route::group(['prefix' => 'dashboard/sekretaris', 'middleware' => ['auth', 'check.jabatan:4']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('sekretaris.home');

    Route::group(['prefix' => 'profile'], function () {
        Route::get('/', [HomeController::class, 'profile'])->name('sekretaris.profile');
        Route::post('update', [HomeController::class, 'updateprofile'])->name('sekretaris.profile.update');
    });

    // Routes untuk surat masuk
    Route::prefix('surat_masuk')
        ->as('sekretaris.masuk.')
        ->group(function () {
            Route::get('/', [SuratMasukController::class, 'sekretaris_getSuratMasuk'])->name('surat_masuk');
            Route::get('tambah', [SuratMasukController::class, 'showTambahSuratMasuk'])->name('add');
            Route::post('tambah', [SuratMasukController::class, 'tambahSuratMasuk'])->name('tambah');
            Route::delete('hapus/{id_suratmasuk}', [SuratMasukController::class, 'hapusSuratMasuk'])->name('delete');
            Route::put('update/{id_suratmasuk}', [SuratMasukController::class, 'updateSuratMasuk'])->name('update');
            Route::get('edit/{id_suratmasuk}', [SuratMasukController::class, 'showEditSuratMasuk'])->name('edit');
            Route::get('detail/{id_suratmasuk}', [SuratMasukController::class, 'showDetail'])->name('detail');
        });

    // Routes untuk surat keluar
    Route::prefix('surat_keluar')
        ->as('sekretaris.keluar.')
        ->group(function () {
            Route::get('/', [SuratKeluarController::class, 'sekretaris_getSuratKeluar'])->name('surat_keluar');
            Route::get('tambah', [SuratKeluarController::class, 'showTambahSuratKeluar'])->name('add');
            Route::post('tambah', [SuratKeluarController::class, 'tambahSuratKeluar'])->name('tambah');
            Route::delete('hapus/{id_suratkeluar}', [SuratKeluarController::class, 'hapusSuratKeluar'])->name('delete');
            Route::put('update/{id_suratkeluar}', [SuratKeluarController::class, 'updateSuratKeluar'])->name('update');
            Route::get('edit/{id_suratkeluar}', [SuratKeluarController::class, 'showEditSuratKeluar'])->name('edit');
            Route::get('detail/{id_suratkeluar}', [SuratKeluarController::class, 'showDetail'])->name('detail');
        });

    // Routes untuk disposisi
    Route::prefix('disposisi')
        ->as('sekretaris.disposisi.')
        ->group(function () {
            Route::get('/', [DisposisiController::class, 'sekretaris_getDisposisi'])->name('disposisi');
            Route::get('tambah/{id_suratmasuk}', [DisposisiController::class, 'showTambahDisposisi'])->name('tambah_disposisi');
            Route::post('tambah/{id_suratmasuk}', [DisposisiController::class, 'tambahDisposisi'])->name('add');
            Route::delete('hapus/{id_disposisi}', [DisposisiController::class, 'hapusDisposisi'])->name('delete');
            Route::put('update/{id_disposisi}', [DisposisiController::class, 'updateDisposisi'])->name('update');
            Route::get('edit/{id_disposisi}', [DisposisiController::class, 'showEditDisposisi'])->name('edit');
            Route::get('detail/{id_disposisi}', [DisposisiController::class, 'showDetail'])->name('detail');
        });

    // Routes untuk olah data pengguna
    Route::prefix('olah_data_pengguna')
        ->as('sekretaris.pengguna.')
        ->group(function () {
            Route::get('/', [OlahDataPenggunaController::class, 'sekretaris_getPengguna'])->name('pengguna');
            Route::get('detail/{id_pengguna}', [OlahDataPenggunaController::class, 'showDetail'])->name('detail');
        });

    // Logout Route
    Route::post('logout', [HomeController::class, 'logout'])->name('sekretaris.logout');
});

