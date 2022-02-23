<?php

namespace App\Http\Controllers\PengaturanAkun;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; // baru
use App\Models\DevError;
use App\Models\User;
use DB;

class PengaturanAkunController extends Controller
{
    protected $PATH_VIEW = 'pengaturanakun.';

    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    public function index(Request $request){
        $data = [];
        $page = \Request::get('page');
        $page = ($page-1)*5+1;
        if(empty($page)||$page < 1){
            $page = 1;
        }
        $user = Auth::user();
        $mode = $request->mode;
        $dataUser = User::all();
        
        if($mode == "edit"){
            $user_id = base64_decode($request->usrid);
            $usernya = User::where('user_id',$user_id)->first();
            $data['usernya'] = $usernya;
        }else{
            $usernya = new User;
            $data['usernya'] = $usernya;
        }
        
        $data['page'] = $page;
        $data["mode"] = $mode;
        $data['user'] = $user;

        return view($this->PATH_VIEW.'index',$data);
    }

    public function datatableAkun(Request $request){
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

        $jumlahTotal = User::count();
        
        if ($search) { // filter data
            $where = " user_id like lower('%{$search}%') OR nama like lower('%{$search}%')
            OR jabatan like lower('%{$search}%') OR peran like lower('%{$search}%')";
            $jumlahFiltered = User::whereRaw("{$where}") ->count(); //hitung data yang telah terfilter
            if($orderBy !=null){
                $data = User::whereRaw($where)->orderBy($orderBy, $orderType)->get();
            }else{
                $data = User::whereRaw($where)->get();
            }
            
        } else {
            $jumlahFiltered = $jumlahTotal;
            if($orderBy !=null){
                $data = User::offset($offset)->limit($limit)->orderBy($orderBy,$orderType)->get();
            }else{
                $data = User::offset($offset)->limit($limit)->get();
            }
        }
        $result = [];
        foreach ($data as $no => $dt) {
            $linkedit = url('/pengaturan-akun?mode=edit&usrid='.base64_encode($dt->user_id));
            $linkdelete = url('/pengaturan-akun/delete',base64_encode($dt->user_id));
            $action = '<center>
                            <a href="'.$linkedit.'">
                                <button data-toggle="tooltip" data-original-title="Edit" type="button" class="btn btn-xs btn-primary btn-circle"><i class="fas fa-pencil-alt"></i></button>
                            </a>
                            <button onclick="buttonDelete(this)" data-link="'.$linkdelete.'" data-toggle="tooltip" data-original-title="Delete" type="button" class="btn btn-xs btn-default btn-circle"><i class="fas fa-trash"></i></button>
                        </center>';
            $result[] = [
                $start+$no+1,
                $dt->user_id,
                $dt->nama,
                $dt->jabatan,
                $dt->peran,
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

    public function delete($user_id){
        $user_id = base64_decode($user_id);
        try{
            User::where('user_id',$user_id)->delete();
            $flashs[] = [
                'type' => 'success', // option : info, warning, success, error
                'title' => 'Success',
                'message' => "User '.$user_id.' telah dihapus!",
            ];
            // return redirect()->back()->with($flashs);
            return redirect()->back();
        }catch(\Exception $e) {
            $devError = new DevError;
            $devError->form = "Add User";
            $devError->url = $request->path();
            $devError->error = $e;
            $devError->data = json_encode($request->input());
            $devError->created_at = date("Y:m:d H:i:s");
            $devError->save();
            DB::commit();
            DB::rollBack();
            $flashs[] = [
                'type' => 'error', // option : info, warning, success, error
                'title' => 'Error',
                'message' => "User '.$user_id.' tidak bisa dihapus!",
            ];
            return redirect()->back()->with($flashs);
        } 
    }
}
