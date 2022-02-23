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
    public function storeAP(Request $request){
        $user = Auth::user();
    	$mode = strtolower($request->input("mode"));
    	$results = $request->all();
    	$error = 0;
        $check = AmarPutusan::where('amar_putusan',$request->amar_putusan)->count();
        if ($check > 0) {
            $msg[] = [
	            'type' => 'error', // option : info, warning, success, error
	            'title' => 'Error',
	            'message' => "Amar Putusan '.$request->amar_putusan.' sudah ada!",
	        ];
            return redirect()->back()->with("flashs", $msg);
        }
    	DB::beginTransaction();
    	if($mode=="edit"){
    		$id = $request->id_amar_putusan;
            $dt = AmarPutusan::where('id',$id)->first();
            try {
                $data = AmarPutusan::updateDt($request,$dt);
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
                    'message' => 'Amar Putusan '.$request->amar_putusan.' telah diupdate!',
                ];
            }
        }

        $data["flashs"] = $flashs;
    	// return redirect()->back()->with($data);
        return redirect()->route('amarputusan.index');
    }
    public function ajaxDataAP(Request $request){
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
        $jumlahTotal = AmarPutusan::count();
        
        if ($search) { // filter data
            $where = "amar_putusan like lower('%{$search}%')";
            $jumlahFiltered = AmarPutusan::whereRaw("{$where}")->orderBy($orderBy, $orderType)
                ->count(); //hitung data yang telah terfilter
            $data = AmarPutusan::whereRaw($where)
                ->orderBy($orderBy, $orderType)
                ->offset($offset)
                ->limit($limit)
                ->get();
        } else {
            $jumlahFiltered = $jumlahTotal;
            $data = AmarPutusan::orderBy($orderBy, $orderType)
                ->offset($offset)
                ->limit($limit)
                ->get();
        }

        $result = [];

        foreach ($data as $no => $dt) {
            $linkdelete = url('master-data/deleteAP', $dt->id);
            $action = '<center>
                                <div class="btn-group btn-group-sm">
                            <button data-toggle="tooltip" data-original-title="Edit" type="button" class="btn btn-xs btn-primary btn-circle" onclick="editAP(\''.$dt->id.'\')"><i class="fas fa-pencil-alt"></i></button>
                            <button onclick="buttonDeleteAP(this)" data-link="'.$linkdelete.'" data-toggle="tooltip" data-original-title="Delete" type="button" class="btn btn-xs btn-danger btn-circle"><i class="fas fa-trash"></i></button>
                            </div>
                            </center>';
            $result[] = [
                $start + $no + 1,
                $dt->amar_putusan,
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
    public function editAP(Request $request)
    {
        $id = $request->id;
        $dt = AmarPutusan::where('id',$id)->first();

        echo json_encode($dt);
    }
    public function deleteAP($id){
        AmarPutusan::where('id',$id)->delete();
        return redirect()->back();
    }
}
