<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class GroupPermissions extends Model {
	
	protected $table = 'group_permissions';
	protected $primaryKey = null;
	public $incrementing = false;
	public $timestamps = false;
}
