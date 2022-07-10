<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTask extends Model
{
    use HasFactory;

    protected $table = 'user_task';

    protected $fillable = [
        'user_id',
        'task_id',
    ];
    
    public $timestamps = false;

    public function task()
    {
        return $this->hasOne(Task::class, 'id', 'task_id')->with('creator')->with('owner');
    }
}
