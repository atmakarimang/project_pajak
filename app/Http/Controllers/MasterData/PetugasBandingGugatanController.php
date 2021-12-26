<?php

namespace App\Http\Controllers\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; // baru
use App\Models\DevError;
use App\Models\PetugasSidangBanding;
use App\Models\PelaksanaEksekutor;
use DB;

class PetugasBandingGugatanController extends Controller
{
    protected $PATH_VIEW = 'masterdata.PetugasBandingGugatan.';

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request){
        $data = [];
        $user = Auth::user();
        $mode = $request->mode;
        $data["mode"] = $mode;
        $data['user'] = $user;

        return view($this->PATH_VIEW.'index',$data);
    }
    public function storePetSidang(Request $request){
        $user = Auth::user();
    	$mode = strtolower($request->input("mode"));
    	$results = $request->all();
    	$error = 0;
    	DB::beginTransaction();
    	if($mode=="edit"){
    		$id = $results['id'];
            $data = PetugasSidangBanding::where('id','=',$id)->first();
            $data->nama_petugas = $request->nama_petugas;
            try {
                $data->save();
            }catch(\Exception $e) {
                $devError = new DevError;
                $devError->form = "Update Petugas Sidang Banding";
                $devError->url = $request->path();
                $devError->error = $e;
                $devError->data = json_encode($request->input());
                $devError->created_at = date("Y:m:d H:i:s");
                $devError->save();
                DB::commit();
                $error++;
                DB::rollBack();
                $flashs[] = [
                    'type' => 'error', // option : info, warning, success, error
                    'title' => 'Error',
                    'message' => "Petugas Sidang Banding gagal diupdated!",
                ];
            }
    	}else{
            $check = PetugasSidangBanding::where('nama_petugas',$request->nama_petugas)->count();
            if ($check > 0) {
                $msg[] = [
	                'type' => 'error', // option : info, warning, success, error
	                'title' => 'Error',
	                'message' => "Nama petugas sudah ada!",
	            ];
                return redirect()->back()->with("flashs", $msg);
            }
    		try {
    			$data = PetugasSidangBanding::create($request);
    		}catch(\Exception $e) {
    			$devError = new DevError;
	            $devError->form = "Add Petugas Sidang Banding";
	            $devError->url = $request->path();
	            $devError->error = $e;
	            $devError->data = json_encode($request->input());
	            $devError->created_at = date("Y:m:d H:i:s");
	            $devError->save();
	            DB::commit();
	            $error++;
	            DB::rollBack();
	            $flashs[] = [
	                'type' => 'error', // option : info, warning, success, error
	                'title' => 'Error',
	                'message' => "Petugas Sidang Banding gagal disimpan!",
	            ];
	        }
    	}

    	if($error == 0) {
    		DB::commit();
            if($mode == 'add'){
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => 'Petugas Sidang Banding '.$request->nama_petugas.' telah ditambahkan!',
                ];
            }else{
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => 'Petugas Sidang Banding '.$request->nama_petugas.' telah diperbaharui!',
                ];
            }
        }

        $data["flashs"] = $flashs;
    	// return redirect()->back()->with($data);
        return redirect()->route('ptg_banding.index');
    }
    public function storeEksekutor(Request $request){
        $user = Auth::user();
    	$mode = strtolower($request->input("mode"));
    	$results = $request->all();
    	$error = 0;
    	DB::beginTransaction();
    	if($mode=="edit"){
    		$id = $results['id'];
            $data = PelaksanaEksekutor::where('id','=',$id)->first();
            $data->pelaksana_eksekutor = $request->pel_eksekutor;
            try {
                $data->save();
            }catch(\Exception $e) {
                $devError = new DevError;
                $devError->form = "Update Pelaksana Eksekutor";
                $devError->url = $request->path();
                $devError->error = $e;
                $devError->data = json_encode($request->input());
                $devError->created_at = date("Y:m:d H:i:s");
                $devError->save();
                DB::commit();
                $error++;
                DB::rollBack();
                $flashs[] = [
                    'type' => 'error', // option : info, warning, success, error
                    'title' => 'Error',
                    'message' => "Pelaksana Eksekutor gagal diupdated!",
                ];
            }
    	}else{
            $check = PelaksanaEksekutor::where('pelaksana_eksekutor',$request->pel_eksekutor)->count();
            if ($check > 0) {
                $msg[] = [
	                'type' => 'error', // option : info, warning, success, error
	                'title' => 'Error',
	                'message' => "Nama pelaksana eksekutor sudah ada!",
	            ];
                return redirect()->back()->with("flashs", $msg);
            }
    		try {
    			$data = PelaksanaEksekutor::create($request);
    		}catch(\Exception $e) {
    			$devError = new DevError;
	            $devError->form = "Add Pelaksana Eksekutor";
	            $devError->url = $request->path();
	            $devError->error = $e;
	            $devError->data = json_encode($request->input());
	            $devError->created_at = date("Y:m:d H:i:s");
	            $devError->save();
	            DB::commit();
	            $error++;
	            DB::rollBack();
	            $flashs[] = [
	                'type' => 'error', // option : info, warning, success, error
	                'title' => 'Error',
	                'message' => "Pelaksana Eksekutor gagal disimpan!",
	            ];
	        }
    	}

    	if($error == 0) {
    		DB::commit();
            if($mode == 'add'){
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => 'Pelaksana Eksekutor '.$request->nama_petugas.' telah ditambahkan!',
                ];
            }else{
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => 'Pelaksana Eksekutor '.$request->nama_petugas.' telah diperbaharui!',
                ];
            }
        }

        $data["flashs"] = $flashs;
    	// return redirect()->back()->with($data);
        return redirect()->route('ptg_banding.index');
    }
}
