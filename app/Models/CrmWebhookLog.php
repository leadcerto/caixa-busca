<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmWebhookLog extends Model
{
    protected $table = 'crm_webhook_logs';

    protected $fillable = [
        'status',
        'status_code',
        'payload',
        'resposta',
        'is_teste',
    ];

    protected $casts = [
        'payload'  => 'array',
        'is_teste' => 'boolean',
    ];

    public function corStatus(): string
    {
        return match ($this->status) {
            'sucesso' => 'green',
            'falha'   => 'yellow',
            default   => 'red',
        };
    }
}
