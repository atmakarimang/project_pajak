<?php

namespace App\Http\Controllers\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; // baru
use App\Models\DevError;
use App\Models\AsalPermohonan;
use App\Models\KriteriaPermohonan;
use DB;

class KriteriaPermohonanController extends Controller
{
    protected $PATH_VIEW = 'masterdata.KriteriaPermohonan.';

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
    public function storeKriteria(Request $request)
    {
        $user = Auth::user();
        $mode = strtolower($request->input("mode"));
        $results = $request->all();
        $error = 0;

        DB::beginTransaction();
        if ($mode == "edit") {
            $id_kp = $request->id_kp;
            $kpr = KriteriaPermohonan::where('id', $id_kp)->first();
            try {
                $data = KriteriaPermohonan::updateDt($request, $kpr);
            } catch (\Exception $e) {
                $devError = new DevError;
                $devError->form = "Update Kriteria Permohonan";
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
                    'message' => "Kriteria Permohonan gagal diupdated!",
                ];
            }
        } else {
            $cek_data = KriteriaPermohonan::find($request->kp);
            if ($cek_data) {
                $error_duplikat[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => 'Kriteria Permohonan ' . $request->kp . ' sudah digunakan!',
                ];
                $data["error_duplikat"] = $error_duplikat;
                // return redirect()->back()->with($data);
                return redirect()->back();
            }
            try {
                $data = KriteriaPermohonan::create($request);
            } catch (\Exception $e) {
                $devError = new DevError;
                $devError->form = "Add Kriteria Permohonan";
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
                    'message' => "Kriteria Permohonan gagal disimpan!",
                ];
            }
        }

        if ($error == 0) {
            DB::commit();
            if ($mode == 'add') {
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => 'Kriteria Permohonan baru ' . $request->kp . ' telah ditambahkan!',
                ];
            } else {
                $flashs[] = [
                    'type' => 'success', // option : info, warning, success, error
                    'title' => 'Success',
                    'message' => 'Kriteria Permohonan ' . $request->kp . ' telah diupdated!',
                ];
            }
        }

        $data["flashs"] = $flashs;

        toast($flashs[0]['message'], $flashs[0]['type']);

        return redirect()->route('kr_permohonan.index');
    }
    public function ajaxDataKriteria(Request $request)
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

        $jumlahTotal = KriteriaPermohonan::count();

        if ($search) { // filter data
            $where = " kriteria_permohonan like lower('%{$search}%')";
            $jumlahFiltered = KriteriaPermohonan::whereRaw("{$where}")->count(); //hitung data yang telah terfilter
            if ($orderBy != null) {
                $data = KriteriaPermohonan::whereRaw($where)->orderBy($orderBy, $orderType)->get();
            } else {
                $data = KriteriaPermohonan::whereRaw($where)->get();
            }
        } else {
            $jumlahFiltered = $jumlahTotal;
            if ($orderBy != null) {
                $data = KriteriaPermohonan::offset($offset)->limit($limit)->orderBy($orderBy, $orderType)->get();
            } else {
                $data = KriteriaPermohonan::offset($offset)->limit($limit)->get();
            }
        }
        $result = [];
        foreach ($data as $no => $dt) {
            $linkdelete = url('/master-data/deleteKriteria', $dt->id);
            $action = '<center>
                            <div class="btn-group btn-group-sm">
                            <button data-toggle="tooltip" title="Edit" type="button" class="btn btn-xs btn-primary btn-circle" onclick="editKriteria(\'' . $dt->id . '\')"><i class="fas fa-pencil-alt"></i></button>
                            <button onclick="buttonDeleteKri(this)" data-link="' . $linkdelete . '" data-toggle="tooltip" title="Delete" type="button" class="btn btn-xs btn-danger btn-circle"><i class="fas fa-trash"></i></button>
                            </div>
                        </center>';
            $result[] = [
                $start + $no + 1,
                $dt->kriteria_permohonan,
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
    public function editKriteria(Request $request)
    {
        $id_kp = $request->id;
        $dt = KriteriaPermohonan::where('id', $id_kp)->first();

        echo json_encode($dt);
    }
    public function deleteKriteria($id)
    {
        KriteriaPermohonan::where('id', $id)->delete();

        toast('Data sudah berhasil dihapus', 'success');
        return redirect()->back();
    }
}
