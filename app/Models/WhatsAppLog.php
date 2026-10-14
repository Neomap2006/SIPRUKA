<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsAppLog extends Model
{
    protected $table = 'whatsapp_logs';

    protected $fillable = [
        'recipient',
        'type',
        'message',
        'status',
        'message_id',
        'detail',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];
}
