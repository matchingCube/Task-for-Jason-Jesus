<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Scopes\CompanyScope;

class FixedRateSetting extends Model
{
    use HasFactory;

    protected $table = 'fixed_rate_setting';

    protected $fillable = [
        'from_miles',
        'to_miles',
        'fixed_rate',
        'company_id'
    ];

    public $timestamps = true;

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
