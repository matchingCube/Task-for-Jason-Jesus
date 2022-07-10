<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Scopes\CompanyScope;

class Linehaul_Trips extends Model
{
    use HasFactory;

    protected $table = 'linehaul_trips';

    protected $fillable = [
        'year_num',
        'week_num',
        'date',
        'vehicle',
        'trip_id',
        'leg_org',
        'leg_dest',
        'zip_postal',
        'miles_qty',
        'vmr_rate',
        'mileage_plus',
        'premiums',
        'fuel',
        'total_rate',
        'amt_1',
        'pkgs',
        'amt_2',
        'd_and_h',
        'tolls',
        'flat_rate',
        'daily_gross_amt',
        'driver_1',
        'driver_2',
        'company_id'
    ];

    public $timestamps = false;

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new CompanyScope);
    }
}
