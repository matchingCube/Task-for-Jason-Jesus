<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Scopes\CompanyScope;

class Fleet extends Model
{
    use HasFactory;

    protected $table = 'fleets';

    protected $fillable = [
        'tractor_id',
        'model',
        'vin',
        'year',
        'license_plate',
        't_check',
        'pre_pass',
        'service_provider',
        'qiv',
        'bit',
        'domicile',
        'domicile_email',
        'book_value',
        'vedr',
        'eld',
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
