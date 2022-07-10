<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Scopes\CompanyScope;

class Person_Photo extends Model
{
    use HasFactory;

    protected $table = 'person_photo';

    protected $fillable = [
        'name',
        'ext',
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
