<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Atendimento extends Model
{
    use HasFactory, SoftDeletes;

    const UPDATED_AT = null;

    protected $fillable = [
        'id_lead',
        'id_imovel',
        'id_imobiliaria',
        'id_origem',
        'mensagem',
        'email_enviado',
        'whatsapp_enviado',
        'status_parceiro',
        'anotacao',
    ];

    protected $casts = [
        'email_enviado'    => 'boolean',
        'whatsapp_enviado' => 'boolean',
    ];

    public const STATUS_LABELS = [
        'pendente'       => 'Pendente',
        'contatado'      => 'Contatado',
        'negociando'     => 'Negociando',
        'sem_interesse'  => 'Sem interesse',
        'fechado'        => 'Fechado',
    ];

    public const STATUS_CORES = [
        'pendente'       => 'bg-yellow-100 text-yellow-700',
        'contatado'      => 'bg-blue-100 text-blue-700',
        'negociando'     => 'bg-purple-100 text-purple-700',
        'sem_interesse'  => 'bg-red-100 text-red-600',
        'fechado'        => 'bg-green-100 text-green-700',
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

    public function origem()
    {
        return $this->belongsTo(AtendimentoOrigem::class, 'id_origem');
    }
}
