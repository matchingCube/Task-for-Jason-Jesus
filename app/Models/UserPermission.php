<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    use HasFactory;

    protected $table = 'user_permission';

    protected $fillable = [
        'user_id',
        'permission_id',
    ];
    
    public $timestamps = false;

    public function permission()
    {
        return $this->hasOne(Permission::class, 'id', 'permission_id');
    }
}
