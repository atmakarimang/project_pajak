<?php

namespace App\Http\Controllers\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; // baru
use App\Models\DevError;
use App\Models\Pajak;
use App\Models\Ketetapan;
use DB;

class DataPajakController extends Controller
{
    protected $PATH_VIEW = 'masterdata.DataPajak.';

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
    public function storePajak(Request $request)
    {
        $user = Auth::user();
        $mode = strtolower($request->input("mode"));
        $results = $request->all();
        $error = 0;
        $cek_data = Pajak::where('pajak', $request->pajak)->count();
        if ($cek_data > 0) {
            $error_duplikat[] = [
                'type' => 'success', // option : info, warning, success, error
                'title' => 'Success',
                'message' => 'Pajak ' . $request->pajak . ' sudah ada!',
            ];
            $data["error_duplikat"] = $error_duplikat;
            // return redirect()->back()->with($data);
            return redirect()->back();
        }
        DB::beginTransaction();
        if ($mode == "edit") {
            $id = $request->id_pj;
            $pj = Pajak::where('id', $id)->first();
            try {
                $data = Pajak::updateDt($request, $pj);
            } catch (\Exception $e) {
                $devError = new DevError;
                $devError->form = "Update Pajak";
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
                    'message' => "Pajak gagal diupdated!",
                ];
            }
        } else {
            try {
                $data = Pajak::create($request);
            } catch (\Exception $e) {
                $devError = new DevError;
                $devError->form = "Add Pajak";
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
                    'message' => "Pajak gagal disimpan!",
                ];
            }
        }

        if ($error == 0) {
            DB::commit();
            if ($mode == 'add') {
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => 'Pajak ' . $request->pajak . ' telah ditambahkan!',
                ];
            } else {
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => 'Pajak ' . $request->pajak . ' telah diupdated!',
                ];
            }
        }

        $data["flashs"] = $flashs;
        toast($flashs[0]['message'], $flashs[0]['type']);
        // return redirect()->back()->with($data);
        return redirect()->route('pajak.index');
    }
    public function ajaxDataPajak(Request $request)
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
        $jumlahTotal = Pajak::count();

        if ($search) { // filter data
            $where = "pajak like lower('%{$search}%')";
            $jumlahFiltered = Pajak::whereRaw("{$where}")->orderBy($orderBy, $orderType)
                ->count(); //hitung data yang telah terfilter
            $data = Pajak::whereRaw($where)
                ->orderBy($orderBy, $orderType)
                ->offset($offset)
                ->limit($limit)
                ->get();
        } else {
            $jumlahFiltered = $jumlahTotal;
            $data = Pajak::orderBy($orderBy, $orderType)
                ->offset($offset)
                ->limit($limit)
                ->get();
        }

        $result = [];

        foreach ($data as $no => $dt) {
            $linkdelete = url('master-data/deletePj', $dt->id);
            $action = '<center>
                                <div class="btn-group btn-group-sm">
                            <button data-toggle="tooltip" data-original-title="Edit" type="button" class="btn btn-xs btn-primary btn-circle" onclick="editPj(\'' . $dt->id . '\')"><i class="fas fa-pencil-alt"></i></button>
                            <button onclick="buttonDeletePj(this)" data-link="' . $linkdelete . '" data-toggle="tooltip" data-original-title="Delete" type="button" class="btn btn-xs btn-danger btn-circle"><i class="fas fa-trash"></i></button>
                            </div>
                            </center>';
            $result[] = [
                $start + $no + 1,
                $dt->pajak,
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
    public function editPj(Request $request)
    {
        $id = $request->id;
        $dt = Pajak::where('id', $id)->first();

        echo json_encode($dt);
    }
    public function deletePj($id)
    {
        Pajak::where('id', $id)->delete();
        toast('Data sudah berhasil dihapus', 'success');
        return redirect()->back();
    }

    public function storeJK(Request $request)
    {
        $user = Auth::user();
        $mode = strtolower($request->input("mode"));
        $results = $request->all();
        $error = 0;
        $cek_data = Ketetapan::where('jenis_ketetapan', $request->jenis_ketetapan)->count();
        if ($cek_data > 0) {
            $error_duplikat[] = [
                'type' => 'success', // option : info, warning, success, error
                'title' => 'Success',
                'message' => 'Jenis Ketetapan ' . $request->jenis_ketetapan . ' sudah ada!',
            ];
            $data["error_duplikat"] = $error_duplikat;
            // return redirect()->back()->with($data);
            return redirect()->back();
        }
        DB::beginTransaction();
        if ($mode == "edit") {
            $id = $request->id_jk;
            $jk = Ketetapan::where('id', $id)->first();
            try {
                $data = Ketetapan::updateDt($request, $jk);
            } catch (\Exception $e) {
                $devError = new DevError;
                $devError->form = "Update Jenis Ketetapan";
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
                    'message' => "Jenis Ketetapan gagal diupdated!",
                ];
            }
        } else {
            try {
                $data = Ketetapan::create($request);
            } catch (\Exception $e) {
                $devError = new DevError;
                $devError->form = "Add Jenis Ketetapan";
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
                    'message' => "Jenis Ketetapan gagal disimpan!",
                ];
            }
        }

        if ($error == 0) {
            DB::commit();
            if ($mode == 'add') {
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => 'Jenis Ketetapan ' . $request->jenis_ketetapan . ' telah ditambahkan!',
                ];
            } else {
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => 'Jenis Ketetapan ' . $request->jenis_ketetapan . ' telah diupdated!',
                ];
            }
        }

        $data["flashs"] = $flashs;
        toast($flashs[0]['message'], $flashs[0]['type']);
        // return redirect()->back()->with($data);
        return redirect()->route('pajak.index');
    }
    public function ajaxDataJK(Request $request)
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
        $jumlahTotal = Ketetapan::count();

        if ($search) { // filter data
            $where = "jenis_ketetapan like lower('%{$search}%')";
            $jumlahFiltered = Ketetapan::whereRaw("{$where}")->orderBy($orderBy, $orderType)
                ->count(); //hitung data yang telah terfilter
            $data = Ketetapan::whereRaw($where)
                ->orderBy($orderBy, $orderType)
                ->offset($offset)
                ->limit($limit)
                ->get();
        } else {
            $jumlahFiltered = $jumlahTotal;
            $data = Ketetapan::orderBy($orderBy, $orderType)
                ->offset($offset)
                ->limit($limit)
                ->get();
        }

        $result = [];

        foreach ($data as $no => $dt) {
            $linkdelete = url('master-data/deleteJk', $dt->id);
            $action = '<center>
                                <div class="btn-group btn-group-sm">
                            <button data-toggle="tooltip" data-original-title="Edit" type="button" class="btn btn-xs btn-info btn-circle" onclick="editJk(\'' . $dt->id . '\')"><i class="fas fa-pencil-alt"></i></button>
                            <button onclick="buttonDeleteJk(this)" data-link="' . $linkdelete . '" data-toggle="tooltip" data-original-title="Delete" type="button" class="btn btn-xs btn-danger btn-circle"><i class="fas fa-trash"></i></button>
                            </div>
                            </center>';
            $result[] = [
                $start + $no + 1,
                $dt->jenis_ketetapan,
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
    public function editJk(Request $request)
    {
        $id = $request->id;
        $dt = Ketetapan::where('id', $id)->first();

        echo json_encode($dt);
    }
    public function deleteJk($id)
    {
        Ketetapan::where('id', $id)->delete();
        toast('Data sudah berhasil dihapus', 'success');
        return redirect()->back();
    }
}
