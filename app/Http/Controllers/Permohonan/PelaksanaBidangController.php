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
use App\Models\PelaksanaBidang;
use App\Models\Status;
use App\Models\Progress;
use App\Models\SeksiKonseptor;
use App\Models\KategoriPermohonan;
use App\Models\KriteriaPermohonan;
use App\Models\User;
use DB;
//use Illuminate\Support\Facades\DB;

use PhpOffice\PhpSpreadsheet\Reader\Html;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PelaksanaBidangController extends Controller
{
    protected $PATH_VIEW = 'permohonan.PelaksanaBidang.';

    public function __construct()
    {
        // $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $data = [];
        // $user = Auth::user();
        $user = User::where('user_id', session('user_id'))->first();
        $dtAsalPermohonan = AsalPermohonan::get();
        $dtJnsPermohonan = JenisPermohonan::get();
        $dtPajak = Pajak::get();
        $dtKetetapan = Ketetapan::get();
        $dtStatus = Status::where('status', '<>', 'Diarsipkan')->where('status', '<>', 'Ditindaklanjuti')->get();
        $dtProgress = Progress::get();
        $dtSeksiKonsep = SeksiKonseptor::get();
        $dtKatPermohonan = KategoriPermohonan::get();
        $mode = $request->mode;
        $data["mode"] = $mode;
        $no_agenda = base64_decode($request->no);
        $data["no_agenda"] = $no_agenda;
        $dtPB = PelaksanaBidang::where('no_agenda', '=', $no_agenda)->first();
        $dtKriteria = KriteriaPermohonan::get();
        if (empty($dtPB)) {
            $dtPB = new PelaksanaBidang;
        } else {
            $dtPB = PelaksanaBidang::where('no_agenda', '=', $no_agenda)->first();
        }
        //set otomatis no agenda
        // 7 digit angka depan = nomor urut, 2 digit angka belakang = tahun P-0000001-21
        $datenow = substr(date('Y'), -2);
        $tahun = date('Y');
        // $maxval = PelaksanaBidang::max('no_agenda');
        $no_agenda = "P-0000001-" . $datenow;
        $maxval = PelaksanaBidang::where('tahun', $tahun)->max('no');
        if ($maxval) {
            // $noUrut = substr($maxval,2,7);
            $noUrut = $maxval;
            $noInt = (int)$noUrut;
            $noInt++;
            $no_agenda = "P-" . str_pad($noInt, 7, "0",  STR_PAD_LEFT) . "-" . $datenow;
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
        $data['dtKriteria'] = $dtKriteria;

        return view($this->PATH_VIEW . 'index', $data);
    }
    public function store(Request $request)
    {
        $mode = strtolower($request->input("mode"));
        $results = $request->all();
        $error = 0;
        DB::beginTransaction();
        if ($mode == "edit") {
            $no_agenda = $request->no_agenda;
            $pb = PelaksanaBidang::where('no_agenda', $no_agenda)->first();
            try {
                $data = PelaksanaBidang::updateDt($request, $pb);
            } catch (\Exception $e) {
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
        } else {
            try {
                $data = PelaksanaBidang::create($request);
            } catch (\Exception $e) {
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
        if ($error == 0) {
            DB::commit();
            if ($mode == 'add') {
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => $request->no_agenda . ' telah ditambahkan!',
                ];
            } else {
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => $request->no_agenda . ' telah diupdated!',
                ];
            }
        }

        $data["flashs"] = $flashs;
        // return redirect()->back()->with($data);
        if ($mode == "edit") {
            return redirect()->back();
        } else {
            return redirect()->route('pelaksanabidang.index');
        }
    }
    public function browse(Request $request)
    {
        $user = Auth::user();
        $page = \Request::get('page');
        $data['user'] = $user;
        return view($this->PATH_VIEW . 'browse', $data);
    }

    public function datatablePB(Request $request)
    {
        $user = User::where('user_id', session('user_id'))->first();
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
                $data = PelaksanaBidang::where('status_dokumen', '<>', 'Delete')
                    ->orWhereNull('status_dokumen')
                    ->offset($offset)
                    ->limit($limit)->orderBy($orderBy, $orderType)->get();
            } else {
                $data = PelaksanaBidang::where('status_dokumen', '<>', 'Delete')
                    ->orWhereNull('status_dokumen')
                    ->offset($offset)
                    ->limit($limit)->get();
            }
        }
        $result = [];
        foreach ($data as $no => $dt) {
            $linkedit = url('/permohonan/pelaksana-bidang?mode=edit&no=' . base64_encode($dt->no_agenda));
            $linkdelete = url('/permohonan/pelaksana-bidang/delete', base64_encode($dt->no_agenda));
            if ($user->peran == 'Eksekutor') {
                $action = '<center>
                            <a href="' . $linkedit . '">
                                <button data-toggle="tooltip" data-original-title="Edit" type="button" class="btn btn-xs btn-primary btn-circle"><i class="fas fa-pencil-alt"></i></button>
                            </a>
                            </center>';
            } else if ($user->peran == 'Forecaster') {
                $action = '<center>
                            <a href="' . $linkedit . '">
                                <button data-toggle="tooltip" data-original-title="Edit" type="button" class="btn btn-xs btn-primary btn-circle"><i class="fas fa-pencil-alt"></i></button>
                            </a>
                            <button onclick="buttonDelete(this)" data-link="' . $linkdelete . '" data-toggle="tooltip" data-original-title="Delete" type="button" class="btn btn-xs btn-default btn-circle"><i class="fas fa-trash"></i></button>
                        </center>';
            }

            if ($dt->progress == 'Final') {
                $badge = 'badge-success';
            } else if ($dt->progress == 'Proses') {
                $badge = 'badge-primary';
            } else {
                $badge = 'badge-info';
            }
            $progress = '<center>
                            <span class="badge ' . $badge . '">' . $dt->progress . '</span>
                        </center>';

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
            $result[] = [
                $start + $no + 1,
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
            array(
                'draw' => $draw,
                'recordsTotal' => $jumlahTotal,
                'recordsFiltered' => $jumlahFiltered,
                'data' => $result,
            )
        );
    }

    public function delete($no_agenda)
    {
        $no_agenda = base64_decode($no_agenda);
        PelaksanaBidang::where('no_agenda', $no_agenda)->update(['status_dokumen' => 'Delete']);
        return redirect()->back();
    }

    public function print($no_agenda)
    {
        $noagen = base64_decode($no_agenda);
        $data = PelaksanaBidang::where('no_agenda', '=', $noagen)->first();
        if (!$data) {
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
        $sheet->getColumnDimension('N')->setWidth(16);
        $sheet->getColumnDimension('O')->setWidth(16);
        $sheet->getColumnDimension('P')->setWidth(16);
        $sheet->getColumnDimension('Q')->setWidth(16);
        $sheet->getColumnDimension('R')->setWidth(16);
        $sheet->getColumnDimension('S')->setWidth(16);
        $sheet->getColumnDimension('T')->setWidth(16);
        $sheet->getColumnDimension('U')->setWidth(16);
        $sheet->getColumnDimension('V')->setWidth(16);
        $sheet->getColumnDimension('W')->setWidth(16);
        $sheet->getColumnDimension('X')->setWidth(16);
        $sheet->getColumnDimension('Y')->setWidth(16);

        $sheet->setCellValue('A1', 'No Agenda');
        $sheet->setCellValue('B1', 'Tanggal Agenda');
        $sheet->setCellValue('C1', 'Nomor Naskah Dinas');
        $sheet->setCellValue('D1', 'Tanggal Naskah Dinas');
        $sheet->setCellValue('E1', 'Asal Permohonan');
        $sheet->setCellValue('F1', 'Nomor Lembar Pengawasan Arus Dokumen');
        $sheet->setCellValue('G1', 'Tanggal diterima KPP');
        $sheet->setCellValue('H1', 'Tanggal diterima Kanwil');
        $sheet->setCellValue('I1', 'Nomor Pokok Wajib Pajak');
        $sheet->setCellValue('J1', 'Nama Wajib Pajak');
        $sheet->setCellValue('K1', 'Jenis Permohonan');
        $sheet->setCellValue('L1', 'Jenis Pajak');
        $sheet->setCellValue('M1', 'Jenis Ketetapan');
        $sheet->setCellValue('N1', 'Nomor Ketetapan');
        $sheet->setCellValue('O1', 'Tanggal Ketetapan');
        $sheet->setCellValue('P1', 'Masa Pajak');
        $sheet->setCellValue('Q1', 'Tahun Pajak');
        $sheet->setCellValue('R1', 'Kategori Permohonan');
        $sheet->setCellValue('S1', 'Nomor Surat Permohonan');
        $sheet->setCellValue('T1', 'Tanggal Surat Permohonan');
        $sheet->setCellValue('U1', 'Seksi Konseptor');
        $sheet->setCellValue('V1', 'Status');
        $sheet->setCellValue('W1', 'Progress');
        $sheet->setCellValue('X1', 'Jumlah Pembayaran a/ PMK-29 & PMK-91');
        $sheet->setCellValue('Y1', 'Tanggal Pembayaran');

        $spreadsheet->getActiveSheet()->getStyle('A1:Y1')->getFont()->getColor()->setARGB('FFFFFFFF');
        $sheet->getStyle('A1:Y1')->applyFromArray($fontTitle);
        $sheet->getRowDimension('4')->setRowHeight(20);

        $sheet->setCellValue('A2', $data->no_agenda);
        $sheet->setCellValue('B2', $data->tgl_agenda);
        $sheet->setCellValue('C2', $data->no_naskah_dinas);
        $sheet->setCellValue('D2', date("d-m-Y", strtotime($data->tgl_naskah_dinas)));
        $sheet->setCellValue('E2', $data->pemohon);
        $sheet->setCellValue('F2', $data->no_lbr_pengawas_dok);
        $sheet->setCellValue('G2', date("d-m-Y", strtotime($data->tgl_diterima_kpp)));
        $sheet->setCellValue('H2', date("d-m-Y", strtotime($data->tgl_diterima_kanwil)));
        $sheet->setCellValue('I2', $data->npwp);
        $sheet->setCellValue('J2', $data->nama_wajib_pajak);
        $sheet->setCellValue('K2', $data->jenis_permohonan);
        $sheet->setCellValue('L2', $data->pajak);
        $sheet->setCellValue('M2', $data->jenis_ketetapan);
        $sheet->setCellValue('N2', $data->no_ketetapan);
        $sheet->setCellValue('O2', date("d-m-Y", strtotime($data->tgl_ketetapan)));
        $sheet->setCellValue('P2', $data->masa_pajak);
        $sheet->setCellValue('Q2', $data->tahun_pajak);
        $sheet->setCellValue('R2', $data->kat_permohonan);
        $sheet->setCellValue('S2', $data->no_srt_permohonan);
        $sheet->setCellValue('T2', date("d-m-Y", strtotime($data->tgl_srt_permohonan)));
        $sheet->setCellValue('U2', $data->seksi_konseptor);
        $sheet->setCellValue('V2', $data->status);
        $sheet->setCellValue('W2', $data->progress);
        $sheet->setCellValue('X2', $data->jumlah_byr_pmk);
        $sheet->setCellValue('Y2', date("d-m-Y", strtotime($data->tgl_byr_pmk)));

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
        $sheet->getStyle('A1:Y1')->applyFromArray($header_style_border);
        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="Forecaster ' . $noagen . '.xls');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        $writer = new Xls($spreadsheet);
        $writer->save("php://output");
        header('Content-Disposition: attachment; filename="Forecaster ' . $noagen . '.xls');
    }

    public function printAll()
    {
        $dataAll = PelaksanaBidang::get();
        if (!$dataAll) {
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
        $sheet->getColumnDimension('N')->setWidth(16);
        $sheet->getColumnDimension('O')->setWidth(16);
        $sheet->getColumnDimension('P')->setWidth(16);
        $sheet->getColumnDimension('Q')->setWidth(16);
        $sheet->getColumnDimension('R')->setWidth(16);
        $sheet->getColumnDimension('S')->setWidth(16);
        $sheet->getColumnDimension('T')->setWidth(16);
        $sheet->getColumnDimension('U')->setWidth(16);
        $sheet->getColumnDimension('V')->setWidth(16);
        $sheet->getColumnDimension('W')->setWidth(16);
        $sheet->getColumnDimension('X')->setWidth(16);
        $sheet->getColumnDimension('Y')->setWidth(16);

        $sheet->setCellValue('A1', 'No Agenda');
        $sheet->setCellValue('B1', 'Tanggal Agenda');
        $sheet->setCellValue('C1', 'Nomor Naskah Dinas');
        $sheet->setCellValue('D1', 'Tanggal Naskah Dinas');
        $sheet->setCellValue('E1', 'Asal Permohonan');
        $sheet->setCellValue('F1', 'Nomor Lembar Pengawasan Arus Dokumen');
        $sheet->setCellValue('G1', 'Tanggal diterima KPP');
        $sheet->setCellValue('H1', 'Tanggal diterima Kanwil');
        $sheet->setCellValue('I1', 'Nomor Pokok Wajib Pajak');
        $sheet->setCellValue('J1', 'Nama Wajib Pajak');
        $sheet->setCellValue('K1', 'Jenis Permohonan');
        $sheet->setCellValue('L1', 'Jenis Pajak');
        $sheet->setCellValue('M1', 'Jenis Ketetapan');
        $sheet->setCellValue('N1', 'Nomor Ketetapan');
        $sheet->setCellValue('O1', 'Tanggal Ketetapan');
        $sheet->setCellValue('P1', 'Masa Pajak');
        $sheet->setCellValue('Q1', 'Tahun Pajak');
        $sheet->setCellValue('R1', 'Kategori Permohonan');
        $sheet->setCellValue('S1', 'Nomor Surat Permohonan');
        $sheet->setCellValue('T1', 'Tanggal Surat Permohonan');
        $sheet->setCellValue('U1', 'Seksi Konseptor');
        $sheet->setCellValue('V1', 'Status');
        $sheet->setCellValue('W1', 'Progress');
        $sheet->setCellValue('X1', 'Jumlah Pembayaran a/ PMK-29 & PMK-91');
        $sheet->setCellValue('Y1', 'Tanggal Pembayaran');

        $spreadsheet->getActiveSheet()->getStyle('A1:Y1')->getFont()->getColor()->setARGB('FFFFFFFF');
        $sheet->getStyle('A1:Y1')->applyFromArray($fontTitle);
        $sheet->getRowDimension('4')->setRowHeight(20);
        $last_row = 1;
        foreach ($dataAll as $data) {
            $last_row++;
            $sheet->setCellValue('A' . $last_row, $data->no_agenda);
            $sheet->setCellValue('B' . $last_row, $data->tgl_agenda);
            $sheet->setCellValue('C' . $last_row, $data->no_naskah_dinas);
            $sheet->setCellValue('D' . $last_row, date("d-m-Y", strtotime($data->tgl_naskah_dinas)));
            $sheet->setCellValue('E' . $last_row, $data->pemohon);
            $sheet->setCellValue('F' . $last_row, $data->no_lbr_pengawas_dok);
            $sheet->setCellValue('G' . $last_row, date("d-m-Y", strtotime($data->tgl_diterima_kpp)));
            $sheet->setCellValue('H' . $last_row, date("d-m-Y", strtotime($data->tgl_diterima_kanwil)));
            $sheet->setCellValue('I' . $last_row, $data->npwp);
            $sheet->setCellValue('J' . $last_row, $data->nama_wajib_pajak);
            $sheet->setCellValue('K' . $last_row, $data->jenis_permohonan);
            $sheet->setCellValue('L' . $last_row, $data->pajak);
            $sheet->setCellValue('M' . $last_row, $data->jenis_ketetapan);
            $sheet->setCellValue('N' . $last_row, $data->no_ketetapan);
            $sheet->setCellValue('O' . $last_row, date("d-m-Y", strtotime($data->tgl_ketetapan)));
            $sheet->setCellValue('P' . $last_row, $data->masa_pajak);
            $sheet->setCellValue('Q' . $last_row, $data->tahun_pajak);
            $sheet->setCellValue('R' . $last_row, $data->kat_permohonan);
            $sheet->setCellValue('S' . $last_row, $data->no_srt_permohonan);
            $sheet->setCellValue('T' . $last_row, date("d-m-Y", strtotime($data->tgl_srt_permohonan)));
            $sheet->setCellValue('U' . $last_row, $data->seksi_konseptor);
            $sheet->setCellValue('V' . $last_row, $data->status);
            $sheet->setCellValue('W' . $last_row, $data->progress);
            $sheet->setCellValue('X' . $last_row, $data->jumlah_byr_pmk);
            $sheet->setCellValue('Y' . $last_row, date("d-m-Y", strtotime($data->tgl_byr_pmk)));
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
        $sheet->getStyle('A1:Y1')->applyFromArray($header_style_border);
        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="Database Forecaster.xls');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        $writer = new Xls($spreadsheet);
        $writer->save("php://output");
        header('Content-Disposition: attachment; filename="Database Forecaster.xls');
    }
}
