<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    use HasFactory;

    protected $table = 'role_permission';

    protected $fillable = [
        'role_id',
        'permission_id',
    ];
    
    public $timestamps = false;

    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    public function permission()
    {
        return $this->hasOne(Permission::class, 'id', 'permission_id');
    }
}
