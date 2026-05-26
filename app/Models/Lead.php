<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_NOVO           = 'novo';
    const STATUS_EM_ATENDIMENTO = 'em_atendimento';
    const STATUS_PROPOSTA       = 'proposta';
    const STATUS_PERDA          = 'perda';

    const STATUS_LABELS = [
        'novo'           => 'Novo',
        'em_atendimento' => 'Em Atendimento',
        'proposta'       => 'Proposta',
        'perda'          => 'Perda',
    ];

    const STATUS_CORES = [
        'novo'           => 'bg-blue-100 text-blue-700',
        'em_atendimento' => 'bg-yellow-100 text-yellow-700',
        'proposta'       => 'bg-green-100 text-green-700',
        'perda'          => 'bg-red-100 text-red-700',
    ];

    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'senha',
        'email_confirmado',
        'token_confirmacao',
        'imoveis_interesse',
        'ativo',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'status',
        'user_id',
    ];

    protected $hidden = ['senha', 'token_confirmacao'];

    protected $casts = [
        'imoveis_interesse' => 'array',
        'email_confirmado'  => 'boolean',
        'ativo'             => 'boolean',
    ];

    public function atendimentos()
    {
        return $this->hasMany(Atendimento::class, 'id_lead');
    }

    public function notes()
    {
        return $this->hasMany(LeadNote::class)->latest();
    }

    public function responsavel()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
