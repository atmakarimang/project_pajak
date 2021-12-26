<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class PetugasSidangBanding extends Model
{
    protected $primaryKey = 'id';
	protected $table = 'tb_petugas_sidang';
	public $timestamps = false;
    public $incrementing = false; // ini untuk table yg primary key nya bukan auto increment

    public static function create($request){
    	$data = new PetugasSidangBanding();
        $data->nama_petugas = $request->nama_petugas;
        $data->save();
        return $data;
    }
}
?>