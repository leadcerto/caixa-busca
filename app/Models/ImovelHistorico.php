<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImovelHistorico extends Model
{
    use HasFactory;
    protected $table = 'imoveis_historico';

    protected $fillable = [
        'id_imovel',
        'id_modalidade',
        'data_referencia',
        'valor_avaliacao',
        'valor_venda',
        'desconto_percentual',
        'desconto_valor',
        'aceita_financ_sbpe',
        'aceita_financ_mcmv'
    ];

    protected $casts = [
        'data_referencia' => 'date',
        'valor_avaliacao' => 'decimal:2',
        'valor_venda' => 'decimal:2',
        'desconto_percentual' => 'decimal:2',
        'desconto_valor' => 'decimal:2',
    ];

    const UPDATED_AT = null;

    public function imovel()
    {
        return $this->belongsTo(Imovel::class, 'id_imovel');
    }

    public function modalidade()
    {
        return $this->belongsTo(ModalidadeVenda::class, 'id_modalidade');
    }
}
