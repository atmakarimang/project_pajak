<?php

namespace App\Http\Controllers\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; // baru
use App\Models\DevError;
use App\Models\AnggotaSeksi;
use App\Models\SeksiKonseptor;
use App\Models\PenelaahKeberatan;
use DB;

class DataSeksiController extends Controller
{
    protected $PATH_VIEW = 'masterdata.DataSeksi.';

    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    public function index(Request $request)
    {
        $data = [];
        $user = Auth::user();
        $mode = $request->mode;
        $data["mode"] = $mode;
        $data['user'] = $user;

        return view($this->PATH_VIEW . 'index', $data);
    }
    public function storeKepSeksi(Request $request)
    {
        $mode = strtolower($request->input("mode"));
        $results = $request->all();
        $error = 0;
        DB::beginTransaction();
        if ($mode == "edit") {
            $id = $request->id_ks;
            $as = AnggotaSeksi::where('id', $id)->first();
            try {
                $data = AnggotaSeksi::updateDt($request, $as);
            } catch (\Exception $e) {
                $devError = new DevError;
                $devError->form = "Update Kepala Seksi";
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
                    'message' => "Kepala Konseptor gagal diupdated!",
                ];
            }
        } else {
            try {
                $data = AnggotaSeksi::create($request);
            } catch (\Exception $e) {
                $devError = new DevError;
                $devError->form = "Add Anggota Seksi";
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
                    'message' => "Gagal disimpan!",
                ];
            }
        }

        if ($error == 0) {
            DB::commit();
            if ($mode == 'add') {
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => 'Kepala Seksi ' . $request->nama_kepala . ' telah ditambahkan!',
                ];
            } else {
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => 'Kepala Seksi ' . $request->nama_kepala . ' telah diupdated!',
                ];
            }
        }

        $data["flashs"] = $flashs;
        toast($flashs[0]['message'], $flashs[0]['type']);
        // return redirect()->back()->with($data);
        return redirect()->route('seksi.index');
    }
    public function ajaxDataKepsek(Request $request)
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

        if ($orderBy && $orderType) {
            $orderBy = $orderBy;
            $orderType = $orderType;
        } else {
            $orderBy = 'id';
            $orderType = 'DESC';
        }

        $limit = $length;
        $offset = $start;
        $jumlahTotal = AnggotaSeksi::count();

        if ($search) { // filter data
            $where = "nama_anggota like lower('%{$search}%')";
            $jumlahFiltered = AnggotaSeksi::whereRaw("{$where}")->orderBy($orderBy, $orderType)
                ->count(); //hitung data yang telah terfilter
            $data = AnggotaSeksi::whereRaw($where)
                ->orderBy($orderBy, $orderType)
                ->offset($offset)
                ->limit($limit)
                ->get();
        } else {
            $jumlahFiltered = $jumlahTotal;
            $data = AnggotaSeksi::orderBy($orderBy, $orderType)
                ->offset($offset)
                ->limit($limit)
                ->get();
        }

        $result = [];

        foreach ($data as $no => $dt) {
            $linkdelete = url('/master-data/deleteKS', $dt->id);
            $action = '<center>
                            <div class="btn-group btn-group-sm">
                            <button data-toggle="tooltip" title="Edit" type="button" class="btn btn-xs btn-primary btn-circle" onclick="editKS(\'' . $dt->id . '\')"><i class="fas fa-pencil-alt"></i></button>
                            <button onclick="buttonDeleteKS(this)" data-link="' . $linkdelete . '" data-toggle="tooltip" title="Delete" type="button" class="btn btn-xs btn-danger btn-circle"><i class="fas fa-trash"></i></button>
                            </div>
                        </center>';
            $result[] = [
                $start + $no + 1,
                $dt->nama_anggota,
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
    public function editKS(Request $request)
    {
        $id = $request->id;
        $dt = AnggotaSeksi::where('id', $id)->first();

        echo json_encode($dt);
    }
    public function deleteKS($id)
    {
        AnggotaSeksi::where('id', $id)->delete();
        toast('Data sudah berhasil dihapus', 'success');
        return redirect()->back();
    }

    public function storeKonseptor(Request $request)
    {
        $mode = strtolower($request->input("mode"));
        $results = $request->all();
        $error = 0;
        DB::beginTransaction();
        $cek_data = SeksiKonseptor::where('seksi_konseptor', $request->seksi_konseptor)->count();
        if ($cek_data > 0) {
            $error_duplikat[] = [
                'type' => 'success', // option : info, warning, success, error
                'title' => 'Success',
                'message' => 'Seksi Konseptor ' . $request->seksi_konseptor . ' sudah ada!',
            ];
            $data["error_duplikat"] = $error_duplikat;
            // return redirect()->back()->with($data);
            return redirect()->back();
        }
        if ($mode == "edit") {
            $id = $request->id_konseptor;
            $sk = SeksiKonseptor::where('id', $id)->first();
            try {
                $data = SeksiKonseptor::updateDt($request, $sk);
            } catch (\Exception $e) {
                $devError = new DevError;
                $devError->form = "Update Seksi Konseptor";
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
                    'message' => "Seksi Konseptor gagal diupdated!",
                ];
            }
        } else {
            try {
                $data = SeksiKonseptor::create($request);
            } catch (\Exception $e) {
                $devError = new DevError;
                $devError->form = "Add Seksi Konseptor";
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
                    'message' => "Gagal disimpan!",
                ];
            }
        }

        if ($error == 0) {
            DB::commit();
            if ($mode == 'add') {
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => 'Seksi Konseptor ' . $request->seksi_konseptor . ' telah ditambahkan!',
                ];
            } else {
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => 'Seksi Konseptor ' . $request->seksi_konseptor . ' telah diupdated!',
                ];
            }
        }

        $data["flashs"] = $flashs;
        toast($flashs[0]['message'], $flashs[0]['type']);
        // return redirect()->back()->with($data);
        return redirect()->route('seksi.index');
    }

    public function ajaxDataKS(Request $request)
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

        if ($orderBy && $orderType) {
            $orderBy = $orderBy;
            $orderType = $orderType;
        } else {
            $orderBy = 'id';
            $orderType = 'DESC';
        }

        $limit = $length;
        $offset = $start;
        $jumlahTotal = SeksiKonseptor::count();

        if ($search) { // filter data
            $where = "seksi_konseptor like lower('%{$search}%')";
            $jumlahFiltered = SeksiKonseptor::whereRaw("{$where}")->orderBy($orderBy, $orderType)
                ->count(); //hitung data yang telah terfilter
            $data = SeksiKonseptor::whereRaw($where)
                ->orderBy($orderBy, $orderType)
                ->offset($offset)
                ->limit($limit)
                ->get();
        } else {
            $jumlahFiltered = $jumlahTotal;
            $data = SeksiKonseptor::orderBy($orderBy, $orderType)
                ->offset($offset)
                ->limit($limit)
                ->get();
        }

        $result = [];

        foreach ($data as $no => $dt) {
            $linkdelete = url('/master-data/deleteKonseptor', $dt->id);
            $action = '<center>
                            <div class="btn-group btn-group-sm">
                            <button data-toggle="tooltip" title="Edit" type="button" class="btn btn-xs btn-info btn-circle" onclick="editKonseptor(\'' . $dt->id . '\')"><i class="fas fa-pencil-alt"></i></button>
                            <button onclick="buttonDeleteKonseptor(this)" data-link="' . $linkdelete . '" data-toggle="tooltip" title="Delete" type="button" class="btn btn-xs btn-danger btn-circle"><i class="fas fa-trash"></i></button>
                            </div>
                        </center>';
            $result[] = [
                $start + $no + 1,
                $dt->seksi_konseptor,
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
    public function editKonseptor(Request $request)
    {
        $id = $request->id;
        $dt = SeksiKonseptor::where('id', $id)->first();

        echo json_encode($dt);
    }
    public function deleteKonseptor($id)
    {
        SeksiKonseptor::where('id', $id)->delete();
        toast('Data sudah berhasil dihapus', 'success');
        return redirect()->back();
    }
    public function storePK(Request $request)
    {
        $mode = strtolower($request->input("mode"));
        $results = $request->all();
        $error = 0;
        DB::beginTransaction();
        $cek_data = PenelaahKeberatan::where('nama_penelaah', $request->nama_pk)->count();
        if ($cek_data > 0) {
            $error_duplikat[] = [
                'type' => 'success', // option : info, warning, success, error
                'title' => 'Success',
                'message' => 'Penelaah Keberatan ' . $request->nama_pk . ' sudah ada!',
            ];
            $data["error_duplikat"] = $error_duplikat;
            // return redirect()->back()->with($data);
            return redirect()->back();
        }
        if ($mode == "edit") {
            $id = $request->id_pk;
            $pk = PenelaahKeberatan::where('id', $id)->first();
            try {
                $data = PenelaahKeberatan::updateDt($request, $pk);
            } catch (\Exception $e) {
                $devError = new DevError;
                $devError->form = "Update Penelaah Keberatan";
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
                    'message' => "Penelaah Keberatan gagal diupdated!",
                ];
            }
        } else {
            try {
                $data = PenelaahKeberatan::create($request);
            } catch (\Exception $e) {
                $devError = new DevError;
                $devError->form = "Add Penelaah Keberatan";
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
                    'message' => "Gagal disimpan!",
                ];
            }
        }

        if ($error == 0) {
            DB::commit();
            if ($mode == 'add') {
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => 'Penelaah Keberatan ' . $request->nama_pk . ' telah ditambahkan!',
                ];
            } else {
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => 'Penelaah Keberatan ' . $request->nama_pk . ' telah diupdated!',
                ];
            }
        }

        $data["flashs"] = $flashs;
        toast($flashs[0]['message'], $flashs[0]['type']);
        // return redirect()->back()->with($data);
        return redirect()->route('seksi.index');
    }
    public function ajaxDataPK(Request $request)
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

        if ($orderBy && $orderType) {
            $orderBy = $orderBy;
            $orderType = $orderType;
        } else {
            $orderBy = 'id';
            $orderType = 'DESC';
        }

        $limit = $length;
        $offset = $start;
        $jumlahTotal = PenelaahKeberatan::count();

        if ($search) { // filter data
            $where = "nama_penelaah like lower('%{$search}%')";
            $jumlahFiltered = PenelaahKeberatan::whereRaw("{$where}")->orderBy($orderBy, $orderType)
                ->count(); //hitung data yang telah terfilter
            $data = PenelaahKeberatan::whereRaw($where)
                ->orderBy($orderBy, $orderType)
                ->offset($offset)
                ->limit($limit)
                ->get();
        } else {
            $jumlahFiltered = $jumlahTotal;
            $data = PenelaahKeberatan::orderBy($orderBy, $orderType)
                ->offset($offset)
                ->limit($limit)
                ->get();
        }

        $result = [];

        foreach ($data as $no => $dt) {
            $linkdelete = url('/master-data/deletePK', $dt->id);
            $action = '<center>
                            <div class="btn-group btn-group-sm">
                            <button data-toggle="tooltip" title="Edit" type="button" class="btn btn-xs btn-success btn-circle" onclick="editPK(\'' . $dt->id . '\')"><i class="fas fa-pencil-alt"></i></button>
                            <button onclick="buttonDeletePK(this)" data-link="' . $linkdelete . '" data-toggle="tooltip" title="Delete" type="button" class="btn btn-xs btn-danger btn-circle"><i class="fas fa-trash"></i></button>
                            </div>
                        </center>';
            $result[] = [
                $start + $no + 1,
                $dt->nama_penelaah,
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
    public function editPK(Request $request)
    {
        $id = $request->id;
        $dt = PenelaahKeberatan::where('id', $id)->first();

        echo json_encode($dt);
    }
    public function deletePK($id)
    {
        PenelaahKeberatan::where('id', $id)->delete();
        toast('Data sudah berhasil dihapus', 'success');
        return redirect()->back();
    }
}
