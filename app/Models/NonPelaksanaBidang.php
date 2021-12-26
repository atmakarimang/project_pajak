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
        $data->tgl_agenda = date('Y-m-d', strtotime($request->tgl_agenda));
        $data->no_naskah_dinas = $request->no_naskahdinas;
        $data->tgl_naskah_dinas = date('Y-m-d', strtotime($request->tgl_naskahdinasate));
        $data->pemohon = $request->asal_permohonan;
        $data->no_lbr_pengawas_dok = $request->no_arusdok;
        $data->tgl_diterima_kpp = date('Y-m-d', strtotime($request->tgl_in_kpp));
        $data->tgl_diterima_kanwil = date('Y-m-d', strtotime($request->tgl_in_kanwil));
        $data->npwp = $request->no_npwp;
        $data->nama_wajib_pajak = $request->nama_npwp;
        $data->jenis_permohonan = $request->jenis_permohonan;
        $data->pajak = $request->jenis_pajak;
        $data->jenis_ketetapan = $request->jenis_ketetapan;
        $data->no_ketetapan = $request->no_ketetapan;
        $data->tgl_ketetapan = date('Y-m-d', strtotime($request->tgl_ketetapan));
        $data->masa_pajak = $request->masa_pajak;
        $data->tahun_pajak = $request->tahun_pajak;
        $data->kat_permohonan = $request->kat_permohonan;
        $data->no_srt_permohonan = $request->no_srt_per;
        $data->tgl_srt_permohonan = date('Y-m-d', strtotime($request->tgl_srtper));
        $seksiKonseptor = implode(",",$request->seksi_konseptor);
        $data->seksi_konseptor = $seksiKonseptor;
        $data->status = $request->status;
        $data->progress = $request->progress;
        $data->jumlah_byr_pmk = $request->jumlah_bayar;
        $data->tgl_byr_pmk = date('Y-m-d', strtotime($request->tgl_bayar));
        $data->save();
        return $data;
    }

    public static function updateDt($request,$data){
        $data->tgl_agenda = date('Y-m-d', strtotime($request->tgl_agenda));
        $data->no_naskah_dinas = $request->no_naskahdinas;
        $data->tgl_naskah_dinas = date('Y-m-d', strtotime($request->tgl_naskahdinasate));
        $data->pemohon = $request->asal_permohonan;
        $data->no_lbr_pengawas_dok = $request->no_arusdok;
        $data->tgl_diterima_kpp = date('Y-m-d', strtotime($request->tgl_in_kpp));
        $data->tgl_diterima_kanwil = date('Y-m-d', strtotime($request->tgl_in_kanwil));
        $data->npwp = $request->no_npwp;
        $data->nama_wajib_pajak = $request->nama_npwp;
        $data->jenis_permohonan = $request->jenis_permohonan;
        $data->pajak = $request->jenis_pajak;
        $data->jenis_ketetapan = $request->jenis_ketetapan;
        $data->no_ketetapan = $request->no_ketetapan;
        $data->tgl_ketetapan = date('Y-m-d', strtotime($request->tgl_ketetapan));
        $data->masa_pajak = $request->masa_pajak;
        $data->tahun_pajak = $request->tahun_pajak;
        $data->kat_permohonan = $request->kat_permohonan;
        $data->no_srt_permohonan = $request->no_srt_per;
        $data->tgl_srt_permohonan = date('Y-m-d', strtotime($request->tgl_srtper));
        $seksiKonseptor = implode(",",$request->seksi_konseptor);
        $data->seksi_konseptor = $seksiKonseptor;
        $data->status = $request->status;
        $data->progress = $request->progress;
        $data->jumlah_byr_pmk = $request->jumlah_bayar;
        $data->tgl_byr_pmk = date('Y-m-d', strtotime($request->tgl_bayar));
        $data->save();
        return $data;
    }

    public static function createKasi($request,$data){
        $data->kepala_seksi = $request->kepala_seksi;
        $data->pk_konseptor = $request->pk_konseptor;
        $data->no_produk_hukum = $request->no_prodhukum;
        $data->tgl_produk_hukum = $request->tgl_prodhukum;
        $data->jml_byr_semula = $request->jumlah_bayar_awl;
        $data->tambah_kurang = $request->jumlah_tbh;
        $data->jml_byr_produk = $request->jumlah_bayarprod;
        $data->hasil_keputusan = $request->hsl_kep;
        $data->no_resi = $request->noresi;
        $data->tgl_resi = $request->tgl_resi;
        $data->no_srt_pengantar = $request->no_srtpengantar;
        $data->tgl_srt_pengantar = $request->tgl_srtpengantar;
        $data->save();
        return $data;
    }
}
?>