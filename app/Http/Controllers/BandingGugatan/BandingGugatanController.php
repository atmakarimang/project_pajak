<?php

namespace App\Http\Controllers\BandingGugatan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; // baru
use App\Models\DevError;
use App\Models\Project;
use App\Models\AsalPermohonan;
use App\Models\JenisPermohonan;
use App\Models\KategoriPermohonan;

use App\Models\Pajak;
use App\Models\AmarPutusan;
use App\Models\PetugasSidangBanding;
use App\Models\PelaksanaEksekutor;
use DB;

class BandingGugatanController extends Controller
{
    protected $PATH_VIEW = 'bandingGugatan.';

    public function __construct()
    {
        // $this->middleware('auth');
    }
    public function index(Request $request){
        $data = [];
        $user = Auth::user();
        $mode = $request->mode;
        $dtPajak = Pajak::get();
        $dtAmarPutusan = AmarPutusan::get();
        $dtPtgSidang = PetugasSidangBanding::get();
        $dtPkEksekutor = PelaksanaEksekutor::get();
        
        $data["mode"] = $mode;
        $data['user'] = $user;
        $data['dtPajak'] = $dtPajak;
        $data['dtAmarPutusan'] = $dtAmarPutusan;
        $data['dtPtgSidang'] = $dtPtgSidang;
        $data['dtPkEksekutor'] = $dtPkEksekutor;

        return view($this->PATH_VIEW.'index',$data);
    }
    public function storePemohon(Request $request){
        $user = Auth::user();
    	$mode = strtolower($request->input("mode"));
    	$results = $request->all();
    	$error = 0;
    	DB::beginTransaction();
    	if($mode=="edit"){
    		$id_project = $results['id_project'];
            $project = Project::where('id_project','=',$id_project)->first();
            $project->name = $request->cat_name;
            $project->description = $request->cat_description;
            $project->updated_at = date('Y-m-d H:i:s');
            try {
                $project->save();
            }catch(\Exception $e) {
                $devError = new DevError;
                $devError->form = "Update Project";
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
                    'message' => "Project gagal diupdated!",
                ];
            }
    	}else{
    		try {
    			$data = AsalPermohonan::create($request);
    		}catch(\Exception $e) {
    			$devError = new DevError;
	            $devError->form = "Add Asal Pemohon";
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
	                'message' => "Project gagal disimpan!",
	            ];
	        }
    	}

    	if($error == 0) {
    		DB::commit();
            if($mode == 'add'){
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => 'Project baru '.$request->cat_name.' telah ditambahkan!',
                ];
            }else{
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => 'Project '.$id_cat.' telah diupdated!',
                ];
            }
        }

        $data["flashs"] = $flashs;
    	// return redirect()->back()->with($data);
        return redirect()->route('permohonan.index');
    }
    public function storeJP(Request $request){
        $user = Auth::user();
    	$mode = strtolower($request->input("mode"));
    	$results = $request->all();
    	$error = 0;
    	DB::beginTransaction();
    	if($mode=="edit"){
    		$id_project = $results['id_project'];
            $project = Project::where('id_project','=',$id_project)->first();
            $project->name = $request->cat_name;
            $project->description = $request->cat_description;
            $project->updated_at = date('Y-m-d H:i:s');
            try {
                $project->save();
            }catch(\Exception $e) {
                $devError = new DevError;
                $devError->form = "Update Project";
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
                    'message' => "Project gagal diupdated!",
                ];
            }
    	}else{
    		try {
    			$data = JenisPermohonan::create($request);
    		}catch(\Exception $e) {
    			$devError = new DevError;
	            $devError->form = "Add Jenis Pemohon";
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
	                'message' => "Project gagal disimpan!",
	            ];
	        }
    	}

    	if($error == 0) {
    		DB::commit();
            if($mode == 'add'){
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => 'Project baru '.$request->cat_name.' telah ditambahkan!',
                ];
            }else{
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => 'Project '.$id_cat.' telah diupdated!',
                ];
            }
        }

        $data["flashs"] = $flashs;
    	// return redirect()->back()->with($data);
        return redirect()->route('permohonan.index');
    }
    public function storeKP(Request $request){
        $user = Auth::user();
    	$mode = strtolower($request->input("mode"));
    	$results = $request->all();
    	$error = 0;
    	DB::beginTransaction();
    	if($mode=="edit"){
    		$id_project = $results['id_project'];
            $project = Project::where('id_project','=',$id_project)->first();
            $project->name = $request->cat_name;
            $project->description = $request->cat_description;
            $project->updated_at = date('Y-m-d H:i:s');
            try {
                $project->save();
            }catch(\Exception $e) {
                $devError = new DevError;
                $devError->form = "Update Project";
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
                    'message' => "Project gagal diupdated!",
                ];
            }
    	}else{
    		try {
    			$data = KategoriPermohonan::create($request);
    		}catch(\Exception $e) {
    			$devError = new DevError;
	            $devError->form = "Add Kategori Permohonan";
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
	                'message' => "Kategori Permohonan gagal disimpan!",
	            ];
	        }
    	}

    	if($error == 0) {
    		DB::commit();
            if($mode == 'add'){
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => 'Kategori Permohonan '.$request->kat_pemohon.' telah ditambahkan!',
                ];
            }else{
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => 'Kategori Permohonan '.$request->kat_pemohon.' telah diupdated!',
                ];
            }
        }

        $data["flashs"] = $flashs;
    	// return redirect()->back()->with($data);
        return redirect()->route('permohonan.index');
    }
}
