<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // baru
use App\Models\DevError;
use App\Models\Project;

class CrudController extends Controller
{
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

        return view('crud.index',$data);
    }
    public function store(Request $request){
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
    			$project = Project::create($request);
    		}catch(\Exception $e) {
    			$devError = new DevError;
	            $devError->form = "Add Project";
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
    	return redirect()->back()->with($data);
    }
}
