<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Imovel extends Model
{
    protected $table = 'imoveis';

    protected $fillable = [
        'numero_original',
        'id_imobiliaria',
        'id_tipo_imovel',
        'id_estado',
        'id_municipio',
        'id_bairro',
        'id_sub_bairro',
        'id_grupo',
        'id_etapa',
        'endereco',
        'cep',
        'descricao_original',
        'area_total',
        'area_privativa',
        'area_terreno',
        'quartos',
        'banheiros',
        'salas',
        'garagens',
        'varanda',
        'area_servico',
        'cozinha',
        'piscina',
        'churrasqueira',
        'terraco',
        'foto_fachada_url',
        'imagem_destaque_url',
        'link_edital',
        'aceita_fgts',
        'aceita_financ_sbpe',
        'aceita_financ_mcmv',
        'status',
        'slug',
        'meta_title',
        'meta_description',
        'updated_at'
    ];

    protected $casts = [
        'area_total' => 'decimal:2',
        'area_privativa' => 'decimal:2',
        'area_terreno' => 'decimal:2',
        'updated_at' => 'datetime',
    ];

    public function historico()
    {
        return $this->hasMany(ImovelHistorico::class, 'id_imovel');
    }

    public function bairro()
    {
        return $this->belongsTo(Bairro::class, 'id_bairro');
    }

    public function subBairro()
    {
        return $this->belongsTo(SubBairro::class, 'id_sub_bairro');
    }
}
