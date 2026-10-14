<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class WhatsAppSetting extends Model
{
    protected $table = 'whatsapp_settings';

    protected $fillable = [
        'api_version',
        'access_token',
        'phone_number_id',
        'business_account_id',
        'status',
    ];

    public function setAccessTokenAttribute($value)
    {
        if (!empty($value)) {
            // Hindari enkripsi ganda jika nilainya sudah terenkripsi
            try {
                Crypt::decryptString($value);
                $this->attributes['access_token'] = $value;
            } catch (\Throwable $e) {
                $this->attributes['access_token'] = Crypt::encryptString($value);
            }
        } else {
            $this->attributes['access_token'] = null;
        }
    }

    public function getDecryptedAccessTokenAttribute(): ?string
    {
        if (empty($this->access_token)) {
            return null;
        }

        try {
            return Crypt::decryptString($this->access_token);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
