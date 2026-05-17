<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImobiliariaEstado extends Model
{
    const UPDATED_AT = null;

    protected $table = 'imobiliarias_estados';

    protected $fillable = ['id_imobiliaria', 'id_estado'];

    public function imobiliaria()
    {
        return $this->belongsTo(Imobiliaria::class, 'id_imobiliaria');
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'id_estado');
    }
}
