<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignPageView extends Model
{
    // Tabela de log — sem updated_at
    const UPDATED_AT = null;

    protected $table = 'campaign_page_views';

    protected $fillable = [
        'bairro_id',
        'session_id',
        'ip_hash',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_content',
        'utm_term',
        'referrer',
        'user_agent',
        'device_type',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Relacionamentos

    public function bairro(): BelongsTo
    {
        return $this->belongsTo(Bairro::class, 'bairro_id');
    }

    // Scopes de filtro

    public function scopeWherePeriod($query, string $period)
    {
        return match ($period) {
            'hoje'   => $query->whereDate('created_at', today()),
            '7d'     => $query->where('created_at', '>=', now()->subDays(7)),
            '30d'    => $query->where('created_at', '>=', now()->subDays(30)),
            default  => $query,
        };
    }

    public function scopeWhereUtmSource($query, ?string $source)
    {
        if (!$source) return $query;
        return $query->where('utm_source', $source);
    }

    public function scopeWhereBairroId($query, ?int $bairroId)
    {
        if (!$bairroId) return $query;
        return $query->where('bairro_id', $bairroId);
    }
}
