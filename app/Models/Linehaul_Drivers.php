<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Scopes\CompanyScope;

class Linehaul_Drivers extends Model
{
    use HasFactory;

    protected $table = 'linehaul_drivers';

    protected $fillable = [
        'driver_id',
        'driver_name',
        'email',
        'phone',
        'license',
        'address',
        'price_per_mile',
        'work_status',
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
