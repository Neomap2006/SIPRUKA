<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsAppScheduler extends Model
{
    protected $table = 'whatsapp_schedulers';

    protected $fillable = [
        'recipient_user_ids',
        'target_time',
        'is_active',
    ];

    protected $casts = [
        'recipient_user_ids' => 'array',
        'is_active' => 'boolean',
    ];
}
