<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Controller;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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
            return redirect('/login');
        }
        // Check session in LOS database
        $controller = new Controller;
        $loginSession = $controller->serverSessionCheck();
        if ($loginSession['status'] == 'berhasil') {
            $server_session = $controller->serverSessionCheck();
            if ($server_session['status'] == 'gagal') {
                return redirect('/login')->withError('Sesi Anda telah berakhir.');
            }
        }
        
        return $next($request);
    }
}
