<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\LogActivitesController;
use App\Http\Controllers\Master\PenggunaController;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    private $penggunaController;
    private $logActivity;

    function __construct()
    {
        $this->penggunaController = new PenggunaController;
        $this->logActivity = new LogActivitesController;
    }
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $user = User::where('email', $request->input_type)->orWhere('nip', $request->input_type)->first();
        if ($user->nip) {
            $karyawan = $this->penggunaController->getKaryawan($user->nip);
            if ($karyawan)
                session(['nama_karyawan' => $karyawan['nama_karyawan']]);
        }
        if ($user->role_id != 4) {
            if ($user->first_login == true) {
                return redirect('first-login?id=' . $user->id);
            } else {
                $request->authenticate();

                $request->session()->regenerate();

                $this->logActivity->store("Pengguna '$request->input_type' melakukan log in.");

                return redirect()->intended(RouteServiceProvider::HOME);
            }
        } else {
            $request->authenticate();

            $request->session()->regenerate();

            $this->logActivity->store("Pengguna '$request->input_type' melakukan log in.");

            return redirect()->intended(RouteServiceProvider::HOME);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::guard('web')->user()->nip != null ? Auth::guard('web')->user()->email . " (" . Auth::guard('web')->user()->nip . ")" : Auth::guard('web')->user()->email;
        $this->logActivity->store("Pengguna '$user' melakukan log out.");
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();


        return redirect('/');
    }

    public function firstLogin()
    {
        return view('auth.first-login');
    }

    public function firstLoginStore(Request $request)
    {
        $user = User::find($request->id);
        $user->password = Hash::make($request->password);
        $user->first_login = false;
        $user->save();

        return redirect('login');
    }
}
