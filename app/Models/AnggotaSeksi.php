<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class AnggotaSeksi extends Model
{
    protected $primaryKey = 'id';
	protected $table = 'tb_anggota_seksi';
	public $timestamps = false;
    public $incrementing = false; // ini untuk table yg primary key nya bukan auto increment

    public static function create($request){
    	$data = new AnggotaSeksi();
        $data->nama_anggota = $request->nama_kepala;
        $data->save();
        return $data;
    }

    public static function updateDt($request,$data){
        $data->nama_anggota = $request->nama_kepala;
        $data->save();
        return $data;
    }
}
?>