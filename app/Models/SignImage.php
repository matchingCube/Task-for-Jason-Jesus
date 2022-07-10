<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Scopes\CompanyScope;

class SignImage extends Model
{
    use HasFactory;

    protected $table = 'sign_images';

    protected $fillable = [
        'name',
        'extension',
        'path',
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
