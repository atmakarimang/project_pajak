<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model {
	protected $primaryKey = 'user_id';
	protected $table = 'tb_user';
	public $timestamps = false;
	public $incrementing = false; // ini untuk table yg primary key nya bukan auto increment

	protected $fillable = [
		'user_id','nama','password','jabatan','peran'
	];
}