<?php

namespace App\Http\Controllers\Admin;

use App\Models\M_Jabatan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

class JabatanController extends Controller
{
    public function admin_getJabatan(Request $request)
    {
        return $this->handleData($request, 'admin');
    }

    public function handleData(Request $request, $role)
    {
        if ($request->ajax()) {
            $data = M_Jabatan::select(['id_jabatan', 'jabatan'])->get();
            return DataTables::of($data)
            ->addColumn('action', function($row) use ($role) {
                $deleteUrl = route("admin.jabatan.delete", $row->id_jabatan);
                $editUrl = route("admin.jabatan.update", $row->id_jabatan);

                $deleteBtn = '<button class="btn btn-danger hapusData" data-id="'.$row->id_jabatan.'" data-hapus-url="'.$deleteUrl.'">Hapus</button>';

                $editBtn = '<button class="btn btn-primary editData" 
                    data-id="' . $row->id_jabatan . '" 
                    data-jabatan="' . $row->jabatan . '" 
                    data-toggle="modal" data-target="#editJabatanModal">Edit</button>';

                return $deleteBtn . ' ' . $editBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }

        $jabatan = M_Jabatan::all();
        return view($role . '.jabatan', compact('jabatan'));
    }

    public function tambahJabatan(Request $request)
    {
        $validatedData = $request->validate([
            'jabatan' => 'required|string|max:200|min:3',
        ]);

        M_Jabatan::create([
            'jabatan' => $request->jabatan,
        ]);

        return redirect()->route("admin.jabatan.jabatan")->with('status', 'Data berhasil ditambahkan');
    }

    public function hapusJabatan($id_jabatan)
    {
        $jabatan = M_Jabatan::find($id_jabatan);
        if (!$jabatan) {
            return redirect()->route('admin.jabatan.jabatan')->with('error', 'Data tidak ditemukan!');
        }

        $jabatan->delete();

        return response()->json(['success' => true]);
    }

    public function updateJabatan(Request $request, $id_jabatan)
    {
        $validatedData = $request->validate([
            'jabatan' => 'required|string|max:200|min:3',
        ]);

        $jabatan = M_Jabatan::find($id_jabatan);
        if (!$jabatan) {
            return redirect()->route('admin.jabatan.jabatan')->with('error', 'Data tidak ditemukan!');
        }

        $jabatan->jabatan = $request->jabatan;
        $jabatan->save();

        return redirect()->route('admin.jabatan.jabatan')->with('success', 'Data berhasil diubah');
    }
}
