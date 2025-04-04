<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'length',
        'done',
        'assignees',
        'priority',
        'scheduled_day',
    ];

    protected $casts = [
        'done' => 'boolean',
        'assignees' => 'array',
        'scheduled_day' => 'date',
    ];
}
