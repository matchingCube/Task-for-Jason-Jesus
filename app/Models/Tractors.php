<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tractors extends Model
{
    use HasFactory;

    protected $table = 'tractors';

    protected $fillable = [
        'tractor_id',
        'model',
        'vin',
        'year',
        'license_plate',
        'last_bit_miles',
        'bit',
        'oil_changes',
        'insurance_book_value',
        'smart_witness_serial',
        'omnitracs_device_id',
        'pre_pass',
        't_check'
    ];

    public $timestamps = false;
}
