<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    protected $table = 'municipios';

    protected $fillable = [
        'id_estado',
        'nome',
        'conteudo_ia',
        'ia_status',
        'ia_gerado_em',
    ];

    protected $casts = [
        'conteudo_ia' => 'array',
        'ia_gerado_em' => 'datetime',
    ];

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'id_estado');
    }

    public function bairros()
    {
        return $this->hasMany(Bairro::class, 'id_municipio');
    }

    public function imoveis()
    {
        return $this->hasMany(Imovel::class, 'id_municipio');
    }
}
