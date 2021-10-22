<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // baru

class DashboardController extends Controller
{
    public function index(){
        $data = [];
        $user = Auth::user();
        $data['user'] = $user;

        return view('dashboard.index',$data);
    }
}
