<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImovelEtapa extends Model
{
    public $timestamps = false;

    protected $table = 'imoveis_etapas';

    protected $fillable = ['nome', 'descricao', 'ordem', 'ativo'];

    protected $casts = ['ativo' => 'boolean'];

    public function imoveis()
    {
        return $this->hasMany(Imovel::class, 'id_etapa');
    }
}
