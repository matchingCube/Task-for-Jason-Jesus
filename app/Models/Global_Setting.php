<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Global_Setting extends Model
{
    use HasFactory;

    protected $table = 'global_setting';

    protected $fillable = [
        'code',
        'value',
        'description'
    ];

    public $timestamps = false;
}
