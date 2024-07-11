<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // Check user role and redirect based on role
        if ($user->id_jabatan == 1) {
            return redirect()->route('admin.home');
        } elseif ($user->id_jabatan == 2) {
            return redirect()->route('kabag.home');
        } elseif ($user->id_jabatan == 3) {
            return redirect()->route('kadin.home');
        } elseif ($user->id_jabatan == 4) {
            return redirect()->route('sekretaris.home');
        } else {
            // If user role is not recognized, log error and redirect to login with error
            \Log::error('Unrecognized user role: ' . $user->id_jabatan);
            return redirect('/login')->with('error', 'Unauthorized action.');
        }
    }
    

    public function logout(Request $request)
{
    Auth::logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect('/login');
}
}
