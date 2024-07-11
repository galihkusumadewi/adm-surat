<?php

namespace App\Http\Controllers\Admin;

use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use App\Models\M_Jenis_Surat;
use Illuminate\Routing\Controller;

class JenisSuratController extends Controller
{
    public function admin_getJenisSurat(Request $request)
    {
        return $this->handleData($request, 1);
    }

    public function kabag_getJenisSurat(Request $request)
    {
        return $this->handleData($request, 2);
    }

    public function kadin_getJenisSurat(Request $request)
    {
        return $this->handleData($request, 3);
    }
    
    public function handleData(Request $request, $role)
    {
        if ($request->ajax()) {
            $data = M_Jenis_Surat::select(['id_jenis', 'jenis_surat']);

            return DataTables::of($data)
                ->addColumn('action', function($row) use ($role) {
                    $deleteUrl = $role === 1 ? 
                        route('admin.jenis.delete', $row->id_jenis) :
                        ($role === 2 ? route('kabag.jenis.delete', $row->id_jenis) : route('kadin.jenis.delete', $row->id_jenis));

                    $editUrl = $role === 1 ? 
                        route('admin.jenis.update', $row->id_jenis) :
                        ($role === 2 ? route('kabag.jenis.update', $row->id_jenis) : route('kadin.jenis.update', $row->id_jenis));

                    $deleteBtn = '<button class="btn btn-danger hapusData" data-id="'.$row->id_jenis.'" data-hapus-url="'.$deleteUrl.'">Hapus</button>';

                    $editBtn = '<button class="btn btn-primary editData" 
                        data-id="' . $row->id_jenis . '" 
                        data-jenis_surat="' . $row->jenis_surat . '" 
                        data-toggle="modal" data-target="#editJenisModal">Edit</button>';


                    return $deleteBtn . ' ' . $editBtn ;
                })
                ->rawColumns(['action'])
                ->make(true); 
        }

        $jenisSurat = M_Jenis_Surat::all();

        if ($role === 1) {
            return view('admin.jenis_surat', compact('jenisSurat'));
        } elseif ($role === 2) {
            return view('kabag.jenis_surat', compact('jenisSurat'));
        } elseif ($role === 3) {
            return view('kadin.jenis_surat', compact('jenisSurat'));
        } 

        return abort(403, 'Unauthorized action.');
    }

    public function tambahJenis(Request $request)
    {
        if ($request->isMethod('post')) {
            $validatedData = $request->validate([
                'jenis_surat' => 'required|string|max:200|min:3',
            ]);

            M_Jenis_Surat::create([
                'jenis_surat' => $request->jenis_surat,
            ]);

            $role = auth()->user()->role;
            switch ($role) {
                case 'admin':
                    return redirect()->route('admin.jenis.jenis_surat')->with('status', 'Data berhasil ditambahkan');
                case 'kabag':
                    return redirect()->route('kabag.jenis.jenis_surat')->with('status', 'Data berhasil ditambahkan');
                case 'kadin':
                    return redirect()->route('kadin.jenis.jenis_surat')->with('status', 'Data berhasil ditambahkan');
            }
        }
    }


    public function hapusJenis($id_jenis)
    {
        $jenisSurat = M_Jenis_Surat::findOrFail($id_jenis);
        $jenisSurat->delete();

        return response()->json(['success' => true]);
    }

    public function updateJenis(Request $request, $id_jenis)
    {
        $validatedData = $request->validate([
            'jenis_surat' => 'required|string|max:200|min:3',
        ]);

        $jenisSurat = M_Jenis_Surat::find($id_jenis);

        if (!$jenisSurat) {
            switch (auth()->user()->role) {
                case 'admin':
                    return redirect()->route('admin.jenis.jenis_surat')->with('error', 'Data tidak ditemukan!');
                case 'kabag':
                    return redirect()->route('kabag.jenis.jenis_surat')->with('error', 'Data tidak ditemukan!');
                case 'kadin':
                    return redirect()->route('kadin.jenis.jenis_surat')->with('error', 'Data tidak ditemukan!');
                default:
                    return redirect()->back()->with('error', 'Data tidak ditemukan!');
            }
        }

        $jenisSurat->jenis_surat = $request->jenis_surat;
        $jenisSurat->save();

        switch (auth()->user()->role) {
            case 'admin':
                return redirect()->route('admin.jenis.jenis_surat')->with('success', 'Data berhasil diubah');
            case 'kabag':
                return redirect()->route('kabag.jenis.jenis_surat')->with('success', 'Data berhasil diubah');
            case 'kadin':
                return redirect()->route('kadin.jenis.jenis_surat')->with('success', 'Data berhasil diubah');
            default:
                return redirect()->back()->with('success', 'Data berhasil diubah!');
        }
    }


}

