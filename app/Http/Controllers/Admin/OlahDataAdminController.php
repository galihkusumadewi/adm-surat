<?php 

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\M_Jabatan;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class OlahDataAdminController extends Controller
{
    public function getAdmin(Request $request)
    {
        return $this->handleData($request, 1);
    }

    public function getKabag(Request $request)
    {
        return $this->handleData($request, 2);
    }

    private function handleData(Request $request, $role)
    {
        if ($request->ajax()) {
            $data = User::with('jabatan')->select(['id', 'id_jabatan', 'name', 'username', 'email', 'password']);
            return DataTables::of($data)
                ->addColumn('action', function($row) use ($role) {
                    $deleteUrl = $role === 1 ? 
                        route('admin.data_admin.delete', $row->id) :
                        route('kabag.data_admin.delete', $row->id);

                    $editUrl = $role === 1 ? 
                        route('admin.data_admin.update', $row->id) :
                        route('kabag.data_admin.update', $row->id);

                    // $detailUrl = $role === 1 ? 
                    //     route('admin.data_admin.detail', $row->id) :
                    //     route('kabag.data_admin.detail', $row->id);

                    $deleteBtn = '<button class="btn btn-danger hapusData" data-id="'.$row->id.'" data-hapus-url="'.$deleteUrl.'">Hapus</button>';

                    $editBtn = '<button class="btn btn-primary editData" 
                        data-id="' . $row->id . '" 
                        data-name="' . $row->name . '" 
                        data-email="' . $row->email . '" 
                        data-id_jabatan="'. $row->id_jabatan . '"
                        data-password="'. $row->password . '"
                        data-username="'. $row->username . '"
                        data-toggle="modal" data-target="#editAdminModal">Edit</button>';

                    // $detailBtn = '<a href="'.$detailUrl.'" class="btn btn-info">Detail</a>';

                    return $deleteBtn . ' ' . $editBtn ;
                })
                ->addColumn('jabatan', function($row) {
                    return $row->jabatan->jabatan ?? 'jabatan tidak ditemukan';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $admin = User::with('jabatan')->get();
        $jabatan = M_Jabatan::all();

        if ($role === 1) {
            return view('admin.olah_data_admin', compact('admin', 'jabatan'));
        } elseif ($role === 2) {
            return view('kabag.olah_data_admin', compact('admin', 'jabatan'));
        } 

        return abort(403, 'Unauthorized action.');
    }

    public function tambahAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string',
            'username' => 'required|string',
            'id_jabatan' => 'required|exists:jabatan,id_jabatan',
        ]);

        $admin = new User();
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->password = Hash::make($request->password);
        $admin->username = $request->username;
        $admin->id_jabatan = $request->id_jabatan;
        $admin->save();

        return response()->json(['message' => 'Data berhasil disimpan'], 200);
    }

    public function hapusAdmin($id)
    {
        $admin = User::where('id', $id)->first();

        if (!$admin) {
            return response()->json(['message' => 'Data tidak ditemukan!'], 404);
        }

        $admin->delete();
        return response()->json(['message' => 'Data berhasil dihapus'], 200);
    }

    public function updateAdmin(Request $request, $id)
{
    Log::info('Received data:', $request->all());

    $validatedData = $request->validate([
        'name' => 'required|string',
        'email' => 'required|email',
        'password' => 'required|string',
        'username' => 'required|string',
        'id_jabatan' => 'required|exists:jabatan,id_jabatan',
    ]);

    $admin = User::find($id);

    if (!$admin) {
        return response()->json(['message' => 'Data tidak ditemukan!'], 404);
    }

    $admin->name = $validatedData['name'];
    $admin->email = $validatedData['email'];
    $admin->password = Hash::make($validatedData['password']);
    $admin->username = $validatedData['username'];
    $admin->id_jabatan = $validatedData['id_jabatan'];
    $admin->save();

    return response()->json(['message' => 'Data berhasil diperbarui'], 200);
}

    public function showDetail($id)
    {
        $admin = User::findOrFail($id);

        $role = auth()->user()->id_jabatan;
        if ($role === 1) {
            return view('admin.olah_data_admin', compact('admin', 'jabatan'));
        } elseif ($role === 2) {
            return view('kabag.olah_data_admin', compact('admin', 'jabatan'));
        } 

        return abort(403, 'Unauthorized action.');
    }
}
