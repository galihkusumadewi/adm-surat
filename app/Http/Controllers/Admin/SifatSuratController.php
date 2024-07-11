<?php

namespace App\Http\Controllers\Admin;

use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\M_Sifat_Surat;

class SifatSuratController extends Controller
{
    public function admin_getSifatSurat(Request $request)
    {
        if ($request->ajax()) {
            $data = M_Sifat_Surat::select(['id_sifat', 'sifat_surat']);

            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    $editRoute = route("admin.sifat.update", $row->id_sifat);
                    $deleteRoute = route("admin.sifat.delete", $row->id_sifat);

                    $editBtn = '<a href="#" class="btn btn-primary editBtn" data-id="'.$row->id_sifat.'" data-sifat_surat="'.$row->sifat_surat.'" data-edit-url="'.$editRoute.'">Edit</a>';
                    $deleteBtn = '<button type="button" class="btn btn-danger hapusData" data-id="'.$row->id_sifat.'" data-hapus-url="'.$deleteRoute.'">Hapus</button>';

                    return $editBtn . ' ' . $deleteBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $sifatSurat = M_Sifat_Surat::all();
        return view('admin.sifat_surat', compact('sifatSurat'));
    }

    public function tambahSifat(Request $request)
    {
        $validatedData = $request->validate([
            'sifat_surat' => 'required|string|max:200|min:3',
        ]);

        M_Sifat_Surat::create([
            'sifat_surat' => $request->sifat_surat,
        ]);

        return redirect()->route('admin.sifat.sifat_surat')->with('status', 'Data berhasil ditambahkan');
    }

    public function hapusSifat($id_sifat)
    {
        $sifatSurat = M_Sifat_Surat::findOrFail($id_sifat);
        $sifatSurat->delete();

        return response()->json(['success' => true]);
    }

    public function updateSifat(Request $request, $id_sifat)
    {
        $validatedData = $request->validate([
            'sifat_surat' => 'required|string|max:200|min:3',
        ]);

        $sifatSurat = M_Sifat_Surat::find($id_sifat);

        if (!$sifatSurat) {
            return redirect()->route('admin.sifat.sifat_surat')->with('error', 'Data tidak ditemukan!');
        }

        $sifatSurat->sifat_surat = $request->sifat_surat;
        $sifatSurat->save();

        return redirect()->route('admin.sifat.sifat_surat')->with('success', 'Data berhasil diubah');
    }
}
