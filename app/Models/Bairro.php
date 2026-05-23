<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bairro extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_municipio',
        'nome',
        'latitude',
        'longitude',
        'conteudo_ia',
        'ia_status',
        'ia_gerado_em',
    ];

    protected $casts = [
        'conteudo_ia' => 'array',
        'ia_gerado_em' => 'datetime',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'id_municipio');
    }

    public function subBairros()
    {
        return $this->hasMany(SubBairro::class, 'id_bairro');
    }

    public function imoveis()
    {
        return $this->hasMany(Imovel::class, 'id_bairro');
    }
}
