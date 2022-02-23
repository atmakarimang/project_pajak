<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class Pajak extends Model
{
    protected $primaryKey = 'id';
	protected $table = 'tb_pajak';
	public $timestamps = false;
    public $incrementing = false; // ini untuk table yg primary key nya bukan auto increment

    public static function create($request){
    	$data = new Pajak();
        $data->pajak = $request->pajak;
        $data->save();
        return $data;
    }
    public static function updateDt($request,$data){
        $data->pajak = $request->pajak;
        $data->save();
        return $data;
    }
}
?>