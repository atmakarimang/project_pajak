<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KriteriaPermohonan extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'tb_kriteria_permohonan';
    public $timestamps = false;
    public $incrementing = false; // ini untuk table yg primary key nya bukan auto increment

    public static function create($request)
    {
        $data = new KriteriaPermohonan();
        $data->kriteria_permohonan = $request->kriteria_permohonan;
        $data->save();
        return $data;
    }
    public static function updateDt($request, $data)
    {
        $data->kriteria_permohonan = $request->kriteria_permohonan;
        $data->save();
        return $data;
    }
}
