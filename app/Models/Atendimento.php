<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Atendimento extends Model
{
    protected $fillable = [
        'id_lead',
        'id_imovel',
        'id_imobiliaria',
        'id_origem',
        'mensagem',
        'email_enviado',
        'whatsapp_enviado'
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'id_lead');
    }

    public function imovel()
    {
        return $this->belongsTo(Imovel::class, 'id_imovel');
    }

    public function imobiliaria()
    {
        return $this->belongsTo(Imobiliaria::class, 'id_imobiliaria');
    }
}
