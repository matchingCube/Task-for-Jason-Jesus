<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Scopes\CompanyScope;

class Other_Settlement_Adjustments extends Model
{
    use HasFactory;

    protected $table = 'other_settlement_adjustments';

    protected $fillable = [
        'year_num',
        'week_num',
        'date',
        'type',
        'description',
        'amt',
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
