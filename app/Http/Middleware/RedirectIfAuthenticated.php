<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $token = Session::get(config('global.user_token_session'));
        if ($token) {
            $controller = new Controller;
            $app_session = $controller->getLoginSession();
            
            if ($app_session['status'] == 'berhasil') {
                $server_session = $controller->serverSessionCheck();
                if ($server_session['status'] == 'sukses') {
                    return redirect(RouteServiceProvider::HOME);
                }
                // else {
                //     return redirect('/login');
                // }
            }
        }
        else {
            // Check laravel application session
            $guards = empty($guards) ? [null] : $guards;

            foreach ($guards as $guard) {
                if (Auth::guard($guard)->check()) {
                    return redirect(RouteServiceProvider::HOME);
                }
            }
        }

        return $next($request);
    }
}
