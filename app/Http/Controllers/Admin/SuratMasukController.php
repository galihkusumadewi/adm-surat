<?php

namespace App\Http\Controllers\Admin;

use File;
use App\Models\M_Jabatan;
use App\Models\M_Pengguna;
use App\Models\M_Asal_Surat;
use Illuminate\Http\Request;
use App\Models\M_Jenis_Surat;
use App\Models\M_Sifat_Surat;
use App\Models\M_Surat_Masuk;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class SuratMasukController extends Controller
{
    public function admin_getSuratMasuk(Request $request)
    {
        return $this->handleData($request, 1); 
    }

    public function kabag_getSuratMasuk(Request $request)
    {
        return $this->handleData($request, 2); 
    }

    public function kadin_getSuratMasuk(Request $request)
    {
        return $this->handleData($request, 3); 
    }
    
    public function sekretaris_getSuratMasuk(Request $request)
    {
        return $this->handleData($request, 4); 
    }

    public function handleData(Request $request, $role)
{
    if ($request->ajax()) {
        $query = M_Surat_Masuk::with(['asalSurat', 'jenisSurat', 'jabatan', 'sifatSurat', 'pengguna']);

        // Tambahan kondisi untuk role sekretaris dan kabag
        if (in_array($role, [1, 2, 4])) {
            $query->whereNotIn('id_sifat', [1, 2]);
        }

        $data = $query->get();

        return DataTables::of($data)
            ->addColumn('action', function ($row) use ($role) {
                $routes = [
                    1 => 'admin',
                    2 => 'kabag',
                    3 => 'kadin',
                    4 => 'sekretaris'
                ];

                $routeKey = $routes[$role] ?? null;

                if ($routeKey) {
                    $detailUrl = route("{$routeKey}.masuk.detail", $row->id_suratmasuk);
                    $detailBtn = '<a href="'.$detailUrl.'" class="btn btn-info">Detail</a>';

                    // Only admin, kadin, and sekretaris can edit and delete
                    if (in_array($role, [1, 3, 4])) {
                        $deleteUrl = route("{$routeKey}.masuk.delete", $row->id_suratmasuk);
                        $editUrl = route("{$routeKey}.masuk.edit", $row->id_suratmasuk);

                        $deleteBtn = '<button class="btn btn-danger hapusData" data-id="'.$row->id_suratmasuk.'" data-hapus-url="'.$deleteUrl.'">Hapus</button>';
                        $editBtn = '<a href="'.$editUrl.'" class="btn btn-primary">Edit</a>';

                        return $deleteBtn . ' ' . $editBtn . ' ' . $detailBtn;
                    }

                    return $detailBtn;
                } else {
                    return ''; // Handle case where role does not match any route
                }
            })
            ->addColumn('id_sifat', function ($row) {
                return $row->sifatSurat->sifat_surat ?? 'Sifat Surat tidak ditemukan';
            })
            ->rawColumns(['action'])
            ->make(true);
    } 

    $query = M_Surat_Masuk::query();

    // Tambahan kondisi untuk role sekretaris dan kabag
    if (in_array($role, [1, 2, 4])) {
        $query->whereNotIn('id_sifat', [1, 2]);
    }

    $suratMasuk = $query->get();
    $role = auth()->user()->id_jabatan;

    switch ($role) {
        case 1:
            return view('admin.surat_masuk', compact('suratMasuk'));
        case 2:
            return view('kabag.surat_masuk', compact('suratMasuk'));
        case 3:
            return view('kadin.surat_masuk', compact('suratMasuk'));
        case 4:
            return view('sekretaris.surat_masuk', compact('suratMasuk'));
        default:
            return redirect()->back()->with('error', 'Role tidak dikenal!');
    }
}


    public function tambahSuratMasuk(Request $request)
    {
        $validatedData = $request->validate([
            'no_surat' => 'required|string|max:200|min:3',
            'tanggal' => 'required|date',
            'lampiran' => 'nullable|string|max:200',
            'perihal' => 'nullable|string|max:200',
            'keterangan' => 'nullable|string|max:200',
            'tujuan' => 'nullable|string|max:200',
            'file_surat' => 'nullable|file|max:2000',
            'id_jenis' => 'required|integer',
            'id_jabatan' => 'required|integer',
            'id_asal_surat' => 'required|integer',
            'id_sifat' => 'required|integer',
            'id_pengguna' => 'required|integer',
        ]);

        $suratMasuk = new M_Surat_Masuk(); 
        $suratMasuk->no_surat = $request->no_surat;
        $suratMasuk->tanggal = $request->tanggal;
        $suratMasuk->lampiran = $request->lampiran;
        $suratMasuk->tujuan = $request->tujuan;
        $suratMasuk->perihal = $request->perihal;
        $suratMasuk->keterangan = $request->keterangan;
        $suratMasuk->id_jenis = $request->id_jenis;
        $suratMasuk->id_jabatan = $request->id_jabatan;
        $suratMasuk->id_asal_surat = $request->id_asal_surat;
        $suratMasuk->id_sifat = $request->id_sifat;
        $suratMasuk->id_pengguna = $request->id_pengguna;

        if ($request->hasFile('file_surat')) {
            $file = $request->file('file_surat');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/surat_masuk'), $fileName);
            $suratMasuk->file_surat = $fileName;
        }

        $suratMasuk->save();

        $role = auth()->user()->id_jabatan;
        switch ($role) {
            case 1:
                return redirect()->route('admin.masuk.surat_masuk')->with('status', 'Data berhasil ditambahkan');           
            case 2:
                return redirect()->route('kabag.masuk.surat_masuk')->with('status', 'Data berhasil ditambahkan');
            case 3:
                return redirect()->route('kadin.masuk.surat_masuk')->with('status', 'Data berhasil ditambahkan');
            case 4:
                return redirect()->route('sekretaris.masuk.surat_masuk')->with('status', 'Data berhasil ditambahkan');
        }
    }

    public function showTambahSuratMasuk()
    {
        $pengguna = M_Pengguna::all();
        $asalSurat = M_Asal_Surat::all();
        $jabatan = M_Jabatan::all();
        $jenisSurat = M_Jenis_Surat::all();
        $sifatSurat = M_Sifat_Surat::all();

        $role = auth()->user()->id_jabatan;

        switch ($role) {
            case 1:
                return view('admin.tambah_surat_masuk', compact('pengguna', 'asalSurat', 'jabatan', 'jenisSurat', 'sifatSurat'));
            case 2:
                return view('kabag.tambah_surat_masuk', compact('pengguna', 'asalSurat', 'jabatan', 'jenisSurat', 'sifatSurat'));
            case 3:
                return view('kadin.tambah_surat_masuk', compact('pengguna', 'asalSurat', 'jabatan', 'jenisSurat', 'sifatSurat'));
            case 4:
                return view('sekretaris.tambah_surat_masuk', compact('pengguna', 'asalSurat', 'jabatan', 'jenisSurat', 'sifatSurat'));
            default:
                return redirect()->back()->with('error', 'Role tidak dikenal!');
        }
    }

    public function showEditSuratMasuk($id_suratmasuk)
    {
        $suratMasuk = M_Surat_Masuk::findOrFail($id_suratmasuk);
        $pengguna = M_Pengguna::all();
        $asalSurat = M_Asal_Surat::all();
        $jabatan = M_Jabatan::all();
        $jenisSurat = M_Jenis_Surat::all();
        $sifatSurat = M_Sifat_Surat::all();

        $role = auth()->user()->id_jabatan;
        
        switch ($role) {
            case 1:
                return view('admin.edit_surat_masuk', compact('pengguna', 'asalSurat', 'jabatan', 'jenisSurat', 'sifatSurat', 'suratMasuk'));
            case 2:
                return view('kabag.edit_surat_masuk', compact('pengguna', 'asalSurat', 'jabatan', 'jenisSurat', 'sifatSurat', 'suratMasuk'));
            case 3:
                return view('kadin.edit_surat_masuk', compact('pengguna', 'asalSurat', 'jabatan', 'jenisSurat', 'sifatSurat', 'suratMasuk'));
            case 4:
                return view('sekretaris.edit_surat_masuk', compact('pengguna', 'asalSurat', 'jabatan', 'jenisSurat', 'sifatSurat', 'suratMasuk'));
            default:
                return redirect()->back()->with('error', 'Role tidak dikenal!');
        }
    }

    public function showDetail($id_suratmasuk)
    {
        $suratMasuk = M_Surat_Masuk::findOrFail($id_suratmasuk);
        $pengguna = M_Pengguna::all();
        $asalSurat = M_Asal_Surat::all();
        $jabatan = M_Jabatan::all();
        $jenisSurat = M_Jenis_Surat::all();
        $sifatSurat = M_Sifat_Surat::all();

        $role = auth()->user()->id_jabatan;
        
        switch ($role) {
            case 1:
                return view('admin.surat_masuk_detail', compact('suratMasuk','pengguna', 'asalSurat', 'jabatan', 'jenisSurat', 'sifatSurat'));
            case 2:
                return view('kabag.surat_masuk_detail', compact('suratMasuk','pengguna', 'asalSurat', 'jabatan', 'jenisSurat', 'sifatSurat'));
            case 3:
                return view('kadin.surat_masuk_detail', compact('suratMasuk','pengguna', 'asalSurat', 'jabatan', 'jenisSurat', 'sifatSurat'));
            case 4:
                return view('sekretaris.surat_masuk_detail', compact('suratMasuk','pengguna', 'asalSurat', 'jabatan', 'jenisSurat', 'sifatSurat'));
            default:
                return redirect()->back()->with('error', 'Role tidak dikenal!');
        }
    }

    public function hapusSuratMasuk($id_suratmasuk)
    {
        $suratMasuk = M_Surat_Masuk::find($id_suratmasuk);

        if (!$suratMasuk) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        // Hapus file surat dari folder jika ada
        if (!empty($suratMasuk->file_surat)) {
            $file_path = public_path('uploads/surat_masuk/' . $suratMasuk->file_surat);
            if (File::exists($file_path)) {
                File::delete($file_path);
            }
        }

        $suratMasuk->delete();
        $role = auth()->user()->id_jabatan;
        switch ($role) {
            case 1:
                return redirect()->route('admin.masuk.surat_masuk')->with('success', 'Data berhasil dihapus');           
            case 2:
                return redirect()->route('kabag.masuk.surat_masuk')->with('success', 'Data berhasil dihapus');
            case 3:
                return redirect()->route('kadin.masuk.surat_masuk')->with('success', 'Data berhasil dihapus');
            case 4:
                return redirect()->route('sekretaris.masuk.surat_masuk')->with('success', 'Data berhasil dihapus');
            default:
                return redirect()->back()->with('error', 'Role tidak dikenal!');
        }
    }

    public function updateSuratMasuk(Request $request, $id_suratmasuk)
    {
        $validatedData = $request->validate([
            'no_surat' => 'required|string|max:200|min:3',
            'tanggal' => 'required|date',
            'lampiran' => 'nullable|string|max:200',
            'tujuan' => 'nullable|string|max:200',
            'perihal' => 'nullable|string|max:200',
            'keterangan' => 'nullable|string|max:200',
            'file_surat' => 'nullable|file|max:2000',
            'id_jenis' => 'required|integer',
            'id_jabatan' => 'required|integer',
            'id_asal_surat' => 'required|integer',
            'id_sifat' => 'required|integer',
            'id_pengguna' => 'required|integer',
        ]);

        $suratMasuk = M_Surat_Masuk::find($id_suratmasuk);

        if (!$suratMasuk) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        $suratMasuk->no_surat = $request->no_surat;
        $suratMasuk->tanggal = $request->tanggal;
        $suratMasuk->lampiran = $request->lampiran;

        if ($request->hasFile('file_surat')) {
            // Menghapus file lama jika ada
            $oldFilePath = public_path('uploads/surat_masuk/' . $suratMasuk->file_surat);
            if (File::exists($oldFilePath)) {
                File::delete($oldFilePath);
            }
    
            // Upload file surat yang baru
            $file = $request->file('file_surat');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/surat_masuk'), $fileName);
            $suratMasuk->file_surat = $fileName;
        }

        $suratMasuk->id_jenis = $request->id_jenis;
        $suratMasuk->id_jabatan = $request->id_jabatan;
        $suratMasuk->id_asal_surat = $request->id_asal_surat;
        $suratMasuk->id_sifat = $request->id_sifat;
        $suratMasuk->id_pengguna = $request->id_pengguna;
        $suratMasuk->save();

        $role = auth()->user()->id_jabatan;
        switch ($role) {
            case 1:
                return redirect()->route('admin.masuk.surat_masuk')->with('success', 'Data berhasil diperbarui');           
            case 2:
                return redirect()->route('kabag.masuk.surat_masuk')->with('success', 'Data berhasil diperbarui');
            case 3:
                return redirect()->route('kadin.masuk.surat_masuk')->with('success', 'Data berhasil diperbarui');
            case 4:
                return redirect()->route('sekretaris.masuk.surat_masuk')->with('success', 'Data berhasil diperbarui');
            default:
                return redirect()->back()->with('error', 'Role tidak dikenal!');
        }
    }
}
