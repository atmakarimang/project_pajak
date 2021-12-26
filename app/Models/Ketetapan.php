<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class Ketetapan extends Model
{
    protected $primaryKey = 'id';
	protected $table = 'tb_ketetapan';
	public $timestamps = false;
    public $incrementing = false; // ini untuk table yg primary key nya bukan auto increment

    public static function create($request){
    	$data = new Ketetapan();
        $data->jenis_ketetapan = $request->jenis_ketetapan;
        $data->save();
        return $data;
    }
}
?>