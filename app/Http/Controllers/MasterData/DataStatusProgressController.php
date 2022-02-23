<?php

namespace App\Http\Controllers\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; // baru
use App\Models\DevError;
use App\Models\Status;
use App\Models\Progress;
use DB;

class DataStatusProgressController extends Controller
{
    protected $PATH_VIEW = 'masterdata.DataStatusProgress.';

    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    public function index(Request $request){
        $data = [];
        $user = Auth::user();
        $mode = $request->mode;
        $data["mode"] = $mode;
        $data['user'] = $user;

        return view($this->PATH_VIEW.'index',$data);
    }
    public function storeStatus(Request $request){
        $user = Auth::user();
    	$mode = strtolower($request->input("mode"));
    	$results = $request->all();
    	$error = 0;
        $cek_data = Status::where('status',$request->status)->count();
        if($cek_data>0){
            $error_duplikat[] = [
                'type' => 'success', // option : info, warning, success, error
                'title' => 'Success',
                'message' => 'Status '.$request->status.' sudah ada!',
            ];
            $data["error_duplikat"] = $error_duplikat;
            // return redirect()->back()->with($data);
            return redirect()->back();
        }
    	DB::beginTransaction();
    	if($mode=="edit"){
    		$id = $request->id_status;
            $dt = Status::where('id',$id)->first();
            try {
                $data = Status::updateDt($request,$dt);
            }catch(\Exception $e) {
                $devError = new DevError;
                $devError->form = "Update Status";
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
                    'message' => "Status gagal diupdated!",
                ];
            }
    	}else{
    		try {
    			$data = Status::create($request);
    		}catch(\Exception $e) {
    			$devError = new DevError;
	            $devError->form = "Add Status";
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
	                'message' => "Status gagal disimpan!",
	            ];
	        }
    	}

    	if($error == 0) {
    		DB::commit();
            if($mode == 'add'){
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => 'Status '.$request->status.' telah ditambahkan!',
                ];
            }else{
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => 'Status '.$request->status.' telah diperbaharui!',
                ];
            }
        }

        $data["flashs"] = $flashs;
    	// return redirect()->back()->with($data);
        return redirect()->route('stapro.index');
    }
    public function ajaxDataSt(Request $request){
        $start = $request->input('start');
        $length = $request->input('length');
        $draw = $request->input('draw');
        $search_arr = $request->input('search');
        $search = $search_arr['value'];
        $start = ($request->input('start') ? $request->input('start') : 0);
        $length = ($request->input('length') ? $request->input('length') : 10);
        $order_arr = $request->input('order');
        $order_arr = $order_arr[0];
        $orderByColumnIndex = $order_arr['column']; // index of the sorting column (0 index based - i.e. 0 is the first record)
        $orderType = $order_arr['dir']; // ASC or DESC
        $orderBy = $request->input('columns');
        $orderBy = $orderBy[$orderByColumnIndex]['name'];//Get name of the sorting column from its index

        if ($orderBy && $orderType) {
            $orderBy = $orderBy;
            $orderType = $orderType;
        } else {
            $orderBy = 'id';
            $orderType = 'DESC';
        }

        $limit = $length;
        $offset = $start;
        $jumlahTotal = Status::count();
        
        if ($search) { // filter data
            $where = "status like lower('%{$search}%')";
            $jumlahFiltered = Status::whereRaw("{$where}")->orderBy($orderBy, $orderType)
                ->count(); //hitung data yang telah terfilter
            $data = Status::whereRaw($where)
                ->orderBy($orderBy, $orderType)
                ->offset($offset)
                ->limit($limit)
                ->get();
        } else {
            $jumlahFiltered = $jumlahTotal;
            $data = Status::orderBy($orderBy, $orderType)
                ->offset($offset)
                ->limit($limit)
                ->get();
        }

        $result = [];

        foreach ($data as $no => $dt) {
            $linkdelete = url('master-data/deleteSt', $dt->id);
            $action = '<center>
                                <div class="btn-group btn-group-sm">
                            <button data-toggle="tooltip" data-original-title="Edit" type="button" class="btn btn-xs btn-primary btn-circle" onclick="editSt(\''.$dt->id.'\')"><i class="fas fa-pencil-alt"></i></button>
                            <button onclick="buttonDeleteSt(this)" data-link="'.$linkdelete.'" data-toggle="tooltip" data-original-title="Delete" type="button" class="btn btn-xs btn-danger btn-circle"><i class="fas fa-trash"></i></button>
                            </div>
                            </center>';
            $result[] = [
                $start + $no + 1,
                $dt->status,
                $action,
            ];
        }
        echo json_encode(
            array('draw' => $draw,
                'recordsTotal' => $jumlahTotal,
                'recordsFiltered' => $jumlahFiltered,
                'data' => $result,
            )
        );
    }
    public function editSt(Request $request)
    {
        $id = $request->id;
        $dt = Status::where('id',$id)->first();

        echo json_encode($dt);
    }
    public function deleteSt($id){
        Status::where('id',$id)->delete();
        return redirect()->back();
    }

    public function storeProgress(Request $request){
        $user = Auth::user();
    	$mode = strtolower($request->input("mode"));
    	$results = $request->all();
    	$error = 0;
        $cek_data = Progress::where('progress',$request->progress)->count();
        if($cek_data>0){
            $error_duplikat[] = [
                'type' => 'success', // option : info, warning, success, error
                'title' => 'Success',
                'message' => 'Progress '.$request->progress.' sudah ada!',
            ];
            $data["error_duplikat"] = $error_duplikat;
            // return redirect()->back()->with($data);
            return redirect()->back();
        }

    	DB::beginTransaction();
    	if($mode=="edit"){
    		$id = $request->id_progress;
            $dt = Progress::where('id',$id)->first();
            try {
                $data = Progress::updateDt($request,$dt);
            }catch(\Exception $e) {
                $devError = new DevError;
                $devError->form = "Update Progress";
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
                    'message' => "Progress gagal diupdated!",
                ];
            }
    	}else{
    		try {
    			$data = Progress::create($request);
    		}catch(\Exception $e) {
    			$devError = new DevError;
	            $devError->form = "Add Progress";
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
	                'message' => "Progress gagal disimpan!",
	            ];
	        }
    	}

    	if($error == 0) {
    		DB::commit();
            if($mode == 'add'){
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => 'Progress '.$request->progress.' telah ditambahkan!',
                ];
            }else{
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => 'Progress '.$request->progress.' telah diperbaharui!',
                ];
            }
        }

        $data["flashs"] = $flashs;
    	// return redirect()->back()->with($data);
        return redirect()->route('stapro.index');
    }
    public function ajaxDataPr(Request $request){
        $start = $request->input('start');
        $length = $request->input('length');
        $draw = $request->input('draw');
        $search_arr = $request->input('search');
        $search = $search_arr['value'];
        $start = ($request->input('start') ? $request->input('start') : 0);
        $length = ($request->input('length') ? $request->input('length') : 10);
        $order_arr = $request->input('order');
        $order_arr = $order_arr[0];
        $orderByColumnIndex = $order_arr['column']; // index of the sorting column (0 index based - i.e. 0 is the first record)
        $orderType = $order_arr['dir']; // ASC or DESC
        $orderBy = $request->input('columns');
        $orderBy = $orderBy[$orderByColumnIndex]['name'];//Get name of the sorting column from its index

        if ($orderBy && $orderType) {
            $orderBy = $orderBy;
            $orderType = $orderType;
        } else {
            $orderBy = 'id';
            $orderType = 'DESC';
        }

        $limit = $length;
        $offset = $start;
        $jumlahTotal = Progress::count();
        
        if ($search) { // filter data
            $where = "progress like lower('%{$search}%')";
            $jumlahFiltered = Progress::whereRaw("{$where}")->orderBy($orderBy, $orderType)
                ->count(); //hitung data yang telah terfilter
            $data = Progress::whereRaw($where)
                ->orderBy($orderBy, $orderType)
                ->offset($offset)
                ->limit($limit)
                ->get();
        } else {
            $jumlahFiltered = $jumlahTotal;
            $data = Progress::orderBy($orderBy, $orderType)
                ->offset($offset)
                ->limit($limit)
                ->get();
        }

        $result = [];

        foreach ($data as $no => $dt) {
            $linkdelete = url('master-data/deletePr', $dt->id);
            $action = '<center>
                                <div class="btn-group btn-group-sm">
                            <button data-toggle="tooltip" data-original-title="Edit" type="button" class="btn btn-xs btn-info btn-circle" onclick="editPr(\''.$dt->id.'\')"><i class="fas fa-pencil-alt"></i></button>
                            <button onclick="buttonDeletePr(this)" data-link="'.$linkdelete.'" data-toggle="tooltip" data-original-title="Delete" type="button" class="btn btn-xs btn-danger btn-circle"><i class="fas fa-trash"></i></button>
                            </div>
                            </center>';
            $result[] = [
                $start + $no + 1,
                $dt->progress,
                $action,
            ];
        }
        echo json_encode(
            array('draw' => $draw,
                'recordsTotal' => $jumlahTotal,
                'recordsFiltered' => $jumlahFiltered,
                'data' => $result,
            )
        );
    }
    public function editPr(Request $request)
    {
        $id = $request->id;
        $dt = Progress::where('id',$id)->first();

        echo json_encode($dt);
    }
    public function deletePr($id){
        Progress::where('id',$id)->delete();
        return redirect()->back();
    }
}
