<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class Progress extends Model
{
    protected $primaryKey = 'id';
	protected $table = 'tb_progress';
	public $timestamps = false;
    public $incrementing = false; // ini untuk table yg primary key nya bukan auto increment

    public static function create($request){
    	$data = new Progress();
        $data->progress = $request->progress;
        $data->save();
        return $data;
    }
    public static function updateDt($request,$data){
        $data->progress = $request->progress;
        $data->save();
        return $data;
    }
}
?>