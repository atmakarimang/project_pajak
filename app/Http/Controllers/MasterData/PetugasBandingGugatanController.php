<?php

namespace App\Http\Controllers\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; // baru
use App\Models\DevError;
use App\Models\PetugasSidangBanding;
use App\Models\PelaksanaEksekutor;
use DB;

class PetugasBandingGugatanController extends Controller
{
    protected $PATH_VIEW = 'masterdata.PetugasBandingGugatan.';

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
    public function storePetSidang(Request $request)
    {
        $user = Auth::user();
        $mode = strtolower($request->input("mode"));
        $results = $request->all();
        $error = 0;
        DB::beginTransaction();
        if ($mode == "edit") {
            $id = $request->id_petugas;
            $dt = PetugasSidangBanding::where('id', $id)->first();
            try {
                $data = PetugasSidangBanding::updateDt($request, $dt);
            } catch (\Exception $e) {
                $devError = new DevError;
                $devError->form = "Update Petugas Sidang Banding";
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
                    'message' => "Petugas Sidang Banding gagal diupdated!",
                ];
            }
        } else {
            $check = PetugasSidangBanding::where('nama_petugas', $request->nama_petugas)->count();
            if ($check > 0) {
                $msg[] = [
                    'type' => 'error', // option : info, warning, success, error
                    'title' => 'Error',
                    'message' => "Nama petugas sudah ada!",
                ];
                return redirect()->back()->with("flashs", $msg);
            }
            try {
                $data = PetugasSidangBanding::create($request);
            } catch (\Exception $e) {
                $devError = new DevError;
                $devError->form = "Add Petugas Sidang Banding";
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
                    'message' => "Petugas Sidang Banding gagal disimpan!",
                ];
            }
        }

        if ($error == 0) {
            DB::commit();
            if ($mode == 'add') {
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => 'Petugas Sidang Banding ' . $request->nama_petugas . ' telah ditambahkan!',
                ];
            } else {
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => 'Petugas Sidang Banding ' . $request->nama_petugas . ' telah diperbaharui!',
                ];
            }
        }

        $data["flashs"] = $flashs;
        toast($flashs[0]['message'], $flashs[0]['type']);
        // return redirect()->back()->with($data);
        return redirect()->route('ptg_banding.index');
    }
    public function ajaxDataPS(Request $request)
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
        $jumlahTotal = PetugasSidangBanding::count();

        if ($search) { // filter data
            $where = "nama_petugas like lower('%{$search}%')";
            $jumlahFiltered = PetugasSidangBanding::whereRaw("{$where}")->orderBy($orderBy, $orderType)
                ->count(); //hitung data yang telah terfilter
            $data = PetugasSidangBanding::whereRaw($where)
                ->orderBy($orderBy, $orderType)
                ->offset($offset)
                ->limit($limit)
                ->get();
        } else {
            $jumlahFiltered = $jumlahTotal;
            $data = PetugasSidangBanding::orderBy($orderBy, $orderType)
                ->offset($offset)
                ->limit($limit)
                ->get();
        }

        $result = [];

        foreach ($data as $no => $dt) {
            $linkdelete = url('master-data/deletePS', $dt->id);
            $action = '<center>
                            <div class="btn-group btn-group-sm">
                            <button data-toggle="tooltip" data-original-title="Edit" type="button" class="btn btn-xs btn-primary btn-circle" onclick="editPS(\'' . $dt->id . '\')"><i class="fas fa-pencil-alt"></i></button>
                            <button onclick="buttonDeletePS(this)" data-link="' . $linkdelete . '" data-toggle="tooltip" data-original-title="Delete" type="button" class="btn btn-xs btn-danger btn-circle"><i class="fas fa-trash"></i></button>
                            </div>
                            </center>';
            $result[] = [
                $start + $no + 1,
                $dt->nama_petugas,
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
    public function editPS(Request $request)
    {
        $id = $request->id;
        $dt = PetugasSidangBanding::where('id', $id)->first();

        echo json_encode($dt);
    }
    public function deletePS($id)
    {
        PetugasSidangBanding::where('id', $id)->delete();
        toast('Data sudah berhasil dihapus', 'success');
        return redirect()->back();
    }

    public function storeEksekutor(Request $request)
    {
        $user = Auth::user();
        $mode = strtolower($request->input("mode"));
        $error = 0;
        DB::beginTransaction();
        if ($mode == "edit") {
            $id = $request->id_eks;
            $dt = PelaksanaEksekutor::where('id', $id)->first();
            try {
                $data = PelaksanaEksekutor::updateDt($request, $dt);
            } catch (\Exception $e) {
                $devError = new DevError;
                $devError->form = "Update Pelaksana Eksekutor";
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
                    'message' => "Pelaksana Eksekutor gagal diupdated!",
                ];
            }
        } else {
            $check = PelaksanaEksekutor::where('pelaksana_eksekutor', $request->pel_eksekutor)->count();
            if ($check > 0) {
                $msg[] = [
                    'type' => 'error', // option : info, warning, success, error
                    'title' => 'Error',
                    'message' => "Nama pelaksana eksekutor sudah ada!",
                ];
                return redirect()->back()->with("flashs", $msg);
            }
            try {
                $data = PelaksanaEksekutor::create($request);
            } catch (\Exception $e) {
                $devError = new DevError;
                $devError->form = "Add Pelaksana Eksekutor";
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
                    'message' => "Pelaksana Eksekutor gagal disimpan!",
                ];
            }
        }

        if ($error == 0) {
            DB::commit();
            if ($mode == 'add') {
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => 'Pelaksana Eksekutor ' . $request->nama_petugas . ' telah ditambahkan!',
                ];
            } else {
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => 'Pelaksana Eksekutor ' . $request->nama_petugas . ' telah diperbaharui!',
                ];
            }
        }

        $data["flashs"] = $flashs;
        toast($flashs[0]['message'], $flashs[0]['type']);
        // return redirect()->back()->with($data);
        return redirect()->route('ptg_banding.index');
    }
    public function ajaxDataEks(Request $request)
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
        $jumlahTotal = PelaksanaEksekutor::count();

        if ($search) { // filter data
            $where = "pelaksana_eksekutor like lower('%{$search}%')";
            $jumlahFiltered = PelaksanaEksekutor::whereRaw("{$where}")->orderBy($orderBy, $orderType)
                ->count(); //hitung data yang telah terfilter
            $data = PelaksanaEksekutor::whereRaw($where)
                ->orderBy($orderBy, $orderType)
                ->offset($offset)
                ->limit($limit)
                ->get();
        } else {
            $jumlahFiltered = $jumlahTotal;
            $data = PelaksanaEksekutor::orderBy($orderBy, $orderType)
                ->offset($offset)
                ->limit($limit)
                ->get();
        }

        $result = [];

        foreach ($data as $no => $dt) {
            $linkdelete = url('master-data/deleteEks', $dt->id);
            $action = '<center>
                            <div class="btn-group btn-group-sm">
                            <button data-toggle="tooltip" data-original-title="Edit" type="button" class="btn btn-xs btn-info btn-circle" onclick="editEks(\'' . $dt->id . '\')"><i class="fas fa-pencil-alt"></i></button>
                            <button onclick="buttonDeleteEks(this)" data-link="' . $linkdelete . '" data-toggle="tooltip" data-original-title="Delete" type="button" class="btn btn-xs btn-danger btn-circle"><i class="fas fa-trash"></i></button>
                            </div>
                            </center>';
            $result[] = [
                $start + $no + 1,
                $dt->pelaksana_eksekutor,
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
    public function editEks(Request $request)
    {
        $id = $request->id;
        $dt = PelaksanaEksekutor::where('id', $id)->first();

        echo json_encode($dt);
    }
    public function deleteEks($id)
    {
        PelaksanaEksekutor::where('id', $id)->delete();
        toast('Data sudah berhasil dihapus', 'success');
        return redirect()->back();
    }
}
