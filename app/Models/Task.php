<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks';

    protected $fillable = [
        'name',
        'recurring',
        'from_date',
        'to_date',
        'due_date',
        'interval',
        'status',
        'user_id',
        'owner_id'
    ];

    protected $appends = [
        'is_creator'
    ];

    public $timestamps = false;

    public function creator()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function getIsCreatorAttribute()
    {
        return $this->user_id == Auth::user()->id;
    }

    public function owner()
    {
        return $this->hasOne(User::class, 'id', 'owner_id');
    }
}
