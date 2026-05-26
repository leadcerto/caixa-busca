<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadNote extends Model
{
    protected $table = 'lead_notes';

    protected $fillable = ['lead_id', 'user_id', 'conteudo', 'tipo'];

    const TIPOS = [
        'anotacao'  => 'Anotação',
        'ligacao'   => 'Ligação',
        'email'     => 'E-mail',
        'whatsapp'  => 'WhatsApp',
        'visita'    => 'Visita',
    ];

    const TIPO_ICONS = [
        'anotacao'  => '📝',
        'ligacao'   => '📞',
        'email'     => '✉️',
        'whatsapp'  => '💬',
        'visita'    => '🏠',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function autor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
