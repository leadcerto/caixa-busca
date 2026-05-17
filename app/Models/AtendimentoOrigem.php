<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AtendimentoOrigem extends Model
{
    public $timestamps = false;

    protected $table = 'atendimentos_origem';

    protected $fillable = ['nome', 'ativo'];

    protected $casts = ['ativo' => 'boolean'];

    public function atendimentos()
    {
        return $this->hasMany(Atendimento::class, 'id_origem');
    }
}
