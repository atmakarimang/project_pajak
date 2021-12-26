<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class Keputusan extends Model
{
    protected $primaryKey = 'id';
	protected $table = 'tb_keputusan';
	public $timestamps = false;
    public $incrementing = false; // ini untuk table yg primary key nya bukan auto increment

    public static function create($request){
    	$data = new Keputusan();
        $data->keputusan = $request->keputusan;
        $data->save();
        return $data;
    }
}
?>