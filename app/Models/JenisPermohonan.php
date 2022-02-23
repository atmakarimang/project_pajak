<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class JenisPermohonan extends Model
{
    protected $primaryKey = 'id';
	protected $table = 'tb_jenis_permohonan';
	public $timestamps = false;
    public $incrementing = false; // ini untuk table yg primary key nya bukan auto increment

    public static function create($request){
    	$data = new JenisPermohonan();
        $data->jenis_permohonan = $request->jenis_permohonan;
        $data->save();
        return $data;
    }
    public static function updateDt($request,$data){
        $data->jenis_permohonan = $request->jenis_permohonan;
        $data->save();
        return $data;
    }
}
?>