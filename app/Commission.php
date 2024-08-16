<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model {
	
	protected $table = 'commission';
	protected $primaryKey = null;
	public $incrementing = false;
	public $timestamps = false;
}
