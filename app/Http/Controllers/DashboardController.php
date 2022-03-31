<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // baru
use App\Models\User;
use App\Models\JenisPermohonan;

class DashboardController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    // public function index(){
    //     $data = [];
    //     $user = Auth::user();
    //     $user_id = session('user_id');

    //     $data['user'] = $user;

    //     return view('dashboard.index',$data);
    // }

    public function index()
    {
        $permohonan = JenisPermohonan::get()->toArray();

        $permohonanid = [];
        foreach ($permohonan as $key => $value) {
            //$years .= $permohonan[$key]['id'];
            array_push($permohonanid, $permohonan[$key]['jenis_permohonan']);
        }

        $year = ['2015', '2016', '2017', '2018', '2019', '2020'];

        //print_r($year);

        $user = [10, 20, 30, 40, 30, 60];
        // foreach ($year as $key => $value) {
        //     $user[] = User::where(\DB::raw("DATE_FORMAT(created_at, '%Y')"),$value)->count();
        // }

        return view('dashboard.index')->with('year', json_encode($permohonanid, JSON_NUMERIC_CHECK))->with('user', json_encode($user, JSON_NUMERIC_CHECK));
    }
}
