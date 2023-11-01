<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Controller;
use App\Repository\UserTokenRepository;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

use function PHPUnit\Framework\matches;

class AuthWithApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
    
        // Check session in laravel application
        if (!Session::has(config('global.auth_session'))) {
            // api token null
            
            $token = isset($_COOKIE[config('global.user_token_session')]) ? $_COOKIE[config('global.user_token_session')] : '';
            // dd($token);

            
            if (!$token) {
                // logout from LOS sistem when session lifetime already expired
                $this->logout($token);
            }

            $userToken = UserTokenRepository::userByToken($token);

            $user = array_merge(collect($userToken)->toArray(), ['status' => 'berhasil']); 

            $user['data']['nip'] = $userToken->nip;
            $user['kode_cabang'] = $userToken->id_cabang;
            $user['data']['nama'] = $userToken->name;
        
            if($userToken){
                $role_id = match($userToken->role){
                    "Administrator" => 4,
                    "Kredit Umum" => 1,
                    default => 2,
                }; 
                Session::put(config('global.user_token_session'), $token);
                Session::put(config('global.auth_session'), $user);
                Session::put(config('global.role_id_session'), $role_id);
                Session::put(config('global.user_id_session'), $userToken->id);
                Session::put(config('global.user_nip_session'), $userToken->nip);
                Session::put(config('global.user_name_session'), $userToken->name);
                Session::put(config('global.user_role_session'), $userToken->role);
                Session::put(config('global.user_kode_cabang_session'),  $user['kode_cabang']);
                
                // dd(Session::get(config('global.user_role_session')));

            }
            if(!$userToken){
                $this->logout($token);
            }
            
            
            if (!Session::has('user_id_session')) {
                return redirect()->route('login');
            }
        }
        else {
            // Check session in LOS database
            $controller = new Controller;
            $loginSession = $controller->serverSessionCheck();
            if ($loginSession['status'] == 'berhasil') {
                $server_session = $controller->serverSessionCheck();
                if ($server_session['status'] == 'gagal') {
                    return redirect('/login')->withError('Sesi Anda telah berakhir.');
                }
            }
        }
        
        return $next($request);
    }

    private function logout($token) {
        if ($token) {
            $host = env('LOS_API_HOST');
            if ($host) {
                $apiURL = $host . '/logout';
                $headers = [
                    'token' => env('LOS_API_TOKEN'),
                    'Authorization' => "Bearer $token",
                ];

                try {
                    $response = Http::withHeaders($headers)
                                    ->withOptions(['verify' => false])
                                    ->post($apiURL);
                    $responseBody = json_decode($response->getBody(), true);

                    if ($responseBody) {
                        if (array_key_exists('message', $responseBody)) {
                            if ($responseBody['message'] == 'Successfully logged out') {
                                Session::flush();
                                setcookie(config('global.user_token_session'), ''); // remove token from cookie

                                return redirect()->route('login');
                            }
                        }
                    }
                } catch (\Illuminate\Http\Client\ConnectionException $e) {
                    
                }
            }
        }
    }
}
