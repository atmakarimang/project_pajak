<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class PelaksanaEksekutor extends Model
{
    protected $primaryKey = 'id';
	protected $table = 'tb_pelaksana_eksekutor';
	public $timestamps = false;
    public $incrementing = false; // ini untuk table yg primary key nya bukan auto increment

    public static function create($request){
    	$data = new PelaksanaEksekutor();
        $data->pelaksana_eksekutor = $request->pel_eksekutor;
        $data->save();
        return $data;
    }

    public static function updateDt($request,$data){
        $data->pelaksana_eksekutor = $request->pel_eksekutor;
        $data->save();
        return $data;
    }
}
?>