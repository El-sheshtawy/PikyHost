<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TaskUser extends Pivot
{
    protected $table = 'task_user';

    protected $fillable = [
        'role',
        'task_id',
        'user_id',
    ];
}
