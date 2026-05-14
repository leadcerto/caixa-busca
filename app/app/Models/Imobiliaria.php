<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Imobiliaria extends Model
{
    protected $fillable = ['nome', 'email', 'senha', 'whatsapp', 'creci', 'ativo'];

    protected $hidden = ['senha'];

    public function atendimentos()
    {
        return $this->hasMany(Atendimento::class, 'id_imobiliaria');
    }

    public function estados()
    {
        return $this->belongsToMany(Estado::class, 'imobiliarias_estados', 'id_imobiliaria', 'id_estado');
    }
}
