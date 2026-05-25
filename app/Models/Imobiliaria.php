<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Imobiliaria extends Authenticatable
{
    use HasFactory;
    protected $fillable = ['nome', 'cnpj', 'email', 'senha', 'whatsapp', 'creci', 'imagem_botao', 'logo_url', 'horario_atendimento', 'ativo'];

    protected $hidden = ['senha'];

    protected $casts = ['ativo' => 'boolean'];

    public function getAuthPasswordName(): string
    {
        return 'senha';
    }

    public function atendimentos()
    {
        return $this->hasMany(Atendimento::class, 'id_imobiliaria');
    }

    public function estados()
    {
        return $this->belongsToMany(Estado::class, 'imobiliarias_estados', 'id_imobiliaria', 'id_estado');
    }
}
