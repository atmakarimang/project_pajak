<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class NonPelaksanaBidang extends Model
{
    protected $primaryKey = 'no_agenda';
	protected $table = 'tb_non_pelaksana_bidang';
	public $timestamps = false;
    public $incrementing = false; // ini untuk table yg primary key nya bukan auto increment

    public static function create($request){
    	$data = new NonPelaksanaBidang();
        $data->no_agenda = $request->no_agenda;
        $data->tgl_agenda = date('Y:m:d H:i:s', strtotime($request->tgl_agenda));
        $data->no_surat = $request->no_surat;
        $data->tgl_surat = date('Y-m-d', strtotime($request->tgl_surat));
        $data->tgl_diterima_kanwil = date('Y-m-d', strtotime($request->tgl_diterima_kanwil));
        $data->asal_surat = $request->asal_surat;
        $data->hal = $request->hal;
        $data->npwp = $request->no_npwp;
        $data->nama_wajib_pajak = $request->nama_npwp;
        if(!empty($request->seksi_konseptor)){
            $seksiKonseptor = implode(",",$request->seksi_konseptor);
            $data->seksi_konseptor = $seksiKonseptor;
        }
        if(!empty($request->kepala_seksi)){
            $kep_sek = implode(",",$request->kepala_seksi);
            $data->kepala_seksi = $kep_sek;
        }
        $data->penerima_disposisi = $request->penerima_disposisi;
        $data->status = $request->status;
        $data->no = substr($request->no_agenda,3,7);
        $data->tahun = date('Y');
        $data->save();
        return $data;
    }

    public static function updateDt($request,$data){
        $data->no_agenda = $request->no_agenda;
        $data->tgl_agenda = date('Y-m-d', strtotime($request->tgl_agenda));
        $data->no_surat = $request->no_surat;
        $data->tgl_surat = date('Y-m-d', strtotime($request->tgl_surat));
        $data->tgl_diterima_kanwil = date('Y-m-d', strtotime($request->tgl_diterima_kanwil));
        $data->asal_surat = $request->asal_surat;
        $data->hal = $request->hal;
        $data->npwp = $request->no_npwp;
        $data->nama_wajib_pajak = $request->nama_npwp;
        if(!empty($request->seksi_konseptor)){
            $seksiKonseptor = implode(",",$request->seksi_konseptor);
            $data->seksi_konseptor = $seksiKonseptor;
        }
        if(!empty($request->kepala_seksi)){
            $kep_sek = implode(",",$request->kepala_seksi);
            $data->kepala_seksi = $kep_sek;
        }
        $data->penerima_disposisi = $request->penerima_disposisi;
        $data->status = $request->status;
        $data->save();
        return $data;
    }
}
?>