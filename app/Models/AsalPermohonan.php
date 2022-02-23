<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class AsalPermohonan extends Model
{
    protected $primaryKey = 'id';
	protected $table = 'tb_asal_permohonan';
	public $timestamps = false;
    public $incrementing = false; // ini untuk table yg primary key nya bukan auto increment

    public static function create($request){
    	$data = new AsalPermohonan();
        $data->id = $request->kode_pmh;
        $data->pemohon = $request->pemohon;
        $data->save();
        return $data;
    }
    public static function updateDt($request,$data){
        $data->id = $request->kode_pmh;
        $data->pemohon = $request->pemohon;
        $data->save();
        return $data;
    }
}
?>