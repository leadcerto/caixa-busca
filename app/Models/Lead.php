<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'senha',
        'email_confirmado',
        'token_confirmacao',
        'imoveis_interesse',
        'ativo',
    ];

    protected $hidden = ['senha', 'token_confirmacao'];

    protected $casts = [
        'imoveis_interesse' => 'array',
        'email_confirmado' => 'boolean',
        'ativo' => 'boolean',
    ];

    public function atendimentos()
    {
        return $this->hasMany(Atendimento::class, 'id_lead');
    }
}
