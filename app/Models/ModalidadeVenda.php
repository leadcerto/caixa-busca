<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModalidadeVenda extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'modalidades_venda';

    protected $fillable = ['nome', 'ativo'];

    protected $casts = ['ativo' => 'boolean'];

    public function historicos()
    {
        return $this->hasMany(ImovelHistorico::class, 'id_modalidade');
    }
}
