<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappTemplate extends Model
{
    protected $table = 'whatsapp_templates';

    protected $fillable = ['nome', 'mensagem', 'ativo'];

    protected $casts = ['ativo' => 'boolean'];

    public static function ativo(): ?static
    {
        return static::where('ativo', true)->first();
    }

    public function renderizar(array $vars): string
    {
        $substituicoes = [
            '{nome}'       => $vars['nome']       ?? '',
            '{tipo_imovel}' => $vars['tipo_imovel'] ?? '',
            '{codigo}'     => $vars['codigo']     ?? '',
            '{localidade}' => $vars['localidade'] ?? '',
            '{municipio}'  => $vars['municipio']  ?? '',
            '{uf}'         => $vars['uf']         ?? '',
        ];

        return str_replace(
            array_keys($substituicoes),
            array_values($substituicoes),
            $this->mensagem
        );
    }

    public static function renderizarAtivo(array $vars, string $fallback): string
    {
        $template = static::ativo();

        return $template ? $template->renderizar($vars) : $fallback;
    }
}
