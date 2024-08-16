<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Job extends Model
{
    protected $table = 'jobs';
    protected $primaryKey = 'job_id';

    public function getConfirmationDateAttribute($timestamp) {
        return (new Carbon($timestamp))->format('d-M-Y');
    }

    public static function withCompanyAndHotelInfo($job_id) {
        return self::selectRaw("jobs.job_id, jobs.title, company.company_name, agreement.hotel_name, agreement.comm_id, agreement.commission, chain.name as chain_name, brand.name as brand_name, CONCAT(states.name, ' (', states.code, ')') as state_name, commission.comm_type, Min(rooms_info.check_in) as check_in, Max(rooms_info.check_out) as check_out")
            ->leftJoin("company", "company.company_id", "=", "jobs.company_id")
            ->leftJoin("agreement", "agreement.agreement_id", "=", "jobs.agreement_id")
            ->leftJoin("chain", "chain.chain_id", "=", "agreement.chain_id")
            ->leftJoin("brand", "brand.brand_id", "=", "agreement.brand_id")
            ->leftJoin("states", "states.state_id", "=", "agreement.state_id")
            ->leftJoin("commission", "commission.comm_id", "=", "agreement.comm_id")
            ->leftJoin('rooms_info', 'rooms_info.job_id', '=', 'jobs.job_id')
            ->groupBy('rooms_info.job_id')
            ->where("jobs.job_id", $job_id)->first();
    }

    public function getCheckInAttribute($timestamp) {
        return (new Carbon($timestamp))->format('d-M-Y');
    }

    public function getCheckOutAttribute($timestamp) {
        return (new Carbon($timestamp))->format('d-M-Y');
    }
}

