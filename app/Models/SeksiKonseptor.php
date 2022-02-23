<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class SeksiKonseptor extends Model
{
    protected $primaryKey = 'id';
	protected $table = 'tb_seksi_konseptor';
	public $timestamps = false;
    public $incrementing = false; // ini untuk table yg primary key nya bukan auto increment

    public static function create($request){
    	$data = new SeksiKonseptor();
        $data->seksi_konseptor = $request->seksi_konseptor;
        $data->save();
        return $data;
    }
    public static function updateDt($request,$data){
        $data->seksi_konseptor = $request->seksi_konseptor;
        $data->save();
        return $data;
    }
}
?>