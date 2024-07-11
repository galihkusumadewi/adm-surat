<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\M_Surat_Masuk;
use App\Models\M_Surat_Keluar;
use App\Models\M_Disposisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Hitung jumlah surat masuk, surat keluar, dan disposisi
        $masukCount = M_Surat_Masuk::count();
        $keluarCount = M_Surat_Keluar::count();
        $disposisiCount = M_Disposisi::count();

        // Hitung jumlah surat yang belum didisposisi
        $belumDisposisiCount = $masukCount - $disposisiCount;

        // Kirim data ke view
        return view('home', compact('masukCount', 'keluarCount', 'disposisiCount', 'belumDisposisiCount'));
    }

    public function logout(Request $request)
    {
        \Log::info('Logout request received', $request->all());
        \Log::info('Current URL:', ['url' => url()->current()]);

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        \Log::info('User has been logged out');

        return redirect('/login');
    }

    public function profile()
    {
        return view('page.admin.profile');
    }

    public function updateprofile(Request $request)
    {
        $usr = User::findOrFail(Auth::user()->id);
        if ($request->input('type') == 'change_profile') {
            $this->validate($request, [
                'name' => 'string|max:200|min:3',
                'email' => 'string|min:3|email',
                'user_image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:1024'
            ]);
            $img_old = Auth::user()->user_image;
            if ($request->file('user_image')) {
                # delete old img
                if ($img_old && file_exists(public_path().$img_old)) {
                    unlink(public_path().$img_old);
                }
                $nama_gambar = time() . '_' . $request->file('user_image')->getClientOriginalName();
                $upload = $request->user_image->storeAs('public/admin/user_profile', $nama_gambar);
                $img_old = Storage::url($upload);
            }
            $usr->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'user_image' => $img_old
                ]);
            return redirect()->route('profile')->with('status', 'Perubahan telah tersimpan');
        } elseif ($request->input('type') == 'change_password') {
            $this->validate($request, [
                'password' => 'min:8|confirmed|required',
                'password_confirmation' => 'min:8|required',
            ]);
            $usr->update([
                'password' => Hash::make($request->password)
            ]);
            return redirect()->route('profile')->with('status', 'Perubahan telah tersimpan');
        }
    }
}
