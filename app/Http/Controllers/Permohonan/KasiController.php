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
use App\Models\KriteriaPermohonan;
use App\Models\PenelaahKeberatan;
use App\Models\User;
use DB;
use Session;

use PhpOffice\PhpSpreadsheet\Reader\Html;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpWord\Style\Table;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use RealRashid\SweetAlert\Facades\Alert;

class KasiController extends Controller
{
    protected $PATH_VIEW = 'permohonan.Kasi.';

    public function __construct()
    {
        // $this->middleware('auth');
    }
    public function index(Request $request)
    {
        // Session::flash('backUrl', Request::fullUrl());
        // -- Start Search
        $id_session = "schkey:" . $request->session()->getId();
        $chck_redirect1 = "KasiRedirect:" . $request->session()->getId();
        $chck_redirect2 = $request->session()->get($chck_redirect1);

        //check refresh atau reload
        $pageWasRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0';

        if ($pageWasRefreshed) {
            if ($chck_redirect2 == 'Ya') {
                $search_key = $request->session()->get($id_session);
            } else {
                $search_key = '';
            }
        } else {
            $request->session()->forget($id_session);
            $request->session()->forget($chck_redirect1);
            $search_key = '';
        }

        $data = [];
        $user = Auth::user();
        $mode = $request->mode;
        $data["mode"] = $mode;
        $data['user'] = $user;
        $data['active'] = 'Eksekutor';
        $data['search'] = $search_key;

        //return view($this->PATH_VIEW . 'index', $data);
        return view($this->PATH_VIEW . 'index', ["data" => $data]);
    }

    public function searchKS(Request $request)
    {
        $user = User::where('user_id', session('user_id'))->first();
        $start = $request->input('start');
        $length = $request->input('length');
        $draw = $request->input('draw');
        $search_arr = $request->input('search');

        //search
        $id_session = "schkey:" . $request->session()->getId();
        $search = $request->searchks;

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

        $request->session()->put($id_session, $search);

        if ($search) { // filter data
            $where = " no_agenda like lower('%{$search}%') OR npwp like lower('%{$search}%')
            OR nama_wajib_pajak like lower('%{$search}%') OR jenis_permohonan like lower('%{$search}%')
            OR no_ketetapan like lower('%{$search}%') OR no_produk_hukum like lower('%{$search}%') 
            OR tgl_produk_hukum like lower('%{$search}%') OR pk_konseptor like lower('%{$search}%')
            OR hasil_keputusan like lower('%{$search}%') OR status like lower('%{$search}%')";
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
            $linkedit = url('/permohonan/kasi/create', base64_encode($dt->no_agenda));

            $action = '<center>
                            <a href="' . $linkedit . '">
                                <button data-toggle="tooltip" title="Kasi" type="button" class="btn btn-xs btn-primary btn-circle"><i class="fas fa-sign-in-alt"></i></button>
                            </a>
                            </center>';


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

            if ($dt->hasil_keputusan == 'Diterima') {
                $badge = 'badge-success';
            } else if ($dt->hasil_keputusan == 'Ditolak') {
                $badge = 'badge-danger';
            } else if ($dt->hasil_keputusan == 'Dicabut') {
                $badge = 'badge-warning';
            } else if ($dt->hasil_keputusan == 'Tolak Formal') {
                $badge = 'badge-dark';
            } else if ($dt->hasil_keputusan == 'Sebagian') {
                $badge = 'badge-secondary';
            } else {
                $badge = 'badge-info';
            }

            $hasil_kep = '<center>
                            <span class="badge ' . $badge . '">' . $dt->hasil_keputusan . '</span>
                        </center>';

            $result[] = [
                $start + $no + 1,
                $dt->no_agenda,
                $dt->npwp,
                $dt->nama_wajib_pajak,
                $dt->jenis_permohonan,
                $dt->no_ketetapan,
                $dt->pk_konseptor,
                $dt->no_produk_hukum,
                $dt->tgl_produk_hukum,
                $status,
                $hasil_kep,
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

    public function datatableKasi(Request $request)
    {
        // Session::flash('backUrl', Request::fullUrl());

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
            OR no_ketetapan like lower('%{$search}%') OR no_produk_hukum like lower('%{$search}%') 
            OR tgl_produk_hukum like lower('%{$search}%') OR pk_konseptor like lower('%{$search}%')
            OR hasil_keputusan like lower('%{$search}%') OR status like lower('%{$search}%')";
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
            $linkedit = url('/permohonan/kasi/create', base64_encode($dt->no_agenda));

            $action = '<center>
                            <a href="' . $linkedit . '">
                                <button data-toggle="tooltip" title="Kasi" type="button" class="btn btn-xs btn-primary btn-circle"><i class="fas fa-sign-in-alt"></i></button>
                            </a>
                            </center>';


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

            if ($dt->hasil_keputusan == 'Diterima') {
                $badge = 'badge-success';
            } else if ($dt->hasil_keputusan == 'Ditolak') {
                $badge = 'badge-danger';
            } else if ($dt->hasil_keputusan == 'Dicabut') {
                $badge = 'badge-warning';
            } else if ($dt->hasil_keputusan == 'Tolak Formal') {
                $badge = 'badge-dark';
            } else if ($dt->hasil_keputusan == 'Sebagian') {
                $badge = 'badge-secondary';
            } else {
                $badge = 'badge-info';
            }

            $hasil_kep = '<center>
                            <span class="badge ' . $badge . '">' . $dt->hasil_keputusan . '</span>
                        </center>';

            $result[] = [
                $start + $no + 1,
                $dt->no_agenda,
                $dt->npwp,
                $dt->nama_wajib_pajak,
                $dt->jenis_permohonan,
                $dt->no_ketetapan,
                $dt->pk_konseptor,
                $dt->no_produk_hukum,
                $dt->tgl_produk_hukum,
                $status,
                $hasil_kep,
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
    public function create(Request $request, $id)
    {
        $no_agenda = base64_decode($id);
        $mode = $request->mode;
        $data = [];
        // $user = Auth::user();
        $user = User::where('user_id', session('user_id'))->first();
        $dataPB = PelaksanaBidang::where('no_agenda', $no_agenda)->first();
        $dtAsalPermohonan = AsalPermohonan::get();
        $dtJnsPermohonan = JenisPermohonan::get();
        $dtPajak = Pajak::get();
        $dtKetetapan = Ketetapan::get();
        $dtKepsek = AnggotaSeksi::get();
        $dtPenelaah = PenelaahKeberatan::get();
        $dtKatPermohonan = KategoriPermohonan::get();
        $dtSeksiKonsep = SeksiKonseptor::get();
        $dtStatus = Status::get();
        $dtProgress = Progress::get();
        $dtKeputusan = Keputusan::get();
        $dtKriteria = KriteriaPermohonan::get();
        $data['user'] = $user;
        $data["mode"] = $mode;
        $data['dtAsalPermohonan'] = $dtAsalPermohonan;
        $data['dtJnsPermohonan'] = $dtJnsPermohonan;
        $data['dtPajak'] = $dtPajak;
        $data['dtKetetapan'] = $dtKetetapan;
        $data['dtKepsek'] = $dtKepsek;
        $data['dtPenelaah'] = $dtPenelaah;
        $data['dtKatPermohonan'] = $dtKatPermohonan;
        $data['dtSeksiKonsep'] = $dtSeksiKonsep;
        $data['dtStatus'] = $dtStatus;
        $data['dtProgress'] = $dtProgress;
        $data['dtKeputusan'] = $dtKeputusan;
        $data['dtKriteria'] = $dtKriteria;
        $data['dataPB'] = $dataPB;
        //print_r($data);
        return view($this->PATH_VIEW . 'create', $data);
    }
    public function store(Request $request)
    {
        $user = Auth::user();
        $mode = strtolower($request->input("mode"));
        $error = 0;
        DB::beginTransaction();

        $id_session = "KasiRedirect:" . $request->session()->getId();

        if ($mode == "edit") {
            $no_agenda = $request->no_agenda;
            $pb   = PelaksanaBidang::where('no_agenda', $no_agenda)->first();
            try {
                $data = PelaksanaBidang::updateDt($request, $pb);
                $data = PelaksanaBidang::createKasi($request, $pb);
            } catch (\Exception $e) {
                $devError = new DevError;
                $devError->form = "Update Kasi";
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
        } else {
            $no_agenda = $request->no_agenda;
            $pb   = PelaksanaBidang::where('no_agenda', $no_agenda)->first();
            try {
                $data = PelaksanaBidang::createKasi($request, $pb);
                $data = PelaksanaBidang::updateDt($request, $pb);
            } catch (\Exception $e) {
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
        }

        if ($error == 0) {
            DB::commit();
            if ($mode == 'add') {
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => 'Kasi ' . $request->no_agenda . ' telah ditambahkan!',
                ];
            } else {
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => 'Kasi ' . $request->no_agenda . ' telah diperbaharui!',
                ];
            }
        }

        $data["flashs"] = $flashs;
        toast($flashs[0]['message'], $flashs[0]['type']);
        $request->session()->put($id_session, 'Ya');
        return redirect()->route('kasi.index');
        // if (Session::has('backUrl')) {
        //     Session::keep('backUrl');
        // }
        // return ($url = Session::get('backUrl')) 
        // ? Redirect::to($url) 
        // : Redirect::route('kasi.index');
        // return redirect()->back();
    }
    public function printLabel($no_agenda, $docExt)
    {
        $noagen = base64_decode($no_agenda);
        $data = PelaksanaBidang::where('no_agenda', '=', $noagen)->first();
        if (!$data) {
            // $msg = notifErrorHelper('Wrong Action','Error');
            // return redirect()->back()->with('flashs',$msg);
            return redirect()->back();
        }
        $keberatan = stripos($data->jenis_permohonan, 'keberatan');
        //MR != keberatan 3bln, = keberatan 8bln
        $tgl_diterima_kpp = date('d-m-Y', strtotime($data->tgl_diterima_kpp));
        $d_mr = strtotime("+3 months,-1 day", strtotime($data->tgl_diterima_kpp));
        $d_mrk = strtotime("+8 months,-1 day", strtotime($data->tgl_diterima_kpp));
        //IKU != keberatan 5bln, = keberatan 10bln
        $d_iku = strtotime("+5 months,-1 day", strtotime($data->tgl_diterima_kpp));
        $d_ikuk = strtotime("+10 months,-1 day", strtotime($data->tgl_diterima_kpp));
        //KUP != keberatan 6bln, = keberatan 12bln
        $d_kup = strtotime("+6 months,-1 day", strtotime($data->tgl_diterima_kpp));
        $d_kupk = strtotime("+12 months,-1 day", strtotime($data->tgl_diterima_kpp));
        if ($keberatan != "") { // == keberatan
            $jt_mr = date("d-m-Y", $d_mrk);
            $jt_iku = date("d-m-Y", $d_ikuk);
            $jt_kup = date("d-m-Y", $d_kupk);
        } else { // !=keberatan
            $jt_mr = date("d-m-Y", $d_mr);
            $jt_iku = date("d-m-Y", $d_iku);
            $jt_kup = date("d-m-Y", $d_kup);
        }
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $header = $section->addHeader();
        $table_style = new Table;
        $table_style->setUnit(\PhpOffice\PhpWord\Style\Table::WIDTH_PERCENT);
        $table_style->setWidth(100 * 50);
        $section->addText("LABEL PENGAWASAN DOKUMEN", ['size' => 12, 'bold' => true], array('align' => 'center'));
        $section->addText("BIDANG KEBERATAN, BANDING, DAN PENGURANGAN", ['size' => 12, 'bold' => true], array('align' => 'center'));
        $section->addText("KANWIL DJP JAWA TIMUR I", ['size' => 12, 'bold' => true], array('align' => 'center'));

        $table_style_detail = array(
            'unit' => \PhpOffice\PhpWord\Style\Table::WIDTH_PERCENT,
            'width' => 100 * 50,
            'borderSize' => 0,
            'borderColor' => '757575',
            'cellMargin' => 80,
            'orientation' => 'landscape'
        );
        $titleStyle = array(
            'align' => 'center',
            'marginTop' => 10,
            'bgColor' => '893103',
        );
        $table = $section->addTable($table_style_detail);
        $fontStyle = array('bold' => true, 'bgColor' => 'FF000080');
        $fontStyle1 = array('bold' => true, 'bgColor' => '893103');
        $cellRowSpan = array('vMerge' => 'restart');
        $cellRowContinue = array('vMerge' => 'continue');
        $cellColSpan = array('gridSpan' => 3, 'bgColor' => '893103');
        $tableCenterValignText = array('bold' => true, 'color' => 'ffffff');
        $tableCenterValignText1 = array('bold' => false, 'color' => 'black');

        $table->addRow();
        $table->addCell(9000, $cellColSpan)->addText("JATUH TEMPO", $tableCenterValignText, array('align' => 'center'));
        $table->addRow();
        $table->addCell(3000, $fontStyle1)->addText("MR", $tableCenterValignText, array('align' => 'center'));
        $table->addCell(3000, $fontStyle1)->addText("IKU", $tableCenterValignText, array('align' => 'center'));
        $table->addCell(3000, $fontStyle1)->addText("KUP", $tableCenterValignText, array('align' => 'center'));
        $table->addRow();
        $table->addCell(3000, $fontStyle)->addText($jt_mr, $tableCenterValignText1, array('align' => 'center'));
        $table->addCell(3000, $fontStyle)->addText($jt_iku, $tableCenterValignText1, array('align' => 'center'));
        $table->addCell(3000, $fontStyle)->addText($jt_kup, $tableCenterValignText1, array('align' => 'center'));
        // $table->addRow();
        $styleCell =
            [
                'borderColor' => 'ffffff',
                'borderSize' => 6,
            ];
        $section->addTextRun();
        $table2 = $section->addTable($table_style_detail);
        $table2->addRow();
        $table2->addCell(12000, array('gridSpan' => 3, 'bgColor' => '00284f'))->addText("IDENTITAS DOKUMEN", array('bold' => true, 'align' => 'center'));
        $table2->addRow();
        $table2->addCell(3800, $styleCell)->addText('No Agenda', array('align' => 'center'));
        $table2->addCell(100, $styleCell)->addText(':', array('align' => 'center'));
        $table2->addCell(3900, $styleCell)->addText($data->no_agenda, array('align' => 'center'));
        $table2->addRow();
        $table2->addCell(3800, $styleCell)->addText('No Pokok Wajib Pajak', array('align' => 'center'));
        $table2->addCell(100, $styleCell)->addText(':', array('align' => 'center'));
        $table2->addCell(3900, $styleCell)->addText($data->npwp, array('align' => 'center'));
        $table2->addRow();
        $table2->addCell(3800, $styleCell)->addText('Nama Wajib Pajak', array('align' => 'center'));
        $table2->addCell(100, $styleCell)->addText(':', array('align' => 'center'));
        $table2->addCell(3900, $styleCell)->addText($data->nama_wajib_pajak, array('align' => 'center'));
        $table2->addRow();
        $table2->addCell(3800, $styleCell)->addText('Jenis Permohonan', array('align' => 'center'));
        $table2->addCell(100, $styleCell)->addText(':', array('align' => 'center'));
        $table2->addCell(3900, $styleCell)->addText($data->jenis_permohonan, array('align' => 'center'));
        $table2->addRow();
        $table2->addCell(3800, $styleCell)->addText('Kriteria Permohonan', array('align' => 'center'));
        $table2->addCell(100, $styleCell)->addText(':', array('align' => 'center'));
        $table2->addCell(3900, $styleCell)->addText($data->kriteria_permohonan, array('align' => 'center'));
        $table2->addRow();
        $table2->addCell(3800, $styleCell)->addText('Jenis Ketetapan', array('align' => 'center'));
        $table2->addCell(100, $styleCell)->addText(':', array('align' => 'center'));
        $table2->addCell(3900, $styleCell)->addText($data->jenis_ketetapan, array('align' => 'center'));
        $table2->addRow();
        $table2->addCell(3800, $styleCell)->addText('No Ketetapan', array('align' => 'center'));
        $table2->addCell(100, $styleCell)->addText(':', array('align' => 'center'));
        $table2->addCell(3900, $styleCell)->addText($data->no_ketetapan, array('align' => 'center'));
        $table2->addRow();
        $table2->addCell(3800, $styleCell)->addText('Jenis Pajak', array('align' => 'center'));
        $table2->addCell(100, $styleCell)->addText(':', array('align' => 'center'));
        $table2->addCell(3900, $styleCell)->addText($data->pajak, array('align' => 'center'));
        $table2->addRow();
        $table2->addCell(3800, $styleCell)->addText('Masa Pajak', array('align' => 'center'));
        $table2->addCell(100, $styleCell)->addText(':', array('align' => 'center'));
        $table2->addCell(3900, $styleCell)->addText($data->masa_pajak, array('align' => 'center'));
        $table2->addRow();
        $table2->addCell(3800, $styleCell)->addText('Tahun Pajak', array('align' => 'center'));
        $table2->addCell(100, $styleCell)->addText(':', array('align' => 'center'));
        $table2->addCell(3900, $styleCell)->addText($data->tahun_pajak, array('align' => 'center'));
        $table2->addRow();
        $table2->addCell(3800, $styleCell)->addText('Tanggal diterima KPP', array('align' => 'center'));
        $table2->addCell(100, $styleCell)->addText(':', array('align' => 'center'));
        $table2->addCell(3900, $styleCell)->addText($tgl_diterima_kpp, array('align' => 'center'));
        $table2->addRow();
        $table2->addCell(3800, $styleCell)->addText('Tanggal diterima Kanwil', array('align' => 'center'));
        $table2->addCell(100, $styleCell)->addText(':', array('align' => 'center'));
        $tglkanwil = date('d-m-Y', strtotime($data->tgl_diterima_kanwil));
        $table2->addCell(3900, $styleCell)->addText($tglkanwil, array('align' => 'center'));
        $table2->addRow();
        $table2->addCell(3800, $styleCell)->addText('Seksi Konseptor', array('align' => 'center'));
        $table2->addCell(100, $styleCell)->addText(':', array('align' => 'center'));
        $table2->addCell(3900, $styleCell)->addText($data->seksi_konseptor, array('align' => 'center'));
        $table2->addRow();
        $table2->addCell(3800, $styleCell)->addText('PK Konseptor', array('align' => 'center'));
        $table2->addCell(100, $styleCell)->addText(':', array('align' => 'center'));
        $table2->addCell(3900, $styleCell)->addText($data->pk_konseptor, array('align' => 'center'));

        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');

        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="Label ' . $noagen . '.' . $docExt);
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');

        $objWriter->save("php://output");
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
        $sheet->setCellValue('F1', 'Nama Wajib Pajak');
        $sheet->setCellValue('G1', 'Nomor Pokok Wajib Pajak');
        $sheet->setCellValue('H1', 'Jenis Permohonan');
        $sheet->setCellValue('I1', 'Kategori Permohonan');
        $sheet->setCellValue('J1', 'Tanggal diterima KPP');
        $sheet->setCellValue('K1', 'Nomor Lembar Pengawasan Arus Dokumen');
        $sheet->setCellValue('L1', 'Tanggal diterima Kanwil');
        $sheet->setCellValue('M1', 'Seksi Konseptor');
        $sheet->setCellValue('N1', 'PK Konseptor');
        $sheet->setCellValue('O1', 'Nomor Surat Permohonan');
        $sheet->setCellValue('P1', 'Tanggal Surat Permohonan');
        $sheet->setCellValue('Q1', 'Jenis Ketetapan');
        $sheet->setCellValue('R1', 'Jenis Pajak');
        $sheet->setCellValue('S1', 'Nomor Ketetapan');
        $sheet->setCellValue('T1', 'Tanggal Ketetapan');
        $sheet->setCellValue('U1', 'Masa Pajak');
        $sheet->setCellValue('V1', 'Tahun Pajak');
        $sheet->setCellValue('W1', 'Status');
        $sheet->setCellValue('X1', 'Nomor Produk Hukum');
        $sheet->setCellValue('Y1', 'Tanggal Produk Hukum');
        $sheet->setCellValue('Z1', 'Kurang');
        $sheet->setCellValue('AA1', 'Hasil Keputusan Tambah');
        $sheet->setCellValue('AB1', 'Jumlah Pembayaran a/ PMK-29 & PMK-91');
        $sheet->setCellValue('AC1', 'Tanggal Pembayaran');
        $sheet->setCellValue('AD1', 'Progress');
        $sheet->setCellValue('AE1', 'Jumlah yang Masih Harus Dibayar Semula');
        $sheet->setCellValue('AF1', 'Jumlah yang Masih Harus sesuai Produk Hukum');
        $sheet->setCellValue('AG1', 'Kepala Seksi');
        $sheet->setCellValue('AH1', 'Tambah');
        $sheet->setCellValue('AI1', 'Nomor Bukti Terima Kiriman (Resi) Pos');
        $sheet->setCellValue('AJ1', 'Tanggal Bukti Terima Kiriman (Resi) Pos');
        $sheet->setCellValue('AK1', 'Nomor Surat Pengantar');
        $sheet->setCellValue('AL1', 'Tanggal Surat Pengantar');

        $spreadsheet->getActiveSheet()->getStyle('A1:AL1')->getFont()->getColor()->setARGB('FFFFFFFF');
        $sheet->getStyle('A1:AL1')->applyFromArray($fontTitle);
        $sheet->getRowDimension('4')->setRowHeight(20);

        $sheet->setCellValue('A2', $data->no_agenda);
        $sheet->setCellValue('B2', $data->tgl_agenda);
        $sheet->setCellValue('C2', $data->no_naskah_dinas);
        $sheet->setCellValue('D2', date("d-m-Y", strtotime($data->tgl_naskah_dinas)));
        $sheet->setCellValue('E2', $data->pemohon);
        $sheet->setCellValue('F2', $data->nama_wajib_pajak);
        $sheet->setCellValue('G2', $data->npwp);
        $sheet->setCellValue('H2', $data->jenis_permohonan);
        $sheet->setCellValue('I2', $data->kat_permohonan);
        $sheet->setCellValue('J2', date("d-m-Y", strtotime($data->tgl_diterima_kpp)));
        $sheet->setCellValue('K2', $data->no_lbr_pengawas_dok);
        $sheet->setCellValue('L2', date("d-m-Y", strtotime($data->tgl_diterima_kanwil)));
        $sheet->setCellValue('M2', $data->seksi_konseptor);
        $sheet->setCellValue('N2', $data->pk_konseptor);
        $sheet->setCellValue('O2', $data->no_srt_permohonan);
        $sheet->setCellValue('P2', date("d-m-Y", strtotime($data->tgl_srt_permohonan)));
        $sheet->setCellValue('Q2', $data->jenis_ketetapan);
        $sheet->setCellValue('R2',  $data->pajak);
        $sheet->setCellValue('S2', $data->no_ketetapan);
        $sheet->setCellValue('T2', date("d-m-Y", strtotime($data->tgl_ketetapan)));
        $sheet->setCellValue('U2', $data->masa_pajak);
        $sheet->setCellValue('V2', $data->tahun_pajak);
        $sheet->setCellValue('W2', $data->status);
        $sheet->setCellValue('X2', $data->no_produk_hukum);
        $sheet->setCellValue('Y2', date("d-m-Y", strtotime($data->tgl_produk_hukum)));
        $sheet->setCellValue('Z2', $data->kurang);
        $sheet->setCellValue('AA2', $data->hasil_keputusan);
        $sheet->setCellValue('AB2', $data->jumlah_byr_pmk);
        $sheet->setCellValue('AC2', date("d-m-Y", strtotime($data->tgl_byr_pmk)));
        $sheet->setCellValue('AD2', $data->progress);
        $sheet->setCellValue('AE2', $data->jml_byr_semula);
        $sheet->setCellValue('AF2', $data->jml_byr_produk);
        $sheet->setCellValue('AG2', $data->kepala_seksi);
        $sheet->setCellValue('AH2', $data->tambah);
        $sheet->setCellValue('AI2', $data->no_resi);
        $sheet->setCellValue('AJ2', date("d-m-Y", strtotime($data->tgl_resi)));
        $sheet->setCellValue('AK2', $data->no_srt_pengantar);
        $sheet->setCellValue('AL2', date("d-m-Y", strtotime($data->tgl_srt_pengantar)));

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
        $sheet->getStyle('A1:AL1')->applyFromArray($header_style_border);
        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="Eksekutor ' . $noagen . '.xls');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        $writer = new Xls($spreadsheet);
        $writer->save("php://output");
        header('Content-Disposition: attachment; filename="Eksekutor ' . $noagen . '.xls');
    }
    public function printAll(Request $request)
    {
        $search = $request->searchks;
        if ($search != null) {
            $where = " no_agenda like lower('%{$search}%') OR npwp like lower('%{$search}%')
            OR nama_wajib_pajak like lower('%{$search}%') OR jenis_permohonan like lower('%{$search}%')
            OR no_ketetapan like lower('%{$search}%') OR no_produk_hukum like lower('%{$search}%') 
            OR tgl_produk_hukum like lower('%{$search}%') OR pk_konseptor like lower('%{$search}%')
            OR hasil_keputusan like lower('%{$search}%') OR status like lower('%{$search}%')";
            $dataAll = PelaksanaBidang::where('status_dokumen', '<>', 'Delete')
                ->orWhereNull('status_dokumen')
                ->whereRaw("{$where}")
                ->get();
            if (!$dataAll) {
                return redirect()->back();
            }
        } else {
            $dataAll = PelaksanaBidang::where('status_dokumen', '<>', 'Delete')
                ->orWhereNull('status_dokumen')
                ->get();
            if (!$dataAll) {
                return redirect()->back();
            }
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
        $sheet->setCellValue('F1', 'Nama Wajib Pajak');
        $sheet->setCellValue('G1', 'Nomor Pokok Wajib Pajak');
        $sheet->setCellValue('H1', 'Jenis Permohonan');
        $sheet->setCellValue('I1', 'Kategori Permohonan');
        $sheet->setCellValue('J1', 'Tanggal diterima KPP');
        $sheet->setCellValue('K1', 'Nomor Lembar Pengawasan Arus Dokumen');
        $sheet->setCellValue('L1', 'Tanggal diterima Kanwil');
        $sheet->setCellValue('M1', 'Seksi Konseptor');
        $sheet->setCellValue('N1', 'PK Konseptor');
        $sheet->setCellValue('O1', 'Nomor Surat Permohonan');
        $sheet->setCellValue('P1', 'Tanggal Surat Permohonan');
        $sheet->setCellValue('Q1', 'Jenis Ketetapan');
        $sheet->setCellValue('R1', 'Jenis Pajak');
        $sheet->setCellValue('S1', 'Nomor Ketetapan');
        $sheet->setCellValue('T1', 'Tanggal Ketetapan');
        $sheet->setCellValue('U1', 'Masa Pajak');
        $sheet->setCellValue('V1', 'Tahun Pajak');
        $sheet->setCellValue('W1', 'Status');
        $sheet->setCellValue('X1', 'Nomor Produk Hukum');
        $sheet->setCellValue('Y1', 'Tanggal Produk Hukum');
        $sheet->setCellValue('Z1', 'Kurang');
        $sheet->setCellValue('AA1', 'Hasil Keputusan Tambah');
        $sheet->setCellValue('AB1', 'Jumlah Pembayaran a/ PMK-29 & PMK-91');
        $sheet->setCellValue('AC1', 'Tanggal Pembayaran');
        $sheet->setCellValue('AD1', 'Progress');
        $sheet->setCellValue('AE1', 'Jumlah yang Masih Harus Dibayar Semula');
        $sheet->setCellValue('AF1', 'Jumlah yang Masih Harus sesuai Produk Hukum');
        $sheet->setCellValue('AG1', 'Kepala Seksi');
        $sheet->setCellValue('AH1', 'Tambah');
        $sheet->setCellValue('AI1', 'Nomor Bukti Terima Kiriman (Resi) Pos');
        $sheet->setCellValue('AJ1', 'Tanggal Bukti Terima Kiriman (Resi) Pos');
        $sheet->setCellValue('AK1', 'Nomor Surat Pengantar');
        $sheet->setCellValue('AL1', 'Tanggal Surat Pengantar');

        $spreadsheet->getActiveSheet()->getStyle('A1:AL1')->getFont()->getColor()->setARGB('FFFFFFFF');
        $sheet->getStyle('A1:AL1')->applyFromArray($fontTitle);
        $sheet->getRowDimension('4')->setRowHeight(20);
        $last_row = 1;
        foreach ($dataAll as $data) {
            $last_row++;
            $sheet->setCellValue('A' . $last_row, $data->no_agenda);
            $sheet->setCellValue('B' . $last_row, $data->tgl_agenda);
            $sheet->setCellValue('C' . $last_row, $data->no_naskah_dinas);
            $sheet->setCellValue('D' . $last_row, date("d-m-Y", strtotime($data->tgl_naskah_dinas)));
            $sheet->setCellValue('E' . $last_row, $data->pemohon);
            $sheet->setCellValue('F' . $last_row, $data->nama_wajib_pajak);
            $sheet->setCellValue('G' . $last_row, $data->npwp);
            $sheet->setCellValue('H' . $last_row, $data->jenis_permohonan);
            $sheet->setCellValue('I' . $last_row, $data->kat_permohonan);
            $sheet->setCellValue('J' . $last_row, date("d-m-Y", strtotime($data->tgl_diterima_kpp)));
            $sheet->setCellValue('K' . $last_row, $data->no_lbr_pengawas_dok);
            $sheet->setCellValue('L' . $last_row, date("d-m-Y", strtotime($data->tgl_diterima_kanwil)));
            $sheet->setCellValue('M' . $last_row, $data->seksi_konseptor);
            $sheet->setCellValue('N' . $last_row, $data->pk_konseptor);
            $sheet->setCellValue('O' . $last_row, $data->no_srt_permohonan);
            $sheet->setCellValue('P' . $last_row, date("d-m-Y", strtotime($data->tgl_srt_permohonan)));
            $sheet->setCellValue('Q' . $last_row, $data->jenis_ketetapan);
            $sheet->setCellValue('R' . $last_row, $data->pajak);
            $sheet->setCellValue('S' . $last_row, $data->no_ketetapan);
            $sheet->setCellValue('T' . $last_row, date("d-m-Y", strtotime($data->tgl_ketetapan)));
            $sheet->setCellValue('U' . $last_row, $data->masa_pajak);
            $sheet->setCellValue('V' . $last_row, $data->tahun_pajak);
            $sheet->setCellValue('W' . $last_row, $data->status);
            $sheet->setCellValue('X' . $last_row, $data->no_produk_hukum);
            $sheet->setCellValue('Y' . $last_row, date("d-m-Y", strtotime($data->tgl_produk_hukum)));
            $sheet->setCellValue('Z' . $last_row, $data->kurang);
            $sheet->setCellValue('AA' . $last_row, $data->hasil_keputusan);
            $sheet->setCellValue('AB' . $last_row, $data->jumlah_byr_pmk);
            $sheet->setCellValue('AC' . $last_row, date("d-m-Y", strtotime($data->tgl_byr_pmk)));
            $sheet->setCellValue('AD' . $last_row, $data->progress);
            $sheet->setCellValue('AE' . $last_row, $data->jml_byr_semula);
            $sheet->setCellValue('AF' . $last_row, $data->jml_byr_produk);
            $sheet->setCellValue('AG' . $last_row, $data->kepala_seksi);
            $sheet->setCellValue('AH' . $last_row, $data->tambah);
            $sheet->setCellValue('AI' . $last_row, $data->no_resi);
            $sheet->setCellValue('AJ' . $last_row, date("d-m-Y", strtotime($data->tgl_resi)));
            $sheet->setCellValue('AK' . $last_row, $data->no_srt_pengantar);
            $sheet->setCellValue('AL' . $last_row, date("d-m-Y", strtotime($data->tgl_srt_pengantar)));
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
        $sheet->getStyle('A1:AL1')->applyFromArray($header_style_border);
        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="Database Eksekutor.xls');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        $writer = new Xls($spreadsheet);
        $writer->save("php://output");
        header('Content-Disposition: attachment; filename="Database Eksekutor.xls');
    }
}
