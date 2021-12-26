<?php

namespace App\Http\Controllers\NonPermohonan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; // baru
use App\Models\DevError;
use App\Models\AsalPermohonan;
use App\Models\JenisPermohonan;
use App\Models\Pajak;
use App\Models\Ketetapan;
use App\Models\NonPelaksanaBidang;
use App\Models\Status;
use App\Models\Progress;
use App\Models\SeksiKonseptor;
use App\Models\KategoriPermohonan;
use DB;

use PhpOffice\PhpSpreadsheet\Reader\Html;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class NonPelaksanaBidangController extends Controller
{
    protected $PATH_VIEW = 'nonpermohonan.NonPelaksanaBidang.';

    public function __construct()
    {
        // $this->middleware('auth');
    }
    public function index(Request $request){
        $data = [];
        $user = Auth::user();
        $mode = $request->mode;
        $dtAsalPermohonan = AsalPermohonan::get();
        $dtJnsPermohonan = JenisPermohonan::get();
        $dtPajak = Pajak::get();
        $dtKetetapan = Ketetapan::get();
        $dtStatus = Status::get();
        $dtProgress = Progress::get();
        $dtSeksiKonsep = SeksiKonseptor::get();
        $dtKatPermohonan = KategoriPermohonan::get();
        $mode = $request->mode; $data["mode"] = $mode;
        $no_agenda = base64_decode($request->no);$data["no_agenda"] = $no_agenda;
        $dtPB = NonPelaksanaBidang::where('no_agenda','=',$no_agenda)->first();
        if(empty($dtPB)) {
            $dtPB = new NonPelaksanaBidang;
        }else{
            $dtPB = NonPelaksanaBidang::where('no_agenda','=',$no_agenda)->first();
        }
        //set otomatis no agenda
        // 7 digit angka depan = nomor urut, 2 digit angka belakang = tahun P-0000001-21
        $datenow = substr(date('Y'),-2);
        $maxval = NonPelaksanaBidang::max('no_agenda');
        $no_agenda = "NP-0000001-".$datenow;
        if($maxval){
            $noUrut = substr($maxval,2,7);
            $noInt = (int)$noUrut;
            $noInt++;
            $no_agenda = "NP-" .str_pad($noInt, 7, "0",  STR_PAD_LEFT)."-".$datenow;
        }
        
        $data["mode"] = $mode;
        $data['user'] = $user;
        $data['dtAsalPermohonan'] = $dtAsalPermohonan;
        $data['dtJnsPermohonan'] = $dtJnsPermohonan;
        $data['dtPajak'] = $dtPajak;
        $data['dtKetetapan'] = $dtKetetapan;
        $data['dtPB'] = $dtPB;
        $data['dtStatus'] = $dtStatus;
        $data['dtProgress'] = $dtProgress;
        $data['dtSeksiKonsep'] = $dtSeksiKonsep;
        $data['dtKatPermohonan'] = $dtKatPermohonan;
        $data['no_agenda'] = $no_agenda;

        return view($this->PATH_VIEW.'index',$data);
    }
    public function store(Request $request){
        $mode = strtolower($request->input("mode"));
    	$results = $request->all();
    	$error = 0;
    	DB::beginTransaction();
        if($mode=="edit"){
            $no_agenda = $request->no_agenda;
            $pb = NonPelaksanaBidang::where('no_agenda',$no_agenda)->first();
            try {
                $data = NonPelaksanaBidang::updateDt($request,$pb);
            }catch(\Exception $e) {
    			$devError = new DevError;
	            $devError->form = "Update Pelaksana Bidang";
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
	                'message' => "Pelaksana Bidang gagal diperbaharui!",
	            ];
	        }
        }else{
            try {
    			$data = NonPelaksanaBidang::create($request);
    		}catch(\Exception $e) {
    			$devError = new DevError;
	            $devError->form = "Add Pelaksana Bidang";
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
	                'message' => "Pelaksana Bidang gagal disimpan!",
	            ];
	        }
        }
        if($error == 0) {
    		DB::commit();
            if($mode == 'add'){
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => $request->no_agenda.' telah ditambahkan!',
                ];
            }else{
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => $request->no_agenda.' telah diupdated!',
                ];
            }
        }

        $data["flashs"] = $flashs;
    	// return redirect()->back()->with($data);
        if($mode=="edit"){
            return redirect()->back();
        }else{
            return redirect()->route('nonpelaksanabidang.index');
        }
    }
    public function browse(Request $request){
        $user = Auth::user();
        $page = \Request::get('page');
        $data['user'] = $user;
        return view($this->PATH_VIEW.'browse',$data);
    }

    public function datatablePB(Request $request){
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

        $jumlahTotal = NonPelaksanaBidang::where('status_dokumen','<>','Delete')
                    ->orWhereNull('status_dokumen')
                    ->count();
        
        if ($search) { // filter data
            $where = " no_agenda like lower('%{$search}%') OR npwp like lower('%{$search}%')
            OR nama_wajib_pajak like lower('%{$search}%') OR jenis_permohonan like lower('%{$search}%')
            OR pajak like lower('%{$search}%') OR no_ketetapan like lower('%{$search}%')
            OR seksi_konseptor like lower('%{$search}%') OR progress like lower('%{$search}%') OR status like lower('%{$search}%')";
            $jumlahFiltered = NonPelaksanaBidang::whereRaw("{$where}") ->count(); //hitung data yang telah terfilter
            if($orderBy !=null){
                $data = NonPelaksanaBidang::where('status_dokumen','<>','Delete')
                    ->orWhereNull('status_dokumen')
                    ->whereRaw($where)->orderBy($orderBy, $orderType)->get();
            }else{
                $data = NonPelaksanaBidang::where('status_dokumen','<>','Delete')
                    ->orWhereNull('status_dokumen')
                    ->whereRaw($where)->get();
            }
            
        } else {
            $jumlahFiltered = $jumlahTotal;
            if($orderBy !=null){
                $data = NonPelaksanaBidang::where('status_dokumen','<>','Delete')
                    ->orWhereNull('status_dokumen')
                    ->limit($limit)->orderBy($orderBy,$orderType)->get();
            }else{
                $data = NonPelaksanaBidang::where('status_dokumen','<>','Delete')
                    ->orWhereNull('status_dokumen')
                    ->limit($limit)->get();
            }
        }
        $result = [];
        foreach ($data as $no => $dt) {
            $linkedit = url('/permohonan/pelaksana-bidang?mode=edit&no='.base64_encode($dt->no_agenda));
            $linkdelete = url('/permohonan/pelaksana-bidang/delete',base64_encode($dt->no_agenda));
            $action = '<center>
                            <a href="'.$linkedit.'">
                                <button data-toggle="tooltip" data-original-title="Edit" type="button" class="btn btn-xs btn-primary btn-circle"><i class="fas fa-pencil-alt"></i></button>
                            </a>
                            <button onclick="buttonDelete(this)" data-link="'.$linkdelete.'" data-toggle="tooltip" data-original-title="Delete" type="button" class="btn btn-xs btn-default btn-circle"><i class="fas fa-trash"></i></button>
                        </center>';
            if($dt->progress == 'Final'){
                $badge = 'badge-success'; 
            }else if($dt->progress == 'Proses'){
                $badge = 'badge-primary';
            }else{
                $badge = 'badge-info';
            }
            $progress = '<center>
                            <span class="badge '.$badge.'">'.$dt->progress.'</span>
                        </center>';
                        
             if($dt->status == 'Selesai'){
                $badge = 'badge-success';
             }else if($dt->status == 'Tunggakan'){
                $badge = 'badge-danger';
             }else if($dt->status == 'Kembali'){
                $badge = 'badge-warning';
             }else{
                $badge = 'badge-info';
             }
            $status = '<center>
                            <span class="badge '.$badge.'">'.$dt->status.'</span>
                        </center>';
            $result[] = [
                $no+1,
                $dt->no_agenda,
                $dt->npwp,
                $dt->nama_wajib_pajak,
                $dt->jenis_permohonan,
                $dt->pajak,
                $dt->no_ketetapan,
                $dt->seksi_konseptor,
                $progress,
                $status,
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

    public function delete($no_agenda){
        $no_agenda = base64_decode($no_agenda);
        NonPelaksanaBidang::where('no_agenda',$no_agenda)->update(['status_dokumen' => 'Delete']);
        return redirect()->back();
    }

    public function print($no_agenda,$docExt){
        $noagen = base64_decode($no_agenda);
        $data = NonPelaksanaBidang::where('no_agenda','=',$noagen)->first();
        if(!$data){
            // $msg = notifErrorHelper('Wrong Action','Error');
            // return redirect()->back()->with('flashs',$msg);
            return redirect()->back();
        }
        $spreadsheet = new Spreadsheet();
        dd($spreadsheet);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);
        $logo = storage_path().'/app/'.$company->LOGO;

        //header
        $sheet->mergeCells("A1:C1");
        $sheet->mergeCells("I1:J1");

        $objDrawing1 = new Drawing();
        $objDrawing1->setName('Sample image');
        $objDrawing1->setDescription('Sample image');
        $objDrawing1->setPath($logo);

        $objDrawing1->setHeight(64);
        $objDrawing1->setCoordinates("A1");
        $objDrawing1->setWorksheet($sheet);

        $sheet->getRowDimension('1')->setRowHeight(64);
        $sheet->setCellValue('I1', 'Sales Order');
        $fontTitle = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ]
        ];
        $sheet->getStyle('I1')->applyFromArray($fontTitle);

        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_THICK,
                ],
            ],
        ];
    }
}
