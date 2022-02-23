<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Models\BandingGugatanChild;

class BandingGugatan extends Model
{
    protected $primaryKey = 'id_bg';
	protected $table = 'tb_banding_gugatan';
	public $timestamps = false;
    public $incrementing = false; // ini untuk table yg primary key nya bukan auto increment

    public static function create($request){
    	$data = new BandingGugatan();
        $data->majelis = $request->majelis;
        $data->no_sengketa = $request->no_sengketa;
        $data->nama_wajib_pajak = $request->nama_wjb_pjk;
        $data->objek_bg = $request->ob_bg;
        $data->jenis_pajak = $request->jenis_pajak;
        $data->tahun_pajak = $request->tahun_pajak;
        $data->tanggal_putusan = date('Y-m-d', strtotime($request->tgl_ucp_pts));
        $data->amar_putusan = $request->amar_putusan;
        $data->nilai = $request->nilai;
        $data->keterangan = $request->keterangan;
        if(!empty($request->pet_sidang)){
            $pet_sidang = implode(",",$request->pet_sidang);
            $data->petugas_sidang = $pet_sidang;
        }
        if(!empty($request->pel_eksekutor)){
            $pel_eksekutor = implode(",",$request->pel_eksekutor);
            $data->pelaksana_eksekutor = $pel_eksekutor;
        }
        $data->no_bidang = $request->no_bidang;
        $data->tgl_objek = date('Y-m-d', strtotime($request->tgl_objek));
        $data->save();
        
        $id = DB::getPdo()->lastInsertId();
        foreach($request->urt_sidang as $key => $dt){
            $dataChild = new BandingGugatanChild();
            $dataChild->id_bg = $id;
            $dataChild->sidang_ke = $request->urt_sidang[$key];
            $dataChild->tanggal_sidang = date('Y-m-d', strtotime($request->tgl_sidang[$key]));
            $dataChild->status_sidang = $request->status_sidang[$key];
            $dataChild->save();   
        }
             
        return $data;
    }
    //update data utk user forecester
    public static function updateDt($request,$data){
        $data->majelis = $request->majelis;
        $data->no_sengketa = $request->no_sengketa;
        $data->nama_wajib_pajak = $request->nama_wjb_pjk;
        $data->objek_bg = $request->ob_bg;
        $data->jenis_pajak = $request->jenis_pajak;
        $data->tahun_pajak = $request->tahun_pajak;
        $data->tanggal_putusan = date('Y-m-d', strtotime($request->tgl_ucp_pts));
        $data->amar_putusan = $request->amar_putusan;
        $data->nilai = $request->nilai;
        $data->keterangan = $request->keterangan;
        if(!empty($request->pet_sidang)){
            $pet_sidang = implode(",",$request->pet_sidang);
            $data->petugas_sidang = $pet_sidang;
        }
        if(!empty($request->pel_eksekutor)){
            $pel_eksekutor = implode(",",$request->pel_eksekutor);
            $data->pelaksana_eksekutor = $pel_eksekutor;
        }
        $data->no_bidang = $request->no_bidang;
        $data->tgl_objek = date('Y-m-d', strtotime($request->tgl_objek));
        $data->save();

        foreach($request->urt_sidang as $key => $dt){
            $dataChild = new BandingGugatanChild();
            $dataChild->id_bg = $data->id_bg;
            $dataChild->sidang_ke = $request->urt_sidang[$key];
            $dataChild->tanggal_sidang = date('Y-m-d', strtotime($request->tgl_sidang[$key]));
            $dataChild->status_sidang = $request->status_sidang[$key];
            $dataChild->save();   
        }
             
        return $data;
    }

    public function BGchild(){
        return $this->hasMany('App\Models\BandingGugatanChild','id_bg');
    }
}
?>