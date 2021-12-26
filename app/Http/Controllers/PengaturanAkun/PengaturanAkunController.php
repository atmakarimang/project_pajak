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
        $data['dataUser'] = $dataUser;

        return view($this->PATH_VIEW.'index',$data);
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
