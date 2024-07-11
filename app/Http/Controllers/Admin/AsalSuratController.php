<?php

namespace App\Http\Controllers\Admin;

use App\Models\M_Asal_Surat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

class AsalSuratController extends Controller
{

    public function admin_getAsalSurat(Request $request)
    {
        return $this->handleData($request, 'admin');
    }

    private function handleData(Request $request, $role)
    {
        if ($request->ajax()) {
            $data = M_Asal_Surat::select(['id_asal_surat', 'asal_surat'])->get();
            return DataTables::of($data)
                ->addColumn('action', function($row) use ($role) {
                    $deleteUrl = route("admin.asal.delete", $row->id_asal_surat);
                    $editUrl = route("admin.asal.update", $row->id_asal_surat);

                    $deleteBtn = '<button class="btn btn-danger hapusData" data-id="'.$row->id_asal_surat.'" data-hapus-url="'.$deleteUrl.'">Hapus</button>';

                    $editBtn = '<button class="btn btn-primary editData" 
                        data-id="' . $row->id_asal_surat . '" 
                        data-asal_surat="' . $row->asal_surat . '" 
                        data-toggle="modal" data-target="#editAsalModal">Edit</button>';

                    return $deleteBtn . ' ' . $editBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $asalSurat = M_Asal_Surat::all();
        return view($role . '.asal_surat', compact('asalSurat'));
    }

    public function tambahAsal(Request $request)
    {
        $validatedData = $request->validate([
            'asal_surat' => 'required|string|max:200|min:3',
        ]);

        M_Asal_Surat::create([
            'asal_surat' => $request->asal_surat,
        ]);

        return redirect()->route("admin.asal.asal_surat")->with('status', 'Data berhasil ditambahkan');
    }

    public function hapusAsal($id_asal_surat)
    {
        $asalSurat = M_Asal_Surat::find($id_asal_surat);
        if (!$asalSurat) {
            return redirect()->route('admin.asal.asal_surat')->with('error', 'Data tidak ditemukan!');
        }

        $asalSurat->delete();

        return response()->json(['success' => true]);
    }

    public function updateAsal(Request $request, $id_asal_surat)
    {
        $validatedData = $request->validate([
            'asal_surat' => 'required|string|max:200|min:3',
        ]);

        $asalSurat = M_Asal_Surat::find($id_asal_surat);
        if (!$asalSurat) {
            return redirect()->route('admin.asal.asal_surat')->with('error', 'Data tidak ditemukan!');
        }

        $asalSurat->asal_surat = $request->asal_surat;
        $asalSurat->save();

        return redirect()->route('admin.asal.asal_surat')->with('success', 'Data berhasil diubah');
    }
}

