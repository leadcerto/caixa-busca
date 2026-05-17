<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImovelGrupo extends Model
{
    protected $table = 'imoveis_grupos';

    protected $fillable = [
        'nome',
        'valor_minimo',
        'valor_maximo',
        'percentual_1',
        'percentual_2',
        'valor_fixo_1',
        'valor_fixo_2',
        'ativo',
    ];

    protected $casts = [
        'valor_minimo' => 'decimal:2',
        'valor_maximo' => 'decimal:2',
        'percentual_1' => 'decimal:2',
        'percentual_2' => 'decimal:2',
        'valor_fixo_1' => 'decimal:2',
        'valor_fixo_2' => 'decimal:2',
        'ativo' => 'boolean',
    ];

    public function imoveis()
    {
        return $this->hasMany(Imovel::class, 'id_grupo');
    }
}
