<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\LogActivitesController;
use App\Http\Controllers\Master\PenggunaController;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

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
        if ($request->input_type == 'bjsc@mail.com') {
            // login vendor
            $user = User::where('email', $request->input_type)->orWhere('nip', $request->input_type)->first();
            if ($user->nip) {
                $karyawan = $this->penggunaController->getKaryawan($user->nip);
    
                if (gettype($karyawan) == 'string')
                    session(['nama_karyawan' => 'undifined']);
                else {
                    if ($karyawan)
                        if (array_key_exists('nama', $karyawan))
                            session(['nama_karyawan' => $karyawan['nama']]);
                        else
                            session(['nama_karyawan' => 'undifined']);
                }
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
            Session::put(config('global.role_id_session'), $user->role_id);
            Session::put(config('global.user_id_session'), $user->id);
        }
        else {
            $host = env('LOS_API_HOST');
            if ($host) {
                $apiURL = $host . '/login';
        
                $headers = [
                    'token' => env('LOS_API_TOKEN')
                ];

                try {
                    $response = Http::timeout(3)
                                    ->withHeaders($headers)
                                    ->withOptions(['verify' => false])
                                    ->post($apiURL, [
                                        'email' => $request->input_type,
                                        'password' => $request->password,
                                    ]);
                    $responseBody = json_decode($response->getBody(), true);

                    if (array_key_exists('status', $responseBody)) {
                        if ($responseBody['status'] == 'berhasil') {
                            Session::put(config('global.auth_session'), $responseBody);
                            $role_id = $responseBody['role'] == 'Administrator' ? 4 : 2;
                            Session::put(config('global.role_id_session'), $role_id);
                            Session::put(config('global.user_id_session'), $responseBody['id']);
                            Session::put(config('global.user_nip_session'), $responseBody['data']['nip']);
                            Session::put(config('global.user_name_session'), $responseBody['data']['nama']);
                            Session::put(config('global.user_token_session'), $responseBody['access_token']);

                            return redirect()->route('dashboard');
                        }
                        else
                            return back()->withError($responseBody['message']);
                    }
                    else
                        return back()->withError('Terjadi kesalahan');
                } catch (\Illuminate\Http\Client\ConnectionException $e) {
                    return $e->getMessage();
                    return back()->withError('Terjadi kesalahan. '.$e->getMessage());
                }
            }
            else {
                return back()->withError('Host api belum diatur');
            }
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        $token = \Session::get(config('global.user_token_session'));
        if ($token) {
            $host = env('LOS_API_HOST');
            if ($host) {
                $apiURL = $host . '/logout';
                $headers = [
                    'token' => env('LOS_API_TOKEN'),
                    'Authorization' => "Bearer $token",
                ];
    
                try {
                    $response = Http::timeout(3)
                                    ->withHeaders($headers)
                                    ->withOptions(['verify' => false])
                                    ->post($apiURL);
                    $responseBody = json_decode($response->getBody(), true);
    
                    if (array_key_exists('message', $responseBody)) {
                        if ($responseBody['message'] == 'Successfully logged out') {
                            Session::flush();
                        }
                        else
                            return back()->withError('Terjadi kesalahan');
                    }
                    else
                        return back()->withError('Terjadi kesalahan');
                } catch (\Illuminate\Http\Client\ConnectionException $e) {
                    return back()->withError('Terjadi kesalahan. '.$e->getMessage());
                }
            }
            else {
                return back()->withError('Host api belum diatur');
            }
        }
        else {
            $user = Auth::guard('web')->user()->nip != null ? Auth::guard('web')->user()->email . " (" . Auth::guard('web')->user()->nip . ")" : Auth::guard('web')->user()->email;
            $this->logActivity->store("Pengguna '$user' melakukan log out.");
            Auth::guard('web')->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();
        }

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
