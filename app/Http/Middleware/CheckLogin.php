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

        // dd($request->route()->parameters());
        if (empty(Session::get('user_id'))) {
            return redirect('/login');
        }

        return $next($request);
    }
}
