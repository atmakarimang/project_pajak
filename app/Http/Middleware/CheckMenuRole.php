<?php

namespace App\Http\Middleware;

use Closure;
use Redirect;
use Session;
use App\Models\Menu;
use App\Models\MenuRole;
use App\Models\User;

class CheckMenuRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, ...$menuId)
    {
        $Menu = Menu::whereIn('menu_id', $menuId)->first();
        $MenuRole = MenuRole::where('ag_id', Session::get('ag_id'))->whereIn('menu_id', $menuId)->where('role_access', 1)->count();

        if ($MenuRole <= 0) {

            $flashs[] = [
                'type' => 'error',
                'title' => 'Error',
                'message' => 'Anda tidak dapat membuka halaman ' . (empty($Menu) ? 'tersebut' : $Menu->LABELS),
            ];
            session::flash('flashs', $flashs);
            return redirect()->back();
        }

        return $next($request);
    }
}
