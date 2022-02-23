<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; // baru
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Session;

class LoginController extends Controller
{
    public function index(){
		if (Auth::check()) {
            // return redirect()->route('dashboard');
            return redirect()->route('pelaksanabidang.index');
        }
    	return view('Auth.login');
    }
    public function showLoginForm()
    {
      return view('Auth.login');
    }
    public function proses_login(Request $request){
        $user_id = $request->user_id;
        $password = $request->password;
        if(!empty(session('user_id'))) {
            // return redirect()->route('dashboard');
            return redirect()->route('pelaksanabidang.index');
        }

        if(empty($user_id) || empty($password)) {
            return view('Auth.login');
        }

        $user = User::where("user_id", '=',$user_id)
                    ->where("password", '=',md5($password))
                    ->first();
        if(!empty($user)) {
            session(['user_id' => $user->user_id]);
            // return redirect()->route('dashboard');
            return redirect()->route('pelaksanabidang.index');
        } else {
            Session::flash('error', 'User id atau password salah!');
            return redirect()->back();
        }
    }
    public function logout(Request $request){
    	$request->session()->flush();
    	Auth::logout();
    	return redirect('login');
    }
}
