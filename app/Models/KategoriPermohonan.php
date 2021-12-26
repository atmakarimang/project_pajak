<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class KategoriPermohonan extends Model
{
    protected $primaryKey = 'id';
	protected $table = 'tb_kat_permohonan';
	public $timestamps = false;
    public $incrementing = false; // ini untuk table yg primary key nya bukan auto increment

    public static function create($request){
    	$data = new KategoriPermohonan();
        $data->kat_permohonan = $request->kat_pemohon;
        $data->save();
        return $data;
    }
}
?>