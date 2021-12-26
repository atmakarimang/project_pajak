<?php

namespace App\Http\Controllers\Permohonan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; // baru
use App\Models\DevError;
use App\Models\AsalPermohonan;
use App\Models\JenisPermohonan;
use App\Models\Pajak;
use App\Models\Ketetapan;
use App\Models\AnggotaSeksi;
use App\Models\PelaksanaBidang;
use App\Models\SeksiKonseptor;
use App\Models\Status;
use App\Models\Progress;
use App\Models\KategoriPermohonan;
use App\Models\Keputusan;
use DB;

class KasiController extends Controller
{
    protected $PATH_VIEW = 'permohonan.Kasi.';

    public function __construct()
    {
        // $this->middleware('auth');
    }
    public function index(Request $request){
        $data = [];
        $user = Auth::user();
        $mode = $request->mode;
        $no_agenda = $request->no_agenda;$data["no_agenda"] = $no_agenda;
        $dataPB = PelaksanaBidang::get();
        $dtAsalPermohonan = AsalPermohonan::get();
        $dtJnsPermohonan = JenisPermohonan::get();
        $dtPajak = Pajak::get();
        $dtKetetapan = Ketetapan::get();
        $dtKepsek = AnggotaSeksi::get();
        $page = \Request::get('page');
        $page = ($page-1)*5+1;
        if(empty($page)||$page < 1){
            $page = 1;
        }
        $data["mode"] = $mode;
        $data['user'] = $user;
        $data['dtAsalPermohonan'] = $dtAsalPermohonan;
        $data['dtJnsPermohonan'] = $dtJnsPermohonan;
        $data['dtPajak'] = $dtPajak;
        $data['dtKetetapan'] = $dtKetetapan;
        $data['dtKepsek'] = $dtKepsek;
        $data['dataPB'] = $dataPB;
        $data['page'] = $page;

        return view($this->PATH_VIEW.'index',$data);
    }
    public function create(Request $request,$id){
        $no_agenda = base64_decode($id);
        $mode = $request->mode;
        // dd($no_agenda);
        $data = [];
        $user = Auth::user();
        $dataPB = PelaksanaBidang::where('no_agenda',$no_agenda)->first();
        $dtAsalPermohonan = AsalPermohonan::get();
        $dtJnsPermohonan = JenisPermohonan::get();
        $dtPajak = Pajak::get();
        $dtKetetapan = Ketetapan::get();
        $dtKepsek = AnggotaSeksi::get();
        $dtKatPermohonan = KategoriPermohonan::get();
        $dtSeksiKonsep = SeksiKonseptor::get();
        $dtStatus = Status::get();
        $dtProgress = Progress::get();
        $dtKeputusan = Keputusan::get();
        $data['user'] = $user;
        $data["mode"] = $mode;
        $data['dtAsalPermohonan'] = $dtAsalPermohonan;
        $data['dtJnsPermohonan'] = $dtJnsPermohonan;
        $data['dtPajak'] = $dtPajak;
        $data['dtKetetapan'] = $dtKetetapan;
        $data['dtKepsek'] = $dtKepsek;
        $data['dtKatPermohonan'] = $dtKatPermohonan;
        $data['dtSeksiKonsep'] = $dtSeksiKonsep;
        $data['dtStatus'] = $dtStatus;
        $data['dtProgress'] = $dtProgress;
        $data['dtKeputusan'] = $dtKeputusan;
        $data['dataPB'] = $dataPB;
        return view($this->PATH_VIEW.'create',$data);
    }
    public function store(Request $request){
        $user = Auth::user();
    	$mode = strtolower($request->input("mode"));
        $error = 0;
    	DB::beginTransaction();
    	// if($mode=="edit"){
        // }else{
            try {
                $pb = PelaksanaBidang::where('no_agenda',$request->no_agenda)->first();
    			$data = PelaksanaBidang::createKasi($request,$pb);
    		}catch(\Exception $e) {
    			$devError = new DevError;
	            $devError->form = "Add Kasi";
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
	                'message' => "Kasi gagal disimpan!",
	            ];
	        }
        // }

        if($error == 0) {
    		DB::commit();
            if($mode == 'add'){
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => 'Kasi '.$request->no_agenda.' telah ditambahkan!',
                ];
            }else{
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => 'Kasi '.$request->no_agenda.' telah diperbaharui!',
                ];
            }
        }

        $data["flashs"] = $flashs;
    	// return redirect()->back()->with($data);
        return redirect()->route('kasi.index');
    }
}
