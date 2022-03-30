<?php

namespace App\Http\Controllers\Laporan\Permohonan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; // baru
use App\Models\DevError;
use App\Models\AsalPermohonan;
use App\Models\JenisPermohonan;
use App\Models\Pajak;
use App\Models\Ketetapan;
use App\Models\PelaksanaBidang;
use App\Models\Status;
use App\Models\Progress;
use App\Models\SeksiKonseptor;
use App\Models\PenelaahKeberatan;
use App\Models\KategoriPermohonan;
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

class LaporanPermohonanController extends Controller
{
    protected $PATH_VIEW = 'laporan.permohonan.';

    public function __construct()
    {
        // dd($this->middleware('auth'));
        // $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $user = Auth::user();
        $dtJnsPermohonan = JenisPermohonan::get();
        $dtSK = SeksiKonseptor::get();
        $dtKepsek = PenelaahKeberatan::get();
        $dtStatus = Status::where('status', '<>', 'Diarsipkan')->where('status', '<>', 'Ditindaklanjuti')->get();
        $data['user'] = $user;
        $data['dtJnsPermohonan'] = $dtJnsPermohonan;
        $data['dtSK'] = $dtSK;
        $data['dtKepsek'] = $dtKepsek;
        $data['dtStatus'] = $dtStatus;
        return view($this->PATH_VIEW . 'index', $data);
    }

    public function ajaxDataPB(Request $request)
    {
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
        $orderBy = $orderBy[$orderByColumnIndex]['name']; //Get name of the sorting column from its index
        $limit = $length;
        $offset = $start;

        $jenis_pmh = $request->jenis_pmh;
        $seksi_konseptor = $request->seksi_konseptor;
        $pk_konseptor = $request->pk_konseptor;
        $jatuh_tempo = $request->jatuh_tempo;
        $tgl_awal = $request->tgl_awal;
        $tgl_akhir = $request->tgl_akhir;
        $status = $request->status;

        // dd($jenis_pmh,$seksi_konseptor,$pk_konseptor,$jatuh_tempo,$status);
        $jpm = '';
        if ($jenis_pmh != '-') {
            $jpm = " AND JENIS_PERMOHONAN = '" . $jenis_pmh . "'";
        }
        $sk = '';
        if ($seksi_konseptor != '-') {
            $sk = "AND SEKSI_KONSEPTOR LIKE '%" . $seksi_konseptor . "%'";
        }
        $pk = '';
        if ($pk_konseptor != '-') {
            $pk = "AND PK_KONSEPTOR = '" . $pk_konseptor . "'";
        }
        $stts = '';
        if ($status != '-') {
            $stts = "AND STATUS = '" . $status . "'";
        }
        $filter = $jpm . " " . $sk . " " . $pk . " " . $stts;
        // dd($filter);
        $jumlahTotal = PelaksanaBidang::where('status_dokumen', '<>', 'Delete')
            ->orWhereNull('status_dokumen')
            ->count();

        if ($search) { // filter data
            $where = " no_agenda like lower('%{$search}%') OR npwp like lower('%{$search}%')
            OR nama_wajib_pajak like lower('%{$search}%') OR jenis_permohonan like lower('%{$search}%')
            OR pajak like lower('%{$search}%') OR no_ketetapan like lower('%{$search}%')
            OR seksi_konseptor like lower('%{$search}%') OR progress like lower('%{$search}%') OR status like lower('%{$search}%')";
            $jumlahFiltered = PelaksanaBidang::whereRaw("{$where}")->count(); //hitung data yang telah terfilter
            if ($orderBy != null) {
                $data = PelaksanaBidang::whereRaw('(status_dokumen <> "Delete" OR status_dokumen IS NULL)')
                    ->whereRaw($where)->orderBy($orderBy, $orderType)->get();
            } else {
                $data = PelaksanaBidang::whereRaw('(status_dokumen <> "Delete" OR status_dokumen IS NULL)')
                    ->whereRaw($where)->get();
            }
        } else {
            $jumlahFiltered = $jumlahTotal;
            if ($orderBy != null) {
                $data = PelaksanaBidang::whereRaw('(status_dokumen <> "Delete" OR status_dokumen IS NULL) ' . $filter . '')
                    ->offset($offset)
                    ->limit($limit)->orderBy($orderBy, $orderType)->get();
            } else {
                $data = PelaksanaBidang::whereRaw('(status_dokumen <> "Delete" OR status_dokumen IS NULL) ' . $filter . '')
                    ->offset($offset)
                    ->limit($limit)
                    ->get();

                // $data = PelaksanaBidang::where('status_dokumen','<>','Delete')
                //     ->orWhereNull('status_dokumen')
                //     ->offset($offset)
                //     ->limit($limit)->get();
            }
        }
        $result = [];
        foreach ($data as $no => $dt) {
            $keberatan = stripos($dt->jenis_permohonan, 'keberatan');
            //MR != keberatan 3bln, = keberatan 8bln
            $tgl_diterima_kpp = date('d-m-Y', strtotime($dt->tgl_diterima_kpp));
            $d_mr = strtotime("+3 months", strtotime($dt->tgl_diterima_kpp));
            $d_mrk = strtotime("+8 months", strtotime($dt->tgl_diterima_kpp));
            //IKU != keberatan 5bln, = keberatan 10bln
            $d_iku = strtotime("+5 months", strtotime($dt->tgl_diterima_kpp));
            $d_ikuk = strtotime("+10 months", strtotime($dt->tgl_diterima_kpp));
            //KUP != keberatan 6bln, = keberatan 12bln
            $d_kup = strtotime("+6 months", strtotime($dt->tgl_diterima_kpp));
            $d_kupk = strtotime("+12 months", strtotime($dt->tgl_diterima_kpp));
            if ($keberatan != "") { // == keberatan
                $jt_mr = date("d-m-Y", $d_mrk);
                $jt_iku = date("d-m-Y", $d_ikuk);
                $jt_kup = date("d-m-Y", $d_kupk);
            } else { // !=keberatan
                $jt_mr = date("d-m-Y", $d_mr);
                $jt_iku = date("d-m-Y", $d_iku);
                $jt_kup = date("d-m-Y", $d_kup);
            }

            if ($dt->status == 'Selesai') {
                $badge = 'badge-success';
            } else if ($dt->status == 'Tunggakan') {
                $badge = 'badge-danger';
            } else if ($dt->status == 'Kembali') {
                $badge = 'badge-warning';
            } else {
                $badge = 'badge-info';
            }
            $status = '<center>
                            <span class="badge ' . $badge . '">' . $dt->status . '</span>
                        </center>';

            $linkedit = url('/permohonan/pelaksana-bidang?mode=edit&no=' . base64_encode($dt->no_agenda) . '&readonly=1');
            $action = '<center>
                            <a href="' . $linkedit . '" target="_blank">
                                <button data-toggle="tooltip" title="Lihat Dokumen" type="button" class="btn btn-xs btn-primary btn-circle"><i class="fas fa-search"></i></button>
                            </a>
                        </center>';

            $result[] = [
                $start + $no + 1,
                $dt->no_agenda,
                $tgl_diterima_kpp,
                $dt->npwp,
                $dt->nama_wajib_pajak,
                $dt->jenis_permohonan,
                $dt->no_ketetapan,
                $dt->seksi_konseptor,
                $dt->kepala_seksi,
                $dt->pk_konseptor,
                $status,
                $jt_mr,
                $jt_iku,
                $jt_kup,
                $action,
            ];
        }
        echo json_encode(
            array(
                'draw' => $draw,
                'recordsTotal' => $jumlahTotal,
                'recordsFiltered' => $jumlahFiltered,
                'data' => $result,
            )
        );
    }

    public function print(Request $request)
    {
        $jenis_pmh = $request->jenis_pmh;
        $seksi_konseptor = $request->seksi_konseptor;
        $pk_konseptor = $request->pk_konseptor;
        $jatuh_tempo = $request->jatuh_tempo;
        $tgl_awal = $request->tgl_awal;
        $tgl_akhir = $request->tgl_akhir;
        $status = $request->status;

        // dd($jenis_pmh,$seksi_konseptor,$pk_konseptor,$jatuh_tempo,$status);
        $jpm = '';
        if ($jenis_pmh != '-') {
            $jpm = " AND JENIS_PERMOHONAN = '" . $jenis_pmh . "'";
        }
        $sk = '';
        if ($seksi_konseptor != '-') {
            $sk = "AND SEKSI_KONSEPTOR LIKE '%" . $seksi_konseptor . "%'";
        }
        $pk = '';
        if ($pk_konseptor != '-') {
            $pk = "AND PK_KONSEPTOR = '" . $pk_konseptor . "'";
        }
        $stts = '';
        if ($status != '-') {
            $stts = "AND STATUS = '" . $status . "'";
        }
        $filter = $jpm . " " . $sk . " " . $pk . " " . $stts;

        $data = PelaksanaBidang::whereRaw('(status_dokumen <> "Delete" OR status_dokumen IS NULL) ' . $filter . '')->get();
        // dd($data);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);

        $sheet->setCellValue('E1', 'E-REPORTING PERMOHONAN KEBERATAN DAN NON KEBERATAN');
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
        $sheet->getColumnDimension('I')->setWidth(21);
        $sheet->getColumnDimension('J')->setWidth(16);
        $sheet->getColumnDimension('K')->setWidth(16);
        $sheet->getColumnDimension('L')->setWidth(16);
        $sheet->getColumnDimension('M')->setWidth(16);
        //header end
        //DETAILS

        $sheet->setCellValue('A6', 'No Agenda');
        $sheet->setCellValue('B6', 'Tanggal diterima KPP');
        $sheet->setCellValue('C6', 'NPWP');
        $sheet->setCellValue('D6', 'Nama Wajib Pajak');
        $sheet->setCellValue('E6', 'Jenis Permohonan');
        $sheet->setCellValue('F6', 'No Ketetapan');
        $sheet->setCellValue('G6', 'Seksi Konseptor');
        $sheet->setCellValue('H6', 'Kepala Seksi');
        $sheet->setCellValue('I6', 'PK Konseptor');
        $sheet->setCellValue('J6', 'Status');
        $sheet->setCellValue('K6', 'Jatuh Tempo');
        $sheet->setCellValue('K7', 'MR');
        $sheet->setCellValue('L7', 'KUP');
        $sheet->setCellValue('M7', 'IKU');

        $sheet->mergeCells("K6:M6");
        $table_hr = [
            'borders' => [
                'bottom' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];
        $spreadsheet->getActiveSheet()->getStyle('A6:M6')->getFont()->getColor()->setARGB('FFFFFFFF');
        $spreadsheet->getActiveSheet()->getStyle('K7:M7')->getFont()->getColor()->setARGB('FFFFFFFF');
        $spreadsheet->getActiveSheet()->getStyle('K6:M6')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF008000');
        $spreadsheet->getActiveSheet()->getStyle('K7:M7')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF008000');
        $sheet->getStyle('A6:M6')->applyFromArray($fontTitle);
        $sheet->getStyle('K7:M7')->applyFromArray($fontTitle);

        $sheet->getRowDimension('4')->setRowHeight(20);
        $last_row = 7;
        foreach ($data as $key => $dt) {
            $keberatan = stripos($dt->jenis_permohonan, 'keberatan');
            //MR != keberatan 3bln, = keberatan 8bln
            $tgl_diterima_kpp = date('d-m-Y', strtotime($dt->tgl_diterima_kpp));
            $d_mr = strtotime("+3 months", strtotime($dt->tgl_diterima_kpp));
            $d_mrk = strtotime("+8 months", strtotime($dt->tgl_diterima_kpp));
            //IKU != keberatan 5bln, = keberatan 10bln
            $d_iku = strtotime("+5 months", strtotime($dt->tgl_diterima_kpp));
            $d_ikuk = strtotime("+10 months", strtotime($dt->tgl_diterima_kpp));
            //KUP != keberatan 6bln, = keberatan 12bln
            $d_kup = strtotime("+6 months", strtotime($dt->tgl_diterima_kpp));
            $d_kupk = strtotime("+12 months", strtotime($dt->tgl_diterima_kpp));
            if ($keberatan != "") { // == keberatan
                $jt_mr = date("d-m-Y", $d_mrk);
                $jt_iku = date("d-m-Y", $d_ikuk);
                $jt_kup = date("d-m-Y", $d_kupk);
            } else { // !=keberatan
                $jt_mr = date("d-m-Y", $d_mr);
                $jt_iku = date("d-m-Y", $d_iku);
                $jt_kup = date("d-m-Y", $d_kup);
            }
            $last_row++;
            $sheet->setCellValue('A' . $last_row, $dt->no_agenda);
            $sheet->setCellValue('B' . $last_row, $tgl_diterima_kpp);
            $sheet->setCellValue('C' . $last_row, $dt->npwp);
            $sheet->setCellValue('D' . $last_row, $dt->nama_wajib_pajak);
            $sheet->setCellValue('E' . $last_row, $dt->jenis_permohonan);
            $sheet->setCellValue('F' . $last_row, $dt->no_ketetapan);
            $sheet->setCellValue('G' . $last_row, $dt->seksi_konseptor);
            $sheet->setCellValue('H' . $last_row, $dt->kepala_seksi);
            $sheet->setCellValue('I' . $last_row, $dt->pk_konseptor);
            $sheet->setCellValue('J' . $last_row, $dt->status);
            $sheet->setCellValue('K' . $last_row, $jt_mr);
            $sheet->setCellValue('L' . $last_row, $jt_iku);
            $sheet->setCellValue('M' . $last_row, $jt_kup);
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

        $sheet->getStyle('A6:J6')->applyFromArray($header_style_border);
        $sheet->getStyle('A7:J7')->applyFromArray($header_style_border);

        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="E-Reporting Permohonan.xls');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        $writer = new Xls($spreadsheet);
        $writer->save("php://output");
        header('Content-Disposition: attachment; filename="E-Reporting Permohonan.xls');
    }
}
