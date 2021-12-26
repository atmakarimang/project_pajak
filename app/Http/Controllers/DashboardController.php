<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // baru
use App\Models\User;

class DashboardController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    public function index(){
        $data = [];
        $user = Auth::user();
        $user_id = session('user_id');
        
        $data['user'] = $user;

        return view('dashboard.index',$data);
    }
}
