<?php

namespace App\Http\Controllers\Admin;

use Log;
use App\Models\M_Disposisi;
use App\Models\M_Asal_Surat;
use App\Models\M_Sifat_Surat;
use Illuminate\Http\Request;
use App\Models\M_Surat_Masuk;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

class DisposisiController extends Controller
{
    public function admin_getDisposisi(Request $request)
    {
        return $this->handleData($request, 1); 
    }

    public function kabag_getDisposisi(Request $request)
    {
        return $this->handleData($request, 2); 
    }

    public function kadin_getDisposisi(Request $request)
    {
        return $this->handleData($request, 3); 
    }

    public function sekretaris_getDisposisi(Request $request)
    {
        return $this->handleData($request, 4); 
    }

    public function showTambahDisposisi($id_suratmasuk) 
    {
        $asalSurat = M_Asal_Surat::all();
        $sifatSurat = M_Sifat_Surat::all();
        $suratMasuk = M_Surat_Masuk::findOrFail($id_suratmasuk);
        $asalSuratDetail = $suratMasuk->asalSurat; 

        $role = auth()->user()->id_jabatan;

        switch ($role) {
            case 1:
                return view('admin.tambah_disposisi', compact('sifatSurat', 'asalSurat', 'suratMasuk', 'asalSuratDetail', 'id_suratmasuk'));
            case 2:
                return view('kabag.tambah_disposisi', compact('sifatSurat', 'asalSurat', 'suratMasuk', 'asalSuratDetail', 'id_suratmasuk'));
            case 3:
                return view('kadin.tambah_disposisi', compact('sifatSurat', 'asalSurat', 'suratMasuk', 'asalSuratDetail', 'id_suratmasuk'));
            case 4:
                return view('sekretaris.tambah_disposisi', compact('sifatSurat', 'asalSurat', 'suratMasuk', 'asalSuratDetail', 'id_suratmasuk'));
            default:
                return redirect()->back()->with('error', 'Role tidak dikenal!');
        }
    }

    public function showEditDisposisi($id_disposisi)
    {
        $disposisi = M_Disposisi::findOrFail($id_disposisi);
        $asalSurat = M_Asal_Surat::all();
        $suratMasuk = M_Surat_Masuk::all();

        $role = auth()->user()->id_jabatan;

        switch ($role) {
            case 1:
                return view('admin.edit_disposisi', compact('asalSurat', 'suratMasuk','disposisi'));
            case 2:
                return view('kabag.edit_disposisi', compact('asalSurat', 'suratMasuk','disposisi'));
            case 3:
                return view('kadin.edit_disposisi', compact('asalSurat', 'suratMasuk','disposisi'));
            case 4:
                return view('sekretaris.edit_disposisi', compact('asalSurat', 'suratMasuk','disposisi'));
            default:
                return redirect()->back()->with('error', 'Role tidak dikenal!');
        }
    }

    public function handleData(Request $request, $role)
    {
        if ($request->ajax()) {
            $data = M_Disposisi::with('asalSurat', 'suratMasuk')
                ->select(['id_disposisi', 'no_disposisi', 'tanggal', 'jenis_disposisi', 'keterangan', 'instruksi', 'id_asal_surat', 'id_suratmasuk', 'sifat_disposisi', 'perihal'])
                ->get();
    
            return DataTables::of($data)
                ->addColumn('asal_disposisi', function($row) {
                    return $row->asalSurat->asal_surat ?? 'Asal surat tidak ditemukan';
                })
                ->addColumn('action', function($row) use ($role) {
                    $routes = [
                        1 => 'admin',
                        2 => 'kabag',
                        3 => 'kadin',
                        4 => 'sekretaris'
                    ];
    
                    $routeKey = $routes[$role] ?? null;
    
                    if ($routeKey) {
                        $detailUrl = route("{$routeKey}.disposisi.detail", $row->id_disposisi);
                        $detailBtn = '<a href="'.$detailUrl.'" class="btn btn-info">Detail</a>';
    
                        // Hanya admin, kadin, dan sekretaris bisa edit delete
                        if (in_array($role, [1, 3, 4])) {
                            $deleteUrl = route("{$routeKey}.disposisi.delete", $row->id_disposisi);
                            $editUrl = route("{$routeKey}.disposisi.edit", $row->id_disposisi);
    
                            $deleteBtn = '<button class="btn btn-danger hapusData" data-id="'.$row->id_disposisi.'" data-hapus-url="'.$deleteUrl.'">Hapus</button>';
                            $editBtn = '<a href="'.$editUrl.'" class="btn btn-primary">Edit</a>';
    
                            return $deleteBtn . ' ' . $editBtn . ' ' . $detailBtn;
                        }
    
                        return $detailBtn;
                    } else {
                        return '';
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
        } 
    
        // Menggunakan variabel $role yang diterima dari parameter fungsi
        switch ($role) {
            case 1:
                $asalSurat = M_Asal_Surat::all();
                $suratMasuk = M_Surat_Masuk::all();
                $disposisi = M_Disposisi::all();
                return view('admin.data_disposisi', compact('asalSurat', 'suratMasuk','disposisi'));
            case 2:
                $asalSurat = M_Asal_Surat::all();
                $suratMasuk = M_Surat_Masuk::all();
                $disposisi = M_Disposisi::all();
                return view('kabag.data_disposisi', compact('asalSurat', 'suratMasuk','disposisi'));
            case 3:
                $asalSurat = M_Asal_Surat::all();
                $suratMasuk = M_Surat_Masuk::all();
                $disposisi = M_Disposisi::all();
                return view('kadin.data_disposisi', compact('asalSurat', 'suratMasuk','disposisi'));
            case 4:
                $asalSurat = M_Asal_Surat::all();
                $suratMasuk = M_Surat_Masuk::all();
                $disposisi = M_Disposisi::all();
                return view('sekretaris.data_disposisi', compact('asalSurat', 'suratMasuk','disposisi'));
            default:
                return redirect()->back()->with('error', 'Role tidak dikenal!');
        }
    }

    public function tambahDisposisi(Request $request, $id_suratmasuk)
    {
        Log::info($request->all());
        $validatedData = $request->validate([
            'no_disposisi' => 'required|string|max:200|min:3',
            'tanggal' => 'required|date',
            'sifat_disposisi' => 'required|string|max:200',
            'jenis_disposisi' => 'required|string|max:200',
            'perihal' => 'required|string|max:200',
            'instruksi' => 'nullable|string|max:200',
            'keterangan' => 'nullable|string|max:200',
            'id_asal_surat' => 'required|integer',
        ]);

        $disposisi = new M_Disposisi();
        $disposisi->no_disposisi = $request->no_disposisi;
        $disposisi->tanggal = $request->tanggal;
        $disposisi->sifat_disposisi = $request->sifat_disposisi;
        $disposisi->jenis_disposisi = $request->jenis_disposisi;
        $disposisi->perihal = $request->perihal;
        $disposisi->instruksi = $request->instruksi;
        $disposisi->keterangan = $request->keterangan;
        $disposisi->id_suratmasuk = $id_suratmasuk;
        $disposisi->id_asal_surat = $request->id_asal_surat;

        $disposisi->save();

        $role = auth()->user()->id_jabatan;

        switch ($role) { 
            case 1:
                return redirect()->route('admin.disposisi.disposisi', ['id_suratmasuk' => $id_suratmasuk])->with('success', 'Disposisi berhasil ditambahkan.');
            case 2:
                return redirect()->route('kabag.disposisi.disposisi', ['id_suratmasuk' => $id_suratmasuk])->with('success', 'Disposisi berhasil ditambahkan.');
            case 3:
                return redirect()->route('kadin.disposisi.disposisi', ['id_suratmasuk' => $id_suratmasuk])->with('success', 'Disposisi berhasil ditambahkan.');
            case 4:
                return redirect()->route('sekretaris.disposisi.disposisi', ['id_suratmasuk' => $id_suratmasuk])->with('success', 'Disposisi berhasil ditambahkan.');
            default:
                return redirect()->back()->with('error', 'Role tidak dikenal!');
        }
    }

    public function updateDisposisi(Request $request, $id_disposisi)
    {
        $validatedData = $request->validate([
            'no_disposisi' => 'required|string|max:200|min:3',
            'tanggal' => 'required|date',
            'sifat_disposisi' => 'required|string|max:200',
            'jenis_disposisi' => 'required|string|max:200',
            'perihal' => 'required|string|max:200',
            'instruksi' => 'nullable|string|max:200',
            'keterangan' => 'nullable|string|max:200',
            'id_asal_surat' => 'required|integer',
            'id_suratmasuk' => 'required|integer',
        ]);

        $disposisi = M_Disposisi::findOrFail($id_disposisi);
        $disposisi->no_disposisi = $request->no_disposisi;
        $disposisi->tanggal = $request->tanggal;
        $disposisi->sifat_disposisi = $request->sifat_disposisi;
        $disposisi->jenis_disposisi = $request->jenis_disposisi;
        $disposisi->perihal = $request->perihal;
        $disposisi->instruksi = $request->instruksi;
        $disposisi->keterangan = $request->keterangan;
        $disposisi->id_asal_surat = $request->id_asal_surat;
        $disposisi->id_suratmasuk = $request->id_suratmasuk;

        $disposisi->save();

        $role = auth()->user()->id_jabatan;

        switch ($role) {
            case 1:
                return redirect()->route('admin.disposisi.disposisi', ['id_suratmasuk' => $request->id_suratmasuk])->with('success', 'Disposisi berhasil diupdate.');
            case 2:
                return redirect()->route('kabag.disposisi.disposisi', ['id_suratmasuk' => $request->id_suratmasuk])->with('success', 'Disposisi berhasil diupdate.');
            case 3:
                return redirect()->route('kadin.disposisi.disposisi', ['id_suratmasuk' => $request->id_suratmasuk])->with('success', 'Disposisi berhasil diupdate.');
            case 4:
                return redirect()->route('sekretaris.disposisi.disposisi', ['id_suratmasuk' => $request->id_suratmasuk])->with('success', 'Disposisi berhasil diupdate.');
            default:
                return redirect()->back()->with('error', 'Role tidak dikenal!');
        }
    }

    public function hapusDisposisi($id_disposisi)
    {
        $disposisi = M_Disposisi::findOrFail($id_disposisi);
        $disposisi->delete();

        $role = auth()->user()->id_jabatan;

        switch ($role) {
            case 1:
                return redirect()->route('admin.disposisi.disposisi')->with('success', 'Disposisi berhasil dihapus.');
            case 2:
                return redirect()->route('kabag.disposisi.disposisi')->with('success', 'Disposisi berhasil dihapus.');
            case 3:
                return redirect()->route('kadin.disposisi.disposisi')->with('success', 'Disposisi berhasil dihapus.');
            case 4:
                return redirect()->route('sekretaris.disposisi.disposisi')->with('success', 'Disposisi berhasil dihapus.');
            default:
                return redirect()->back()->with('error', 'Role tidak dikenal!');
        }
    }

    public function showDetail($id_disposisi)
    {
        $disposisi = M_Disposisi::with('suratMasuk')->findOrFail($id_disposisi);
        $asalSurat = M_Asal_Surat::all();
        $suratMasuk = M_Surat_Masuk::all();

        $role = auth()->user()->id_jabatan;

        switch ($role) {
            case 1:
                return view('admin.disposisi_detail', compact('asalSurat', 'suratMasuk','disposisi'));
            case 2:
                return view('kabag.disposisi_detail', compact('asalSurat', 'suratMasuk','disposisi'));
            case 3:
                return view('kadin.disposisi_detail', compact('asalSurat', 'suratMasuk','disposisi'));
            case 4:
                return view('sekretaris.disposisi_detail', compact('asalSurat', 'suratMasuk','disposisi'));
            default:
                return redirect()->back()->with('error', 'Role tidak dikenal!');
        }
    }
}
