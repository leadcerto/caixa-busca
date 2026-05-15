<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubBairro extends Model
{
    protected $fillable = ['nome', 'id_bairro', 'ativo'];

    public function bairro()
    {
        return $this->belongsTo(Bairro::class, 'id_bairro');
    }

    public function imoveis()
    {
        return $this->hasMany(Imovel::class, 'id_sub_bairro');
    }
}
