<?php

namespace App\Http\Middleware;

use Closure;
use Redirect;
use Session;

class CheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $request->route();

        //dd($request->route()->parameters());
        //dd(Session::get('user_id'));
        //dd($request->route());

        $link = substr($request->getPathInfo(), 1);

        if (empty(Session::get('user_id'))) {
            //Cek jika akses register akun maka diperbolehkan
            if ($link == "daftar-akun" || $link == "daftar-akun/create") {
                return $next($request);
            } else {
                return redirect('/login');
            }
        } else {
            $link = substr($request->getPathInfo(), 1);
            if ($link == "" || $link == "login" || $link == "daftar-akun" || $link == "daftar-akun/create") {
                return redirect('/dashboard');
            } else {
                return $next($request);
            }
        }
    }
}
