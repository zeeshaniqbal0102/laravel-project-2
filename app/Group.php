<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Group extends Model {
	
	protected $table = 'groups';
	protected $primaryKey = 'GroupID';
	const CREATED_AT = 'DateAdded';
    const UPDATED_AT = 'DateModified';
	
	public function getDateAddedAttribute($timestamp) {
		return (new Carbon($timestamp))->format('d-M-Y h:i A');
	}
	public function getDateModifiedAttribute($timestamp) {
		return (new Carbon($timestamp))->format('d-M-Y h:i A');
	}
	
	public function permissions() {
		return $this->hasMany('App\GroupPermissions', 'GroupID', 'GroupID');
	}
}
