<?php

namespace App\Http\Controllers\NonPermohonan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; // baru
use App\Models\DevError;
use App\Models\Pajak;
use App\Models\Ketetapan;
use App\Models\NonPelaksanaBidang;
use App\Models\Status;
use App\Models\SeksiKonseptor;
use App\Models\AnggotaSeksi;
use DB;

use PhpOffice\PhpSpreadsheet\Reader\Html;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
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
        $dtStatus = Status::whereIn('status', ['Diarsipkan', 'Ditindaklanjuti', 'Lain - lain'])->get();
        $dtSeksiKonsep = SeksiKonseptor::get();
        $dtKepsek = AnggotaSeksi::get();
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
        $tahun = date('Y');
        $maxval = NonPelaksanaBidang::where('tahun',$tahun)->max('no');
        $no_agenda = "NP-0000001-".$datenow;
        if($maxval){
            $noUrut = $maxval;
            $noInt = (int)$noUrut;
            $noInt++;
            $no_agenda = "NP-" .str_pad($noInt, 7, "0",  STR_PAD_LEFT)."-".$datenow;
        }
        
        $data["mode"] = $mode;
        $data['user'] = $user;
        $data['dtPB'] = $dtPB;
        $data['dtStatus'] = $dtStatus;
        $data['dtSeksiKonsep'] = $dtSeksiKonsep;
        $data['dtKepsek'] = $dtKepsek;
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
            OR nama_wajib_pajak like lower('%{$search}%') OR no_surat like lower('%{$search}%')
            OR asal_surat like lower('%{$search}%') OR status like lower('%{$search}%')";
            $jumlahFiltered = NonPelaksanaBidang::whereRaw("{$where}") ->count(); //hitung data yang telah terfilter
            if($orderBy !=null){
                $data = NonPelaksanaBidang::where('status_dokumen','<>','Delete')
                    ->orWhereNull('status_dokumen')
                    ->whereRaw($where)->orderBy($orderBy, $orderType)->get();
            }else{
                $data = NonPelaksanaBidang::where('status_dokumen','<>','Delete')
                    ->orWhereNull('status_dokumen')
                    ->whereRaw($where)
                    ->get();
            }
            
        } else {
            $jumlahFiltered = $jumlahTotal;
            if($orderBy !=null){
                $data = NonPelaksanaBidang::where('status_dokumen','<>','Delete')
                    ->orWhereNull('status_dokumen')
                    ->offset($offset)
                    ->limit($limit)->orderBy($orderBy,$orderType)->get();
            }else{
                $data = NonPelaksanaBidang::where('status_dokumen','<>','Delete')
                    ->orWhereNull('status_dokumen')
                    ->offset($offset)
                    ->limit($limit)->get();
            }
        }
        $result = [];
        foreach ($data as $no => $dt) {
            $linkedit = url('/nonpermohonan/nonpelaksana-bidang?mode=edit&no='.base64_encode($dt->no_agenda));
            $linkdelete = url('/nonpermohonan/nonpelaksana-bidang/delete',base64_encode($dt->no_agenda));
            $action = '<center>
                            <a href="'.$linkedit.'">
                                <button data-toggle="tooltip" title="Edit" type="button" class="btn btn-xs btn-primary btn-circle"><i class="fas fa-pencil-alt"></i></button>
                            </a>
                            <button onclick="buttonDelete(this)" data-link="'.$linkdelete.'" data-toggle="tooltip" title="Delete" type="button" class="btn btn-xs btn-default btn-circle"><i class="fas fa-trash"></i></button>
                        </center>';
                        
             if($dt->status == 'Ditindaklanjuti'){
                $badge = 'badge-success';
             }else if($dt->status == 'Diarsipkan'){
                $badge = 'badge-danger';
             }else if($dt->status == 'Lain - lain'){
                $badge = 'badge-warning';
             }else{
                $badge = 'badge-info';
             }
            $status = '<center>
                            <span class="badge '.$badge.'">'.$dt->status.'</span>
                        </center>';
            $result[] = [
                $start + $no+1,
                $dt->no_agenda,
                $dt->no_surat,
                $dt->asal_surat,
                $dt->npwp,
                $dt->nama_wajib_pajak,
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

    public function print($no_agenda){
        $noagen = base64_decode($no_agenda);
        $data = NonPelaksanaBidang::where('no_agenda','=',$noagen)->first();
        if(!$data){
            // $msg = notifErrorHelper('Wrong Action','Error');
            // return redirect()->back()->with('flashs',$msg);
            return redirect()->back();
        }
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);

        $fontTitle = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ]
        ];

        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_THICK,
                ],
            ],
        ];

        $sheet->getColumnDimension('A')->setWidth(21);
        $sheet->getColumnDimension('B')->setWidth(21);
        $sheet->getColumnDimension('C')->setWidth(21);
        $sheet->getColumnDimension('D')->setWidth(21);
        $sheet->getColumnDimension('E')->setWidth(21);
        $sheet->getColumnDimension('F')->setWidth(21);
        $sheet->getColumnDimension('G')->setWidth(21);
        $sheet->getColumnDimension('H')->setWidth(21);
        $sheet->getColumnDimension('I')->setWidth(16);
        $sheet->getColumnDimension('J')->setWidth(16);
        $sheet->getColumnDimension('K')->setWidth(16);
        $sheet->getColumnDimension('L')->setWidth(16);
        $sheet->getColumnDimension('M')->setWidth(16);

        $sheet->setCellValue('A1', 'No Agenda');
        $sheet->setCellValue('B1', 'Tanggal Agenda');
        $sheet->setCellValue('C1', 'Nomor Surat');
        $sheet->setCellValue('D1', 'Tanggal Surat');
        $sheet->setCellValue('E1', 'Tanggal diterima Kanwil');
        $sheet->setCellValue('F1', 'Asal Surat');
        $sheet->setCellValue('G1', 'Hal');
        $sheet->setCellValue('H1', 'Nomor Pokok Wajib Pajak');
        $sheet->setCellValue('I1', 'Nama Wajib Pajak');
        $sheet->setCellValue('J1', 'Seksi Konseptor');
        $sheet->setCellValue('K1', 'Kepala Seksi');
        $sheet->setCellValue('L1', 'Penerima Disposisi');
        $sheet->setCellValue('M1', 'Status');

        $spreadsheet->getActiveSheet()->getStyle('A1:M1')->getFont()->getColor()->setARGB('FFFFFFFF');
        $sheet->getStyle('A1:M1')->applyFromArray($fontTitle);
        $sheet->getRowDimension('4')->setRowHeight(20);

        $sheet->setCellValue('A2', $data->no_agenda);
        $sheet->setCellValue('B2', $data->tgl_agenda);
        $sheet->setCellValue('C2', $data->no_surat);
        $sheet->setCellValue('D2', date("d-m-Y",strtotime($data->tgl_surat)));
        $sheet->setCellValue('E2', date("d-m-Y",strtotime($data->tgl_diterima_kanwil)));
        $sheet->setCellValue('F2', $data->asal_surat);
        $sheet->setCellValue('G2', $data->hal);
        $sheet->setCellValue('H2', $data->npwp);
        $sheet->setCellValue('I2', $data->nama_wajib_pajak);
        $sheet->setCellValue('J2', $data->seksi_konseptor);
        $sheet->setCellValue('K2', $data->kepala_seksi);
        $sheet->setCellValue('L2', $data->penerima_disposisi);
        $sheet->setCellValue('M2', $data->status);
        
        $header_style_border = [
            'borders' => [
                'bottom' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [
                        'argb' => 'FFFFFFFF'
                    ]
                ]
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FF000080',
                ],
            ]
        ];
        $sheet->getStyle('A1:M1')->applyFromArray($header_style_border);
        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="Non Pelaksana Bidang '.$noagen.'.xls');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        $writer = new Xls($spreadsheet);
        $writer->save("php://output");
        header('Content-Disposition: attachment; filename="Non Pelaksana Bidang '.$noagen.'.xls');
    }
}
