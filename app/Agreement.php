<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Agreement extends Model {
    protected $table = 'agreement';
    protected $primaryKey = 'agreement_id';

    public function getSignDateAttribute($timestamp) {
        if(!empty($timestamp)) {
            return (new Carbon($timestamp))->format('d-M-Y h:i A');
        } else {
            return '';
        }
    }

    public function RoomRates() {
        return $this->hasMany("App\RoomType", "agreement_id", "agreement_id");
    }
}
