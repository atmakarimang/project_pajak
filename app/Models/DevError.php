<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class DevError extends Model {

	protected $primaryKey = 'id_dev_error';
	protected $table = 'tb_dev_error';
	public $timestamps = false;
	
}