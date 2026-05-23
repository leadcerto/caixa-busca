<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmConfiguracao extends Model
{
    protected $table = 'crm_configuracoes';

    protected $fillable = [
        'webhook_url',
        'webhook_token',
        'ativo',
        'ultimo_envio_em',
        'ultimo_status',
    ];

    protected $casts = [
        'ativo'          => 'boolean',
        'ultimo_envio_em' => 'datetime',
    ];

    public static function atual(): ?static
    {
        return static::first();
    }
}
