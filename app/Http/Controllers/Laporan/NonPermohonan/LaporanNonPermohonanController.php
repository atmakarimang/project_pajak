<?php

namespace App\Http\Controllers\Laporan\NonPermohonan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; // baru
use App\Models\DevError;
use App\Models\NonPelaksanaBidang;
use App\Models\Status;
use App\Models\SeksiKonseptor;
use App\Models\User;
use DB;

use PhpOffice\PhpSpreadsheet\Reader\Html;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class LaporanNonPermohonanController extends Controller
{
    protected $PATH_VIEW = 'laporan.nonpermohonan.';

    public function __construct()
    {
        // dd($this->middleware('auth'));
        // $this->middleware('auth');
    }
    
    public function index(Request $request){ 
        $user = Auth::user();
        $dtSK = SeksiKonseptor::get();
        $dtStatus = Status::whereIn('status', ['Diarsipkan', 'Ditindaklanjuti', 'Lain - lain'])->get();
        $data['user'] = $user;
        $data['dtSK'] = $dtSK;
        $data['dtStatus'] = $dtStatus;
        return view($this->PATH_VIEW.'index',$data);
    }
    
    public function ajaxDataPB(Request $request){
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
        
        $seksi_konseptor = $request->seksi_konseptor;
        $status = $request->status; 
        $date = '';
        if($request->tgl_awal != null || $request->tgl_akhir != null){
            $date1 = str_replace('/', '-', $request->tgl_awal);
            $tgl_awal = date('Y-m-d', strtotime($date1));
            $date2 = str_replace('/', '-', $request->tgl_akhir);
            $tgl_akhir = date('Y-m-d', strtotime($date2));
            $date = " AND TGL_DITERIMA_KANWIL BETWEEN DATE('".$tgl_awal."') AND DATE('".$tgl_akhir."')";
        }
        
        $sk = '';
        if($seksi_konseptor !='-'){
            $sk = "AND SEKSI_KONSEPTOR LIKE '%".$seksi_konseptor."%'";
        }
        $stts = '';
        if($status !='-'){
            $stts = "AND STATUS = '".$status."'";
        }
        
        $filter = $sk." ".$stts." ".$date;
        // dd($filter);
        $jumlahTotal = NonPelaksanaBidang::where('status_dokumen','<>','Delete')
                    ->orWhereNull('status_dokumen')
                    ->count();
        
        if ($search) { // filter data
            $where = " (no_agenda like lower('%{$search}%') OR npwp like lower('%{$search}%')
            OR nama_wajib_pajak like lower('%{$search}%') OR seksi_konseptor like lower('%{$search}%') OR hal like lower('%{$search}%')
            OR asal_surat like lower('%{$search}%') OR penelaah_keberatan like lower('%{$search}%') OR status like lower('%{$search}%'))
            $filter";
            $jumlahFiltered = NonPelaksanaBidang::whereRaw("{$where}")->count(); //hitung data yang telah terfilter
            if($orderBy !=null){
                $data = NonPelaksanaBidang::whereRaw('(status_dokumen <> "Delete" OR status_dokumen IS NULL)')
                    ->whereRaw($where)->orderBy($orderBy, $orderType)->get();
            }else{
                $data = NonPelaksanaBidang::whereRaw('(status_dokumen <> "Delete" OR status_dokumen IS NULL)')
                    ->whereRaw($where)->get();
            }
            
        } else {
            $jumlahFiltered = $jumlahTotal;
            if($orderBy !=null){
                $data = NonPelaksanaBidang::whereRaw('(status_dokumen <> "Delete" OR status_dokumen IS NULL) '.$filter.'')
                    ->offset($offset)
                    ->limit($limit)->orderBy($orderBy,$orderType)->get();
            }else{
                $data = NonPelaksanaBidang::whereRaw('(status_dokumen <> "Delete" OR status_dokumen IS NULL) '.$filter.'')
                    ->offset($offset)
                    ->limit($limit)
                    ->get();
            }
        }
        $result = [];
        foreach ($data as $no => $dt) {
            $tgl_diterima_kanwil = date('d-m-Y', strtotime($dt->tgl_diterima_kanwil));
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
            $linkedit = url('/nonpermohonan/nonpelaksana-bidang?mode=edit&no='.base64_encode($dt->no_agenda).'&readonly=1');
            $action = '<center>
                            <a href="'.$linkedit.'" target="_blank">
                                <button data-toggle="tooltip" title="Lihat Dokumen" type="button" class="btn btn-xs btn-primary btn-circle"><i class="fas fa-search"></i></button>
                            </a>
                        </center>';
            
            $result[] = [
                $start+$no+1,
                $dt->no_agenda,
                $tgl_diterima_kanwil,
                $dt->npwp,
                $dt->nama_wajib_pajak,
                $dt->hal,
                $dt->asal_surat,
                $dt->seksi_konseptor,
                $dt->penelaah_keberatan,
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

    public function print(Request $request){
        $seksi_konseptor = $request->seksi_konseptor;
        $status = $request->status; 
        $date = '';
        if($request->tgl_awal != null || $request->tgl_akhir != null){
            $date1 = str_replace('/', '-', $request->tgl_awal);
            $tgl_awal = date('Y-m-d', strtotime($date1));
            $date2 = str_replace('/', '-', $request->tgl_akhir);
            $tgl_akhir = date('Y-m-d', strtotime($date2));
            $date = " AND TGL_DITERIMA_KANWIL BETWEEN DATE('".$tgl_awal."') AND DATE('".$tgl_akhir."')";
        }
        
        $sk = '';
        if($seksi_konseptor !='-'){
            $sk = "AND SEKSI_KONSEPTOR LIKE '%".$seksi_konseptor."%'";
        }
        $stts = '';
        if($status !='-'){
            $stts = "AND STATUS = '".$status."'";
        }
        
        $filter = $sk." ".$stts." ".$date;

        $data = NonPelaksanaBidang::whereRaw('(status_dokumen <> "Delete" OR status_dokumen IS NULL) '.$filter.'')->get();
        // dd($data);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);

        $sheet->setCellValue('E1', 'E-REPORTING NON PERMOHONAN KEBERATAN DAN NON KEBERATAN');
        $sheet->setCellValue('E2', 'BIDANG KEBERATAN BANDING DAN PENGURANGAN');
        $sheet->setCellValue('E3', 'KANWIL DJP JAWA TIMUR I');
        $fontTitle = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ]
        ];

        $sheet->getStyle('E1')->applyFromArray($fontTitle);
        $sheet->getStyle('E2')->applyFromArray($fontTitle);
        $sheet->getStyle('E3')->applyFromArray($fontTitle);

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
        //DETAILS
        $sheet->setCellValue('A6', 'No Agenda');
        $sheet->setCellValue('B6', 'Tanggal diterima Kanwil');
        $sheet->setCellValue('C6', 'NPWP');
        $sheet->setCellValue('D6', 'Nama Wajib Pajak');
        $sheet->setCellValue('E6', 'Hal');
        $sheet->setCellValue('F6', 'Asal Surat');
        $sheet->setCellValue('G6', 'Seksi Konseptor');
        $sheet->setCellValue('H6', 'Penelaah Keberatan');
        $sheet->setCellValue('I6', 'Status');

        $table_hr = [
            'borders' => [
                'bottom' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];
        $spreadsheet->getActiveSheet()->getStyle('A6:I6')
            ->getFont()->getColor()->setARGB('FFFFFFFF');
        $sheet->getStyle('A6:I6')->applyFromArray($fontTitle);
        
        $sheet->getRowDimension('4')->setRowHeight(20);
        $last_row = 6;
        foreach ($data as $key => $dt) {
            $tgl_diterima_kpp = date('d-m-Y', strtotime($dt->tgl_diterima_kanwil));
            $last_row++;
            $sheet->setCellValue('A'.$last_row, $dt->no_agenda);
            $sheet->setCellValue('B'.$last_row, $tgl_diterima_kpp);
            $sheet->setCellValue('C'.$last_row, $dt->npwp);
            $sheet->setCellValue('D'.$last_row, $dt->nama_wajib_pajak);
            $sheet->setCellValue('E'.$last_row, $dt->hal);
            $sheet->setCellValue('F'.$last_row, $dt->asal_surat);
            $sheet->setCellValue('G'.$last_row, $dt->seksi_konseptor);
            $sheet->setCellValue('H'.$last_row, $dt->penelaah_keberatan);
            $sheet->setCellValue('I'.$last_row, $dt->status);
        }
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

        $sheet->getStyle('A6:I6')->applyFromArray($header_style_border);

        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="E-Reporting Non Permohonan.xls');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        $writer = new Xls($spreadsheet);
        $writer->save("php://output");
        header('Content-Disposition: attachment; filename="E-Reporting Non Permohonan.xls');
    }
}
