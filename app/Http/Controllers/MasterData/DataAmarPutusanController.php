<?php

namespace App\Http\Controllers\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; // baru
use App\Models\DevError;
use App\Models\AmarPutusan;
use DB;

class DataAmarPutusanController extends Controller
{
    protected $PATH_VIEW = 'masterdata.DataAmarPutusan.';

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
    public function store(Request $request){
        $user = Auth::user();
    	$mode = strtolower($request->input("mode"));
    	$results = $request->all();
    	$error = 0;
    	DB::beginTransaction();
    	if($mode=="edit"){
    		$id = $results['id'];
            $data = AmarPutusan::where('id','=',$id)->first();
            $data->amar_putusan = $request->amar_putusan;
            try {
                $data->save();
            }catch(\Exception $e) {
                $devError = new DevError;
                $devError->form = "Update Amar Putusan";
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
                    'message' => "Amar Putusan gagal diupdated!",
                ];
            }
    	}else{
            $check = AmarPutusan::where('amar_putusan',$request->amar_putusan)->count();
            if ($check > 0) {
                $msg[] = [
	                'type' => 'error', // option : info, warning, success, error
	                'title' => 'Error',
	                'message' => "Amar Putusan sudah ada!",
	            ];
                return redirect()->back()->with("flashs", $msg);
            }
    		try {
    			$data = AmarPutusan::create($request);
    		}catch(\Exception $e) {
    			$devError = new DevError;
	            $devError->form = "Add Amar Putusan";
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
	                'message' => "Amar Putusan gagal disimpan!",
	            ];
	        }
    	}

    	if($error == 0) {
    		DB::commit();
            if($mode == 'add'){
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => 'Amar Putusan '.$request->amar_putusan.' telah ditambahkan!',
                ];
            }else{
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => 'Amar Putusan '.$request->amar_putusan.' telah diperbaharui!',
                ];
            }
        }

        $data["flashs"] = $flashs;
    	// return redirect()->back()->with($data);
        return redirect()->route('amarputusan.index');
    }
}
