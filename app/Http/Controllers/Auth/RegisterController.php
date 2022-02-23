<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth; // baru
use Session;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function index(){
		return view('auth.register');
    }
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'user_id' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'confirmed'],
            'jabatan' => ['required', 'string', 'max:255'],
            'peran' => ['required', 'string', 'max:255'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(Request $request)
    {
        $mode = strtolower($request->input("mode"));
        $form = $request->form;
        $error = 0;
        if($mode == 'add'){
            $cek = User::where('user_id',$request->user_id)->count();
            if($cek > 0){
                Session::flash('error', 'User id sudah digunakan!');
                return redirect()->back();
            }
            if ($request->hasFile('ft_profil')) {
                $resi = $request->hasFile("ft_profil");
                $ext = strtolower($request->noresi->getClientOriginalExtension());
                $filename = "$request->user_id.$ext";
                $request->ft_profil->move('public/akun/', $filename);
            }else{
                $filename ='';
            }
            try {
                User::create([
                    'user_id' => $request->user_id,
                    'nama' => $request->nama,
                    'password' => md5($request->password),
                    'jabatan' => $request->jabatan,
                    'peran' => $request->peran,
                    'ft_profil' => $filename,
                    'created_at' => date("Y-m-d H:i:s"),
                ]);
            }catch(\Exception $e) {
                $devError = new DevError;
                $devError->form = "Add User";
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
                    'message' => "User gagal disimpan!",
                ];
            } 
        }else{
            $cek = User::where('user_id',$request->user_id)->where('password',$request->password_lama)->count();
            if($cek > 0){
                Session::flash('error', 'Password lama anda salah!');
                return redirect()->back();
            }
            if ($request->hasFile('ft_profil')) {
                $ft_profil = $request->hasFile("ft_profil");
                $ext = strtolower($request->ft_profil->getClientOriginalExtension());
                $filename = "$request->user_id.$ext";
                $request->ft_profil->move('public/akun/', $filename);
            }else{
                $filename ='';
            }
            try {
                User::where("user_id", $request->user_id)->update([
                    'nama' => $request->nama,
                    'password' => md5($request->password),
                    'jabatan' => $request->jabatan,
                    'peran' => $request->peran,
                    'ft_profil' => $filename,
                    'updated_at' => date("Y-m-d H:i:s"),
                ]);
            }catch(\Exception $e) {
                $devError = new DevError;
                $devError->form = "Add User";
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
                    'message' => "User gagal diupadate!",
                ];
            } 
        }
        if($error == 0) {
            if($mode == 'add'){
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => 'User '.$request->user_id.' telah ditambahkan!',
                ];
            }else{
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => 'User '.$request->user_id.' telah diupdated!',
                ];
            }
        }

        $data["flashs"] = $flashs;
    	// return redirect()->back()->with($data);
        if($form == 'formregister'){
            return redirect()->route('login');
        }else{
            return redirect()->back();
        }
        
    }
}
