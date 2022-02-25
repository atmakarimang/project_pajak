<?php

namespace App\Http\Controllers\BandingGugatan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; // baru
use App\Models\DevError;
use App\Models\Project;
use App\Models\AsalPermohonan;
use App\Models\JenisPermohonan;
use App\Models\KategoriPermohonan;

use App\Models\Pajak;
use App\Models\AmarPutusan;
use App\Models\PetugasSidangBanding;
use App\Models\PelaksanaEksekutor;
use App\Models\BandingGugatan;
use App\Models\BandingGugatanChild;
use App\Models\User;
use DB;

use PhpOffice\PhpSpreadsheet\Reader\Html;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BandingGugatanController extends Controller
{
    protected $PATH_VIEW = 'bandinggugatan.';

    public function __construct()
    {
        // $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $data = [];
        // $user = Auth::user();
        $user = User::where('user_id', session('user_id'))->first();
        $mode = $request->mode;
        $dtPajak = Pajak::get();
        $dtAmarPutusan = AmarPutusan::get();
        $dtPtgSidang = PetugasSidangBanding::get();
        $dtPkEksekutor = PelaksanaEksekutor::get();
        $id_bg = base64_decode($request->no);
        $dtBG = BandingGugatan::where('id_bg', $id_bg)->first();
        if (empty($dtBG)) {
            $dtBG = new BandingGugatan;
            $dtChild = new BandingGugatanChild;
        } else {
            $dtBG = BandingGugatan::where('id_bg', $id_bg)->first();
            $dtChild = BandingGugatanChild::where('id_bg', $id_bg)->get();
        }
        // dd($dtChild);
        // dd($dtBG->BGchild);
        $data["mode"] = $mode;
        $data['user'] = $user;
        $data['dtPajak'] = $dtPajak;
        $data['dtAmarPutusan'] = $dtAmarPutusan;
        $data['dtPtgSidang'] = $dtPtgSidang;
        $data['dtPkEksekutor'] = $dtPkEksekutor;
        $data['id_bg'] = $id_bg;
        $data['dtBG'] = $dtBG;
        $data['dtChild'] = $dtChild;

        return view($this->PATH_VIEW . 'index', $data);
    }
    public function store(Request $request)
    {
        // $user = Auth::user();
        $user = User::where('user_id', session('user_id'))->first();
        $mode = strtolower($request->input("mode"));
        $results = $request->all();
        $error = 0;
        DB::beginTransaction();
        // dd($mode);
        if ($mode == "edit") {
            $id_bg = $results['id_bg'];
            $data = BandingGugatan::where('id_bg', '=', $id_bg)->first();
            // try {
            if ($user->peran == "Eksekutor") {
                $data->tanggal_putusan = date('Y-m-d', strtotime($request->tgl_ucp_pts));
                $data->amar_putusan = $request->amar_putusan;
                $data->nilai = $request->nilai;
                $data->keterangan = $request->keterangan;
                if (!empty($request->pet_sidang)) {
                    $pet_sidang = implode(",", $request->pet_sidang);
                    $data->petugas_sidang = $pet_sidang;
                }
                if (!empty($request->pel_eksekutor)) {
                    $pel_eksekutor = implode(",", $request->pel_eksekutor);
                    $data->pelaksana_eksekutor = $pel_eksekutor;
                }
                $data->no_bidang = $request->no_bidang;
                $data->tgl_objek = date('Y-m-d', strtotime($request->tgl_objek));
                $data->save();
                BandingGugatanChild::where('id_bg', $id_bg)->delete();
                foreach ($request->urt_sidang as $key => $dt) {
                    $dataChild = new BandingGugatanChild();
                    $dataChild->id_bg = $id_bg;
                    $dataChild->sidang_ke = $request->urt_sidang[$key];
                    $dataChild->tanggal_sidang = date('Y-m-d', strtotime($request->tgl_sidang[$key]));
                    $dataChild->status_sidang = empty($request->status_sidang[$key]) ? "" : $request->status_sidang[$key];
                    $dataChild->save();
                }
            } else if ($user->peran == "Forecaster") {
                $data = BandingGugatan::updateDt($request, $data);
            }
            // }catch(\Exception $e) {
            //     $devError = new DevError;
            //     $devError->form = "Update Banding Gugatan";
            //     $devError->url = $request->path();
            //     $devError->error = $e;
            //     $devError->data = json_encode($request->input());
            //     $devError->created_at = date("Y:m:d H:i:s");
            //     $devError->save();
            //     DB::commit();
            //     $error++;
            //     DB::rollBack();
            //     $flashs[] = [
            //         'type' => 'error', // option : info, warning, success, error
            //         'title' => 'Error',
            //         'message' => "Banding Gugatan gagal diupdated!",
            //     ];
            // }
        } else {
            // try {
            $data = BandingGugatan::create($request);
            // }catch(\Exception $e) {
            // 	$devError = new DevError;
            //     $devError->form = "Add Banding Gugatan";
            //     $devError->url = $request->path();
            //     $devError->error = $e;
            //     $devError->data = json_encode($request->input());
            //     $devError->created_at = date("Y:m:d H:i:s");
            //     $devError->save();
            //     DB::commit();
            //     $error++;
            //     DB::rollBack();
            //     $flashs[] = [
            //         'type' => 'error', // option : info, warning, success, error
            //         'title' => 'Error',
            //         'message' => "Banding Gugatan gagal disimpan!",
            //     ];
            // }
        }

        if ($error == 0) {
            DB::commit();
            if ($mode == 'add') {
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => 'Banding Gugatan ' . $request->majelis . ' telah ditambahkan!',
                ];
            } else {
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => 'Banding Gugatan ' . $request->majelis . ' telah diupdated!',
                ];
            }
        }

        $data["flashs"] = $flashs;
        // return redirect()->back()->with($data);
        return redirect()->route('bandinggugatan.index');
    }
    public function browse(Request $request)
    {
        $user = Auth::user();
        $page = \Request::get('page');
        $data['user'] = $user;
        return view($this->PATH_VIEW . 'browse', $data);
    }
    public function datatable(Request $request)
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

        $jumlahTotal = BandingGugatan::count();

        if ($search) { // filter data
            $where = " majelis like lower('%{$search}%') OR no_sengketa like lower('%{$search}%')
            OR nama_wajib_pajak like lower('%{$search}%') OR objek_bg like lower('%{$search}%')
            OR petugas_sidang like lower('%{$search}%') OR pelaksana_eksekutor like lower('%{$search}%')";
            $jumlahFiltered = BandingGugatan::whereRaw("{$where}")->count(); //hitung data yang telah terfilter
            if ($orderBy != null) {
                $data = BandingGugatan::whereRaw($where)->orderBy($orderBy, $orderType)->get();
            } else {
                $data = BandingGugatan::whereRaw($where)->get();
            }
        } else {
            $jumlahFiltered = $jumlahTotal;
            if ($orderBy != null) {
                $data = BandingGugatan::offset($offset)->limit($limit)->orderBy($orderBy, $orderType)->get();
            } else {
                $data = BandingGugatan::offset($offset)->limit($limit)->get();
            }
        }
        $result = [];
        foreach ($data as $no => $dt) {
            $linkedit = url('/banding-gugatan?mode=edit&no=' . base64_encode($dt->id_bg));
            $linkdelete = url('/banding-gugatan/delete', base64_encode($dt->id_bg));
            if ($user->peran == "Eksekutor") {
                $action = '<center>
                            <a href="' . $linkedit . '">
                                <button data-toggle="tooltip" title="Edit" type="button" class="btn btn-xs btn-primary btn-circle"><i class="fas fa-pencil-alt"></i></button>
                            </a>
                            </center>';
            } else {
                $action = '<center>
                            <a href="' . $linkedit . '">
                                <button data-toggle="tooltip" title="Edit" type="button" class="btn btn-xs btn-primary btn-circle"><i class="fas fa-pencil-alt"></i></button>
                            </a>
                            <button onclick="buttonDelete(this)" data-link="' . $linkdelete . '" data-toggle="tooltip" title="Delete" type="button" class="btn btn-xs btn-default btn-circle"><i class="fas fa-trash"></i></button>
                        </center>';
            }
            $get_status = BandingGugatanChild::where('id_bg', $dt->id_bg)->max('sidang_ke');
            $status = BandingGugatanChild::where('id_bg', $dt->id_bg)->where('sidang_ke', $get_status)->first();
            if ($status != null) {
                $statusnya = $status['status_sidang'];
            } else {
                $statusnya = '';
            }
            $result[] = [
                $start + $no + 1,
                $dt->majelis,
                $dt->no_sengketa,
                $dt->nama_wajib_pajak,
                $dt->objek_bg,
                $dt->petugas_sidang,
                $dt->pelaksana_eksekutor,
                $statusnya,
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

    public function delete($id_bg)
    {
        $id_bg = base64_decode($id_bg);
        BandingGugatanChild::where('id_bg', $id_bg)->delete();
        BandingGugatan::where('id_bg', $id_bg)->delete();
        return redirect()->back();
    }

    public function print()
    {
        $data = BandingGugatan::get();
        if (!$data) {
            // $msg = notifErrorHelper('Wrong Action','Error');
            // return redirect()->back()->with('flashs',$msg);
            return redirect()->back();
        }
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $fontTitle = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ]
        ];

        $sheet->getColumnDimension('A')->setWidth(21);
        $sheet->getColumnDimension('B')->setWidth(21);
        $sheet->getColumnDimension('C')->setWidth(21);
        $sheet->getColumnDimension('D')->setWidth(21);
        $sheet->getColumnDimension('E')->setWidth(35);
        $sheet->getColumnDimension('F')->setWidth(35);

        $sheet->setCellValue('A1', 'Majelis');
        $sheet->setCellValue('B1', 'No Sengketa');
        $sheet->setCellValue('C1', 'Nama Wajib Pajak');
        $sheet->setCellValue('D1', 'Objek Banding Gugatan');
        $sheet->setCellValue('E1', 'Petugas Sidang');
        $sheet->setCellValue('F1', 'Pelaksana Eksekutor');
        $sheet->setCellValue('B2', 'Sidang ke');
        $sheet->setCellValue('C2', 'Tanggal');
        $sheet->setCellValue('D2', 'Status');


        $last_row = $sheet->getHighestRow();
        foreach ($data as $key => $dt) {
            $last_row++;
            $sheet->setCellValue('A' . $last_row, $dt->majelis);
            $sheet->setCellValue('B' . $last_row, $dt->no_sengketa);
            $sheet->setCellValue('C' . $last_row, $dt->nama_wajib_pajak);
            $sheet->setCellValue('D' . $last_row, $dt->objek_bg);
            $sheet->setCellValue('E' . $last_row, $dt->petugas_sidang);
            $sheet->setCellValue('F' . $last_row, $dt->pelaksana_eksekutor);
            $sheet->getStyle('A' . $last_row . ':F' . $last_row)->applyFromArray($fontTitle);

            $last_rowOF = $last_row;
            $bgChild = BandingGugatanChild::where('id_bg', $dt->id_bg)->get();
            $countChild = count($bgChild);
            for ($x = 0; $x < $countChild; $x++) {
                $last_rowOF++;
                $last_row++;
                $sheet->setCellValue('B' . $last_rowOF, $bgChild[$x]->sidang_ke);
                $sheet->setCellValue('C' . $last_rowOF, $bgChild[$x]->tanggal_sidang);
                $sheet->setCellValue('D' . $last_rowOF, $bgChild[$x]->status_sidang);
            }
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
        $sheet->getStyle('A1:F1')->applyFromArray($fontTitle);
        $sheet->getStyle('B2:D2')->applyFromArray($fontTitle);

        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename=Banding Gugatan.xls');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        $writer = new Xls($spreadsheet);
        $writer->save("php://output");
        header('Content-Disposition: attachment; filename=Banding Gugatan.xls');
    }
}
