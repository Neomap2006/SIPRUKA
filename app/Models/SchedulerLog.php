<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchedulerLog extends Model
{
    protected $table = 'scheduler_logs';

    protected $fillable = [
        'type',
        'recipient',
        'message',
        'status',
        'response_details',
        'executed_at',
    ];

    protected $casts = [
        'executed_at' => 'datetime',
    ];
}
