<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoImovel extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'tipos_imovel';

    protected $fillable = ['nome', 'ativo'];

    protected $casts = ['ativo' => 'boolean'];

    public function imoveis()
    {
        return $this->hasMany(Imovel::class, 'id_tipo_imovel');
    }
}
