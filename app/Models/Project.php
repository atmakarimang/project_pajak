<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_project';
	protected $table = 'tb_project';
	public $timestamps = false;
    public $incrementing = false; // ini untuk table yg primary key nya bukan auto increment

    public static function create($request){
    	date_default_timezone_set("Asia/Jakarta");
    	$project = new Project();
        $project->name = $request->project_name;
        $project->description = $request->description;
        $project->status = $request->status;
        $project->client = $request->client;
        $project->attachment = $request->attachment;
        $project->start_date = $request->start_date;
        $project->end_date = $request->end_date;
        $project->created_at = date('Y-m-d H:i:s');
        $project->save();
        return $project;
    }
}
