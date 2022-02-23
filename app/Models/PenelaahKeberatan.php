<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class PenelaahKeberatan extends Model
{
    protected $primaryKey = 'id';
	protected $table = 'tb_penelaah_keberatan';
	public $timestamps = false;
    public $incrementing = false; // ini untuk table yg primary key nya bukan auto increment

    public static function create($request){
    	$data = new PenelaahKeberatan();
        $data->nama_penelaah = $request->nama_pk;
        $data->save();
        return $data;
    }
    public static function updateDt($request,$data){
        $data->nama_penelaah = $request->nama_pk;
        $data->save();
        return $data;
    }
}
?>