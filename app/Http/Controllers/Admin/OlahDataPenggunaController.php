<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\M_Jabatan;
use App\Models\M_Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class OlahDataPenggunaController extends Controller
{
    public function admin_getPengguna(Request $request)
    {
        return $this->handleData($request, 1);
    }

    public function kabag_getPengguna(Request $request)
    {
        return $this->handleData($request, 2);
    }
    public function kadin_getPengguna(Request $request)
    {
        return $this->handleData($request, 3);
    }
    public function sekretaris_getPengguna(Request $request)
    {
        return $this->handleData($request, 4);
    }

    public function handleData(Request $request, $role)
    {
        if ($request->ajax()) {
            $data = M_Pengguna::with('jabatan')->select(['id_pengguna', 'nama', 'NIK', 'alamat', 'no_hp', 'id_jabatan']);
            return DataTables::of($data)
            ->addColumn('action', function($row) use ($role) {
                $routes = [
                    1 => 'admin',
                    2 => 'kabag',
                    3 => 'kadin',
                    4 => 'sekretaris'
                ];

                $routeKey = $routes[$role] ?? null;

                if ($routeKey) {

                    // Hanya admin bisa edit delete
                    if (in_array($role, [1])) {
                        $deleteUrl = route("{$routeKey}.pengguna.delete", $row->id_pengguna);
                        $editUrl = route("{$routeKey}.pengguna.update", $row->id_pengguna);

                        $deleteBtn = '<button class="btn btn-danger hapusData" data-id="'.$row->id_pengguna.'" data-hapus-url="'.$deleteUrl.'">Hapus</button>';
                        $editBtn = '<a href="'.$editUrl.'" class="btn btn-primary">Edit</a>';

                        return $deleteBtn . ' ' . $editBtn ;
                    }

                }
            })
                ->addColumn('jabatan', function ($row) {
                    return $row->jabatan->jabatan ?? 'Jabatan tidak ditemukan';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $pengguna = M_Pengguna::with('jabatan')->get();
        $jabatan = M_Jabatan::all();

        if ($role === 1) {
            return view('admin.olah_data_pengguna', compact('pengguna', 'jabatan'));
        } elseif ($role === 2) {
            return view('kabag.olah_data_pengguna', compact('pengguna', 'jabatan'));
        } elseif ($role === 3) {
            return view('kadin.olah_data_pengguna', compact('pengguna', 'jabatan'));
        } elseif ($role === 4) {
            return view('sekretaris.olah_data_pengguna', compact('pengguna', 'jabatan'));
        }

        return abort(403, 'Unauthorized action.');
    }

    public function tambahPengguna(Request $request)
    {
        if ($request->isMethod('post')) {
            $validatedData = $request->validate([
                'nama' => 'required|string|max:200|min:3',
                'NIK' => 'required|string|max:200|min:3',
                'alamat' => 'required|string|max:200|min:3',
                'no_hp' => 'required|string|max:200|min:3',
                'id_jabatan' => 'required|string|max:200',
            ]);

            M_Pengguna::create([
                'nama' => $request->nama,
                'NIK' => $request->NIK,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'id_jabatan' => $request->id_jabatan,
            ]);

            return response()->json(['message' => 'Data berhasil disimpan'], 200);
        }
    }

    public function hapusPengguna($id_pengguna)
    {
        $pengguna = M_Pengguna::where('id_pengguna', $id_pengguna)->first();

        if (!$pengguna) {
            return response()->json(['message' => 'Data tidak ditemukan!'], 404);
        }

        $pengguna->delete();
        return response()->json(['message' => 'Data berhasil dihapus'], 200);
    }

    public function updatePengguna(Request $request, $id_pengguna)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:200|min:3',
            'NIK' => 'required|string|max:200|min:3',
            'alamat' => 'required|string|max:200|min:3',
            'no_hp' => 'required|string|max:200|min:3',
            'id_jabatan' => 'required|exists:jabatan,id_jabatan',
        ]);

        $pengguna = M_Pengguna::findOrFail($id_pengguna);

        if (!$pengguna) {
            return response()->json(['message' => 'Data tidak ditemukan!'], 404);
        }

        $pengguna->nama = $validatedData['nama'];
        $pengguna->NIK = $validatedData['NIK'];
        $pengguna->alamat = $validatedData['alamat'];
        $pengguna->no_hp = $validatedData['no_hp'];
        $pengguna->id_jabatan = $validatedData['id_jabatan'];
        $pengguna->save();

        return response()->json(['message' => 'Data berhasil diupdate', 'redirect' => url()->previous()], 200);
    }

    public function showDetail($id_pengguna)
    {
        Log::info('User ID:', ['id' => auth()->user()->id]);
        Log::info('User Jabatan:', ['id_jabatan' => auth()->user()->id_jabatan]);
    
        $pengguna = M_Pengguna::findOrFail($id_pengguna);
        $jabatan = M_Jabatan::all();
    
        $id_jabatan = auth()->user()->id_jabatan;
    
        if ($id_jabatan === 1) {
            return view('admin.olah_data_pengguna', compact('pengguna', 'jabatan'));
        } elseif ($id_jabatan === 2) {
            return view('kabag.olah_data_pengguna', compact('pengguna', 'jabatan'));
        } elseif ($id_jabatan === 3) {
            return view('kadin.olah_data_pengguna', compact('pengguna', 'jabatan'));
        }
    
        return abort(403, 'Unauthorized action.');
    }
    
    
}
