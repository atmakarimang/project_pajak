<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class BandingGugatanChild extends Model
{
    protected $primaryKey = ['id','id_bg'];
	protected $table = 'tb_banding_gugatan_child';
	public $timestamps = false;
    public $incrementing = false; // ini untuk table yg primary key nya bukan auto increment

    public static function create($request){
    	$data = new BandingGugatanChild();
        $data->id_bg = $request->id_bg;
        $data->sidang_ke = $request->sidang_ke;
        $data->tanggal_sidang = $request->tanggal_sidang;
        $data->save();
        return $data;
    }
}
?>