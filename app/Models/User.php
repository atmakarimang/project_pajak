<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
	protected $primaryKey = 'user_id';
	protected $table = 'tb_user';
	public $timestamps = false;
	public $incrementing = false; // ini untuk table yg primary key nya bukan auto increment

	protected $fillable = [
		'user_id', 'nama', 'password', 'jabatan', 'peran'
	];
}
