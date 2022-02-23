<?php

namespace App\Http\Controllers\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; // baru
use App\Models\DevError;
use App\Models\AsalPermohonan;
use App\Models\JenisPermohonan;
use App\Models\KategoriPermohonan;
use DB;

class DataPermohonanController extends Controller
{
    protected $PATH_VIEW = 'masterdata.DataPermohonan.';

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
    public function storePemohon(Request $request){
        $user = Auth::user();
    	$mode = strtolower($request->input("mode"));
    	$results = $request->all();
    	$error = 0;
        
    	DB::beginTransaction();
    	if($mode=="edit"){
    		$id = $request->id_pmh;
            $ap = AsalPermohonan::where('id',$id)->first();
            
            try {
                $data = AsalPermohonan::updateDt($request,$ap);
            }catch(\Exception $e) {
                $devError = new DevError;
                $devError->form = "Update Asal Permohonan";
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
                    'message' => "Asal Permohonan gagal diupdated!",
                ];
            }
    	}else{
            $cek_data = AsalPermohonan::find($request->kode_pmh);
            if($cek_data){
                $error_duplikat[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => 'Kode Permohonan '.$request->kode_pmh.' sudah digunakan!',
                ];
                $data["error_duplikat"] = $error_duplikat;
                // return redirect()->back()->with($data);
                return redirect()->back();
            }
    		try {
    			$data = AsalPermohonan::create($request);
    		}catch(\Exception $e) {
    			$devError = new DevError;
	            $devError->form = "Add Asal Permohonan";
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
	                'message' => "Asal Permohonan gagal disimpan!",
	            ];
	        }
    	}

    	if($error == 0) {
    		DB::commit();
            if($mode == 'add'){
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => 'Asal Permohonan baru '.$request->kode_pmh.' telah ditambahkan!',
                ];
            }else{
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => 'Asal Permohonan '.$request->kode_pmh.' telah diupdated!',
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
        $cek_data = JenisPermohonan::where('jenis_permohonan',$request->jenis_permohonan)->count();
        if($cek_data>0){
            $error_duplikat[] = [
                'type' => 'success', // option : info, warning, success, error
                'title' => 'Success',
                'message' => 'Jenis Permohonan '.$request->jenis_permohonan.' sudah ada!',
            ];
            $data["error_duplikat"] = $error_duplikat;
            // return redirect()->back()->with($data);
            return redirect()->back();
        }
    	DB::beginTransaction();
    	if($mode=="edit"){
    		$id = $request->id_jenis;
            $jp = JenisPermohonan::where('id',$id)->first();
            try {
                $data = JenisPermohonan::updateDt($request,$jp);
            }catch(\Exception $e) {
                $devError = new DevError;
                $devError->form = "Update Jenis Permohonan";
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
                    'message' => "Jenis Permohonan gagal diupdated!",
                ];
            }
    	}else{
    		try {
    			$data = JenisPermohonan::create($request);
    		}catch(\Exception $e) {
    			$devError = new DevError;
	            $devError->form = "Add Jenis Permohonan";
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
	                'message' => "Jenis Permohonan gagal disimpan!",
	            ];
	        }
    	}

    	if($error == 0) {
    		DB::commit();
            if($mode == 'add'){
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => 'Jenis Permohonan baru '.$request->jenis_permohonan.' telah ditambahkan!',
                ];
            }else{
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => 'Jenis Permohonan '.$request->jenis_permohonan.' telah diupdated!',
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
        $cek_data = KategoriPermohonan::where('kat_permohonan',$request->kat_pemohon)->count();
        if($cek_data>0){
            $error_duplikat[] = [
                'type' => 'success', // option : info, warning, success, error
                'title' => 'Success',
                'message' => 'Jenis Permohonan '.$request->kat_pemohon.' sudah ada!',
            ];
            $data["error_duplikat"] = $error_duplikat;
            // return redirect()->back()->with($data);
            return redirect()->back();
        }
    	DB::beginTransaction();
    	if($mode=="edit"){
    		$id = $request->id_kp;
            $kp = KategoriPermohonan::where('id',$id)->first();
            try {
                $data = KategoriPermohonan::updateDt($request,$kp);
            }catch(\Exception $e) {
                $devError = new DevError;
                $devError->form = "Update Kategori Permohonan";
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
                    'message' => "Kategori Permohonan gagal diupdated!",
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
    public function ajaxDataPemohon(Request $request){
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
        $limit = $length;
        $offset = $start;

        $jumlahTotal = AsalPermohonan::count();
        
        if ($search) { // filter data
            $where = " id like lower('%{$search}%') OR pemohon like lower('%{$search}%')";
            $jumlahFiltered = AsalPermohonan::whereRaw("{$where}") ->count(); //hitung data yang telah terfilter
            if($orderBy !=null){
                $data = AsalPermohonan::whereRaw($where)->orderBy($orderBy, $orderType)->get();
            }else{
                $data = AsalPermohonan::whereRaw($where)->get();
            }
            
        } else {
            $jumlahFiltered = $jumlahTotal;
            if($orderBy !=null){
                $data = AsalPermohonan::offset($offset)->limit($limit)->orderBy($orderBy,$orderType)->get();
            }else{
                $data = AsalPermohonan::offset($offset)->limit($limit)->get();
            }
        }
        $result = [];
        foreach ($data as $no => $dt) {
            $linkdelete = url('/master-data/deletePmh',$dt->id);
            $action = '<center>
                            <div class="btn-group btn-group-sm">
                            <button data-toggle="tooltip" data-original-title="Edit Pemohon" type="button" class="btn btn-xs btn-primary btn-circle" onclick="editPemohon(\''.$dt->id.'\')"><i class="fas fa-pencil-alt"></i></button>
                            <button onclick="buttonDeletePmh(this)" data-link="'.$linkdelete.'" data-toggle="tooltip" data-original-title="Delete" type="button" class="btn btn-xs btn-danger btn-circle"><i class="fas fa-trash"></i></button>
                            </div>
                        </center>';
            $result[] = [
                $start + $no+1,
                $dt->id,
                $dt->pemohon,
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
    public function editPemohon(Request $request)
    {
        $id = $request->id;
        $dt = AsalPermohonan::where('id',$id)->first();

        echo json_encode($dt);
    }
    public function deletePemohon($id){
        AsalPermohonan::where('id',$id)->delete();
        return redirect()->back();
    }
    public function ajaxDataJP(Request $request){
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
        $limit = $length;
        $offset = $start;

        $jumlahTotal = JenisPermohonan::count();
        
        if ($search) { // filter data
            $where = " id like lower('%{$search}%') OR jenis_permohonan like lower('%{$search}%')";
            $jumlahFiltered = JenisPermohonan::whereRaw("{$where}") ->count(); //hitung data yang telah terfilter
            if($orderBy !=null){
                $data = JenisPermohonan::whereRaw($where)->orderBy($orderBy, $orderType)->get();
            }else{
                $data = JenisPermohonan::whereRaw($where)->get();
            }
            
        } else {
            $jumlahFiltered = $jumlahTotal;
            if($orderBy !=null){
                $data = JenisPermohonan::offset($offset)->limit($limit)->orderBy($orderBy,$orderType)->get();
            }else{
                $data = JenisPermohonan::offset($offset)->limit($limit)->get();
            }
        }
        $result = [];
        foreach ($data as $no => $dt) {
            $linkdelete = url('/master-data/deleteJP',$dt->id);
            $action = '<center>
                            <div class="btn-group btn-group-sm">
                            <button data-toggle="tooltip" data-original-title="Edit" type="button" class="btn btn-xs btn-info btn-circle" onclick="editJP(\''.$dt->id.'\')"><i class="fas fa-pencil-alt"></i></button>
                            <button onclick="buttonDeleteJP(this)" data-link="'.$linkdelete.'" data-toggle="tooltip" data-original-title="Delete" type="button" class="btn btn-xs btn-danger btn-circle"><i class="fas fa-trash"></i></button>
                            </div>
                        </center>';
            $result[] = [
                $start + $no+1,
                $dt->jenis_permohonan,
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
    public function editJP(Request $request)
    {
        $id = $request->id;
        $dt = JenisPermohonan::where('id',$id)->first();

        echo json_encode($dt);
    }
    public function deleteJP($id){
        JenisPermohonan::where('id',$id)->delete();
        return redirect()->back();
    }
    public function ajaxDataKP(Request $request){
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
        $limit = $length;
        $offset = $start;

        $jumlahTotal = KategoriPermohonan::count();
        
        if ($search) { // filter data
            $where = " id like lower('%{$search}%') OR kat_permohonan like lower('%{$search}%')";
            $jumlahFiltered = KategoriPermohonan::whereRaw("{$where}") ->count(); //hitung data yang telah terfilter
            if($orderBy !=null){
                $data = KategoriPermohonan::whereRaw($where)->orderBy($orderBy, $orderType)->get();
            }else{
                $data = KategoriPermohonan::whereRaw($where)->get();
            }
            
        } else {
            $jumlahFiltered = $jumlahTotal;
            if($orderBy !=null){
                $data = KategoriPermohonan::offset($offset)->limit($limit)->orderBy($orderBy,$orderType)->get();
            }else{
                $data = KategoriPermohonan::offset($offset)->limit($limit)->get();
            }
        }
        $result = [];
        foreach ($data as $no => $dt) {
            $linkdelete = url('/master-data/deleteKP',$dt->id);
            $action = '<center>
                            <div class="btn-group btn-group-sm">
                            <button data-toggle="tooltip" data-original-title="Edit" type="button" class="btn btn-xs btn-secondary btn-circle" onclick="editKP(\''.$dt->id.'\')"><i class="fas fa-pencil-alt"></i></button>
                            <button onclick="buttonDeleteKP(this)" data-link="'.$linkdelete.'" data-toggle="tooltip" data-original-title="Delete" type="button" class="btn btn-xs btn-danger btn-circle"><i class="fas fa-trash"></i></button>
                            </div>
                        </center>';
            $result[] = [
                $start + $no+1,
                $dt->kat_permohonan,
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
    public function editKP(Request $request)
    {
        $id = $request->id;
        $dt = KategoriPermohonan::where('id',$id)->first();

        echo json_encode($dt);
    }
    public function deleteKP($id){
        KategoriPermohonan::where('id',$id)->delete();
        return redirect()->back();
    }
}
