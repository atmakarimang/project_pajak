<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class AmarPutusan extends Model
{
    protected $primaryKey = 'id';
	protected $table = 'tb_amar_putusan';
	public $timestamps = false;
    public $incrementing = false; // ini untuk table yg primary key nya bukan auto increment

    public static function create($request){
    	$data = new AmarPutusan();
        $data->amar_putusan = $request->amar_putusan;
        $data->save();
        return $data;
    }
    public static function updateDt($request,$data){
        $data->amar_putusan = $request->amar_putusan;
        $data->save();
        return $data;
    }
}
?>