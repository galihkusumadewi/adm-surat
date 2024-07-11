<?php 
namespace App\Http\Controllers\Admin;

use File;
use App\Models\M_Asal_Surat;
use Illuminate\Http\Request;
use App\Models\M_Jenis_Surat;
use App\Models\M_Sifat_Surat;
use App\Models\M_Surat_Keluar;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class SuratKeluarController extends Controller
{
    public function admin_getSuratKeluar(Request $request)
    {
        return $this->handleData($request, 1); 
    }

    public function kabag_getSuratKeluar(Request $request)
    {
        return $this->handleData($request, 2); 
    }

    public function kadin_getSuratKeluar(Request $request)
    {
        return $this->handleData($request, 3); 
    }
    public function sekretaris_getSuratKeluar(Request $request)
    {
        return $this->handleData($request, 4); 
    }

    public function handleData(Request $request, $role)
{
    if ($request->ajax()) {
        $query = M_Surat_Keluar::with(['asalSurat', 'jenisSurat', 'sifatSurat']);

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
                    $detailUrl = route("{$routeKey}.keluar.detail", $row->id_suratkeluar);
                    $detailBtn = '<a href="'.$detailUrl.'" class="btn btn-info">Detail</a>';

                    // Only admin, kadin, and sekretaris can edit and delete
                    if (in_array($role, [1, 3, 4])) {
                        $deleteUrl = route("{$routeKey}.keluar.delete", $row->id_suratkeluar);
                        $editUrl = route("{$routeKey}.keluar.edit", $row->id_suratkeluar);

                        $deleteBtn = '<button class="btn btn-danger hapusData" data-id="'.$row->id_suratkeluar.'" data-hapus-url="'.$deleteUrl.'">Hapus</button>';
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

    $query = M_Surat_Keluar::query();

    // Tambahan kondisi untuk role sekretaris dan kabag
    if (in_array($role, [1, 2, 4])) {
        $query->whereNotIn('id_sifat', [1, 2]);
    }

    $suratKeluar = $query->get();
    $role = auth()->user()->id_jabatan;

    switch ($role) {
        case 1:
            return view('admin.surat_keluar', compact('suratKeluar'));
        case 2:
            return view('kabag.surat_keluar', compact('suratKeluar'));
        case 3:
            return view('kadin.surat_keluar', compact('suratKeluar'));
        case 4:
            return view('sekretaris.surat_keluar', compact('suratKeluar'));
        default:
            return redirect()->back()->with('error', 'Role tidak dikenal!');
    }
}

    public function tambahSuratKeluar(Request $request)
    {
        $validatedData = $request->validate([
            'no_surat' => 'required|string|max:200|min:3',
            'tgl_surat' => 'required|date',
            'lampiran' => 'nullable|string|max:200',
            'perihal' => 'nullable|string|max:200',
            'keterangan' => 'nullable|string|max:200',
            'file_surat' => 'nullable|file|max:2000',
            'id_jenis' => 'required|integer',
            'id_asal_surat' => 'required|integer',
            'id_sifat' => 'required|integer',
            'asal' => 'required|string',
        ]);

        $suratKeluar = new M_Surat_Keluar();
        $suratKeluar->no_surat = $request->no_surat;
        $suratKeluar->tgl_surat = $request->tgl_surat;
        $suratKeluar->lampiran = $request->lampiran;
        $suratKeluar->perihal = $request->perihal;
        $suratKeluar->keterangan = $request->keterangan;
        $suratKeluar->id_jenis = $request->id_jenis;
        $suratKeluar->id_sifat = $request->id_sifat;
        $suratKeluar->id_asal_surat = $request->id_asal_surat;
        $suratKeluar->asal = $request->asal;

        if ($request->hasFile('file_surat')) {
            $file = $request->file('file_surat');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/surat_keluar'), $fileName);
            $suratKeluar->file_surat = $fileName;
        }

        $suratKeluar->save();
    }

   public function showTambahSuratKeluar()
{
    $asalSurat = M_Asal_Surat::all();
    $jenisSurat = M_Jenis_Surat::all();
    $sifatSurat = M_Sifat_Surat::all();

    $role = auth()->user()->id_jabatan;

    Log::info('showTambahSuratKeluar dipanggil. Role: ' . $role);

    switch ($role) {
        case 1:
            return view('admin.tambah_surat_keluar', compact('asalSurat', 'jenisSurat', 'sifatSurat'));
        case 2:
            return view('kabag.tambah_surat_keluar', compact('asalSurat', 'jenisSurat', 'sifatSurat'));
        case 3:
            return view('kadin.tambah_surat_keluar', compact('asalSurat', 'jenisSurat', 'sifatSurat'));
        case 4:
            return view('sekretaris.tambah_surat_keluar', compact('asalSurat', 'jenisSurat', 'sifatSurat'));
        default:
            return redirect()->back()->with('error', 'Role tidak dikenal!');
    }
}




    public function showEditSuratKeluar($id_suratkeluar)
    {
        $suratKeluar = M_Surat_Keluar::findOrFail($id_suratkeluar);
        $asalSurat = M_Asal_Surat::all();
        $jenisSurat = M_Jenis_Surat::all();
        $sifatSurat = M_Sifat_Surat::all();

        $role = auth()->user()->id_jabatan;
        
        switch ($role) {
            case 1:
                return view('admin.edit_surat_keluar', compact('asalSurat','jenisSurat', 'suratKeluar', 'sifatSurat'));
            case 2:
                return view('kabag.edit_surat_keluar', compact('asalSurat', 'jenisSurat', 'suratKeluar', 'sifatSurat'));
            case 3:
                return view('kadin.edit_surat_keluar', compact('asalSurat', 'jenisSurat','suratKeluar', 'sifatSurat'));
            case 4:
                return view('sekretaris.edit_surat_keluar', compact('asalSurat', 'jenisSurat','suratKeluar', 'sifatSurat'));
            default:
                return redirect()->back()->with('error', 'Role tidak dikenal!');
        }
    }


    public function showDetail($id_suratkeluar)
    { 
        $suratKeluar = M_Surat_Keluar::findOrFail($id_suratkeluar);
        $asalSurat = M_Asal_Surat::all();
        $jenisSurat = M_Jenis_Surat::all();
        $sifatSurat = M_Sifat_Surat::all();

        $role = auth()->user()->id_jabatan;
        
        switch ($role) {
            case 1:
                return view('admin.surat_keluar_detail', compact('asalSurat','jenisSurat', 'suratKeluar', 'sifatSurat'));
            case 2:
                return view('kabag.surat_keluar_detail', compact('asalSurat', 'jenisSurat', 'suratKeluar', 'sifatSurat'));
            case 3:
                return view('kadin.surat_keluar_detail', compact('asalSurat', 'jenisSurat','suratKeluar', 'sifatSurat'));
            case 4:
                return view('sekretaris.surat_keluar_detail', compact('asalSurat', 'jenisSurat','suratKeluar', 'sifatSurat'));
            default:
                return redirect()->back()->with('error', 'Role tidak dikenal!');
        }
    }

    public function hapusSuratKeluar($id_suratkeluar)
    {
        $suratKeluar = M_Surat_Keluar::find($id_suratkeluar);

        // if (!$suratKeluar) {
        //     return redirect()->route('keluar.surat_keluar')->with('error', 'Data tidak ditemukan!');
        // }

        // Hapus file surat dari folder jika ada
        if (!empty($suratKeluar->file_surat)) {
            $file_path = public_path('uploads/surat_keluar/' . $suratKeluar->file_surat);
            if (File::exists($file_path)) {
                File::delete($file_path);
            }
        }

        $suratKeluar->delete();
        $role = auth()->user()->id_jabatan;
        switch ($role) {
            case 1:
                return redirect()->route('admin.keluar.surat_keluar')->with('success', 'Data berhasil diperbarui');           
            case 2:
                return redirect()->route('kabag.keluar.surat_keluar')->with('success', 'Data berhasil diperbarui');
            case 3:
                return redirect()->route('kadin.keluar.surat_keluar')->with('success', 'Data berhasil diperbarui');
            case 4:
                return redirect()->route('sekretaris.keluar.surat_keluar')->with('success', 'Data berhasil diperbarui');
        };
    }

    public function updateSuratKeluar(Request $request, $id_suratkeluar)
    {
        $validatedData = $request->validate([
            'no_surat' => 'required|string|max:200|min:3',
            'tgl_surat' => 'required|date',
            'lampiran' => 'nullable|string|max:200',
            'perihal' => 'nullable|string|max:200',
            'keterangan' => 'nullable|string|max:200',
            'file_surat' => 'nullable|file|max:2000',
            'id_jenis' => 'required|integer',
            'id_asal_surat' => 'required|integer',
            'id_sifat' => 'required|integer',
        ]);

        $suratKeluar = M_Surat_Keluar::find($id_suratkeluar);

        if (!$suratKeluar) {
            return redirect()->route('keluar.surat_keluar')->with('error', 'Data tidak ditemukan!');
        }

        // $suratKeluar->no_surat = $request->no_surat;
        // $suratKeluar->tgl_ = $request->tanggal;
        // $suratKeluar->lampiran = $request->lampiran;

        if ($request->hasFile('file_surat')) {
            // Menghapus file lama jika ada
            $oldFilePath = public_path('uploads/surat_keluar/' . $suratKeluar->file_surat);
            if (File::exists($oldFilePath)) {
                File::delete($oldFilePath);
            }
    
            // Upload file surat yang baru
            $file = $request->file('file_surat');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/surat_keluar'), $fileName);
            $suratKeluar->file_surat = $fileName;
        }

        $suratKeluar->no_surat = $request->no_surat;
        $suratKeluar->tgl_surat = $request->tgl_surat;
        $suratKeluar->lampiran = $request->lampiran;
        $suratKeluar->perihal = $request->perihal;
        $suratKeluar->keterangan = $request->keterangan;
        $suratKeluar->id_jenis = $request->id_jenis;
        $suratKeluar->id_asal_surat = $request->id_asal_surat;
        $suratKeluar->id_sifat = $request->id_sifat;
        $suratKeluar->save();

        $role = auth()->user()->id_jabatan;
        switch ($role) {
            case 1:
                return redirect()->route('admin.keluar.surat_keluar')->with('success', 'Data berhasil diperbarui');           
            case 2:
                return redirect()->route('kabag.keluar.surat_keluar')->with('success', 'Data berhasil diperbarui');
            case 3:
                return redirect()->route('kadin.keluar.surat_keluar')->with('success', 'Data berhasil diperbarui');
            case 4:
                return redirect()->route('sekretaris.keluar.surat_keluar')->with('success', 'Data berhasil diperbarui');
        };
    }
}
