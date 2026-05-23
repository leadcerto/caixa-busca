<?php

namespace App\Modules\Admin\Livewire;

use App\Models\WhatsappTemplate;
use Livewire\Component;

class WhatsappTemplates extends Component
{
    public string $modo = 'lista'; // lista | form

    public ?int   $templateId = null;
    public string $nome       = '';
    public string $mensagem   = '';
    public bool   $ativo      = false;

    public string $previewNome      = 'João Silva';
    public string $previewTipo      = 'Apartamento';
    public string $previewCodigo    = 'SP123456';
    public string $previewLocalidade = 'Perdizes, São Paulo, SP';
    public string $previewMunicipio = 'São Paulo';
    public string $previewUf        = 'SP';

    // -------------------------------------------------------------------------

    public function novo(): void
    {
        $this->resetForm();
        $this->modo = 'form';
    }

    public function editar(int $id): void
    {
        $template = WhatsappTemplate::findOrFail($id);

        $this->templateId = $template->id;
        $this->nome       = $template->nome;
        $this->mensagem   = $template->mensagem;
        $this->ativo      = $template->ativo;
        $this->modo       = 'form';
    }

    public function salvar(): void
    {
        $this->validate([
            'nome'     => 'required|string|max:100',
            'mensagem' => 'required|string|max:1000',
        ]);

        // Se marcando como ativo, desativa todos os outros
        if ($this->ativo) {
            WhatsappTemplate::where('id', '!=', $this->templateId ?? 0)
                ->update(['ativo' => false]);
        }

        WhatsappTemplate::updateOrCreate(
            ['id' => $this->templateId],
            [
                'nome'     => $this->nome,
                'mensagem' => $this->mensagem,
                'ativo'    => $this->ativo,
            ]
        );

        $this->resetForm();
        $this->modo = 'lista';
    }

    public function ativar(int $id): void
    {
        WhatsappTemplate::query()->update(['ativo' => false]);
        WhatsappTemplate::where('id', $id)->update(['ativo' => true]);
    }

    public function excluir(int $id): void
    {
        WhatsappTemplate::findOrFail($id)->delete();
    }

    public function cancelar(): void
    {
        $this->resetForm();
        $this->modo = 'lista';
    }

    public function getPreviewProperty(): string
    {
        if (!$this->mensagem) {
            return '';
        }

        return str_replace(
            ['{nome}', '{tipo_imovel}', '{codigo}', '{localidade}', '{municipio}', '{uf}'],
            [$this->previewNome, $this->previewTipo, $this->previewCodigo, $this->previewLocalidade, $this->previewMunicipio, $this->previewUf],
            $this->mensagem
        );
    }

    private function resetForm(): void
    {
        $this->templateId = null;
        $this->nome       = '';
        $this->mensagem   = '';
        $this->ativo      = false;
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('modules.admin.livewire.whatsapp-templates', [
            'templates' => WhatsappTemplate::orderByDesc('ativo')->orderBy('nome')->get(),
        ])->layout('layouts.admin', ['title' => 'Templates WhatsApp']);
    }
}
