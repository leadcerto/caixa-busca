<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['nome', 'uf'];

    public function municipios()
    {
        return $this->hasMany(Municipio::class, 'id_estado');
    }

    public function imoveis()
    {
        return $this->hasMany(Imovel::class, 'id_estado');
    }

    public function imobiliariaEstado()
    {
        return $this->hasOne(ImobiliariaEstado::class, 'id_estado');
    }
}
