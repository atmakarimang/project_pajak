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

        if (empty(Session::get('user_id'))) {
            return redirect('/login');
        } else {
            //return redirect('/dashboard');
            //return $next($request);
            $link = substr($request->getPathInfo(), 1);
            if ($link == "" || $link == "login") {
                return redirect('/dashboard');
            } else {
                return $next($request);
            }
        }
    }
}
