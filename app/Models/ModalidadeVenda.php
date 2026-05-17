<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModalidadeVenda extends Model
{
    public $timestamps = false;

    protected $table = 'modalidades_venda';

    protected $fillable = ['nome', 'ativo'];

    protected $casts = ['ativo' => 'boolean'];

    public function historicos()
    {
        return $this->hasMany(ImovelHistorico::class, 'id_modalidade');
    }
}
