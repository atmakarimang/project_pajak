<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class PelaksanaBidang extends Model
{
    protected $primaryKey = 'no_agenda';
    protected $table = 'tb_pelaksana_bidang';
    public $timestamps = false;
    public $incrementing = false; // ini untuk table yg primary key nya bukan auto increment

    public static function create($request)
    {
        $data = new PelaksanaBidang();
        $data->no_agenda = $request->no_agenda;
        $data->tgl_agenda = date('Y-m-d', strtotime($request->tgl_agenda));
        $data->no_naskah_dinas = (!empty($request->no_naskahdinas)) ? $request->no_naskahdinas : "";
        $data->tgl_naskah_dinas = date('Y-m-d', strtotime($request->tgl_naskahdinas));
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
        if (!empty($request->masa_pajak)) {
            $masa_pjk = implode(",", $request->masa_pajak);
            $data->masa_pajak = $masa_pjk;
        }
        $data->tahun_pajak = $request->tahun_pajak;
        $data->kat_permohonan = $request->kat_permohonan;
        $data->no_srt_permohonan = $request->no_srt_per;
        $data->tgl_srt_permohonan = date('Y-m-d', strtotime($request->tgl_srtper));
        if (!empty($request->seksi_konseptor)) {
            $seksiKonseptor = implode(",", $request->seksi_konseptor);
            $data->seksi_konseptor = $seksiKonseptor;
        }
        $data->status = $request->status;
        $data->progress = $request->progress;
        $data->jumlah_byr_pmk = (!empty($request->jumlah_bayar)) ? preg_replace('/[^\d\.]/', '', $request->jumlah_bayar) : 0;
        $jbp  = floatval(str_replace(',', '.', str_replace('.', '', $request->jumlah_bayar)));
        $data->jumlah_byr_pmk = (!empty($request->jumlah_bayar)) ? $jbp : 0;
        $data->tgl_byr_pmk = (!empty($request->tgl_bayar)) ? date('Y-m-d', strtotime($request->tgl_bayar)) : null;
        $data->no = substr($request->no_agenda, 2, 7);
        $data->tahun = date('Y');
        $data->save();
        return $data;
    }

    public static function updateDt($request, $data)
    {
        $data->no_naskah_dinas = $request->no_naskahdinas;
        $data->tgl_naskah_dinas = date('Y-m-d', strtotime($request->tgl_naskahdinas));
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
        if (!empty($request->masa_pajak)) {
            $masa_pjk = implode(",", $request->masa_pajak);
            $data->masa_pajak = $masa_pjk;
        }
        $data->tahun_pajak = $request->tahun_pajak;
        $data->kat_permohonan = $request->kat_permohonan;
        $data->no_srt_permohonan = $request->no_srt_per;
        $data->tgl_srt_permohonan = date('Y-m-d', strtotime($request->tgl_srtper));
        if (!empty($request->seksi_konseptor)) {
            $seksiKonseptor = implode(",", $request->seksi_konseptor);
            $data->seksi_konseptor = $seksiKonseptor;
        }
        $data->status = $request->status;
        $data->progress = $request->progress;
        // $data->jumlah_byr_pmk = preg_replace('/[^\d\.]/', '', $request->jumlah_bayar);
        $jbp  = floatval(str_replace(',', '.', str_replace('.', '', $request->jumlah_bayar)));
        $data->jumlah_byr_pmk = (!empty($request->jumlah_bayar)) ? $jbp : 0;
        $data->tgl_byr_pmk = (!empty($request->tgl_bayar)) ? date('Y-m-d', strtotime($request->tgl_bayar)) : null;
        // dd($data);
        $data->save();
        return $data;
    }

    public static function createKasi($request, $data)
    {
        if (!empty($request->seksi_konseptor)) {
            $seksiKonseptor = implode(",", $request->seksi_konseptor);
            $data->seksi_konseptor = $seksiKonseptor;
        }
        $data->status = $request->status;
        $data->progress = $request->progress;
        // dd($data);
        if (!empty($request->kepala_seksi)) {
            $kep_sek = implode(",", $request->kepala_seksi);
            $data->kepala_seksi = $kep_sek;
        }
        if (!empty($request->pk_konseptor)) {
            $pk_konseptor = implode(",", $request->pk_konseptor);
            $data->pk_konseptor = $pk_konseptor;
        }
        // dd($request->jumlah_bayar_awl);
        $data->no_produk_hukum = $request->no_prodhukum;
        $data->tgl_produk_hukum = date('Y-m-d', strtotime($request->tgl_prodhukum));
        // $jba = preg_replace('/[^\d\.]/', '',$request->jumlah_bayar_awl);
        // $tk = preg_replace('/[^\d\.]/', '',$request->jumlah_tbh);
        // $jbp = preg_replace('/[^\d\.]/', '',$request->jumlah_bayarprod);

        $jba = floatval(str_replace(',', '.', str_replace('.', '', $request->jumlah_bayar_awl)));
        $tbh  = floatval(str_replace(',', '.', str_replace('.', '', $request->jumlah_tbh)));
        $krg  = floatval(str_replace(',', '.', str_replace('.', '', $request->jumlah_krg)));
        $jbp = floatval(str_replace(',', '.', str_replace('.', '', $request->jumlah_bayarprod)));

        //dd($jba, $tk, $jbp);
        $data->jml_byr_semula = $jba;
        $data->tambah = $tbh;
        $data->kurang = $krg;
        $data->jml_byr_produk = $jbp;
        $data->hasil_keputusan = $request->hsl_kep;
        if ($request->hasFile('noresi')) {
            $resi = $request->hasFile("noresi");
            $ext = strtolower($request->noresi->getClientOriginalExtension());
            $filename = "$request->no_agenda.$ext";
            $request->noresi->move('public/buktiResi/', $filename);
            $data->no_resi = $filename;
        }
        $data->tgl_resi = date('Y-m-d', strtotime($request->tgl_resi));
        $data->no_srt_pengantar = $request->no_srtpengantar;
        $data->tgl_srt_pengantar = date('Y-m-d', strtotime($request->tgl_srtpengantar));
        // dd($data);
        $data->save();
        return $data;
    }
}
