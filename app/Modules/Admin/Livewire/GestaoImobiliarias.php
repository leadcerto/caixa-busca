<?php

namespace App\Modules\Admin\Livewire;

use App\Models\Estado;
use App\Models\Imobiliaria;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class GestaoImobiliarias extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $idImobiliaria;
    public $nome = '';
    public $cnpj = '';
    public $email = '';
    public $whatsapp = '';
    public $creci = '';
    public $horario = 'Segunda a Sexta-feira: 10:00 às 16:00';
    public $senha = '';
    public $ativo = true;
    public $imagem;         // upload do botão WhatsApp (PNG)
    public $imagemExistente = '';
    public $logo;           // upload da logo quadrada
    public $logoExistente = '';

    public array $selectedEstados = [];

    public $modalAberto = false;
    public $isEditMode = false;
    public $search = '';

    protected $queryString = ['search' => ['except' => '']];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $imobiliarias = Imobiliaria::with('estados')
            ->where(function($query) {
                $query->where('nome', 'like', "%{$this->search}%")
                    ->orWhere('cnpj', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%")
                    ->orWhere('whatsapp', 'like', "%{$this->search}%")
                    ->orWhere('creci', 'like', "%{$this->search}%");
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        $todosEstados = Estado::orderBy('uf')->get();

        return view('modules.admin.livewire.gestao-imobiliarias', [
            'imobiliarias' => $imobiliarias,
            'todosEstados' => $todosEstados,
        ])->layout('layouts.admin', ['title' => 'Gestão de Imobiliárias']);
    }

    public function abrirModalCriar(): void
    {
        $this->resetCampos();
        $this->isEditMode = false;
        $this->modalAberto = true;
    }

    public function abrirModalEditar(int $id): void
    {
        $this->resetCampos();
        $imobiliaria = Imobiliaria::with('estados')->findOrFail($id);

        $this->idImobiliaria   = $imobiliaria->id;
        $this->nome            = $imobiliaria->nome;
        $this->cnpj            = $imobiliaria->cnpj;
        $this->email           = $imobiliaria->email;
        $this->whatsapp        = $imobiliaria->whatsapp;
        $this->creci           = $imobiliaria->creci;
        $this->horario         = $imobiliaria->horario_atendimento ?? 'Segunda a Sexta-feira: 10:00 às 16:00';
        $this->ativo           = (bool) $imobiliaria->ativo;
        $this->imagemExistente = $imobiliaria->imagem_botao;
        $this->logoExistente   = $imobiliaria->logo_url;
        $this->selectedEstados = $imobiliaria->estados->pluck('id')->toArray();

        $this->isEditMode = true;
        $this->modalAberto = true;
    }

    public function fecharModal(): void
    {
        $this->modalAberto = false;
        $this->resetCampos();
    }

    public function resetCampos(): void
    {
        $this->idImobiliaria  = null;
        $this->nome           = '';
        $this->cnpj           = '';
        $this->email          = '';
        $this->whatsapp       = '';
        $this->creci          = '';
        $this->horario        = 'Segunda a Sexta-feira: 10:00 às 16:00';
        $this->senha          = '';
        $this->ativo          = true;
        $this->imagem         = null;
        $this->imagemExistente = '';
        $this->logo           = null;
        $this->logoExistente  = '';
        $this->selectedEstados = [];
        $this->resetValidation();
    }

    public function salvar(): void
    {
        $regras = [
            'nome'            => 'required|string|max:150',
            'cnpj'            => 'nullable|string|max:20',
            'email'           => 'required|email|max:150|unique:imobiliarias,email,' . ($this->idImobiliaria ?? 'NULL'),
            'whatsapp'        => 'required|string|max:20',
            'creci'           => 'nullable|string|max:30',
            'horario'         => 'nullable|string|max:255',
            'senha'           => $this->isEditMode ? 'nullable|string|min:6' : 'required|string|min:6',
            'selectedEstados' => 'array',
            'imagem'          => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $extension = strtolower($value->getClientOriginalExtension());
                        if ($extension !== 'png') {
                            $fail('A imagem do botão deve ser exclusivamente no formato PNG com fundo transparente.');
                        }
                        if ($value->getSize() > 10 * 1024 * 1024) {
                            $fail('O tamanho do arquivo não pode ser superior a 10MB.');
                        }
                    }
                }
            ],
            'logo' => 'nullable|image|max:5120',
        ];

        $this->validate($regras);

        // Upload do botão WhatsApp (PNG)
        $caminhoImagem = $this->imagemExistente;
        if ($this->imagem) {
            if ($this->imagemExistente && Storage::disk('public')->exists($this->imagemExistente)) {
                Storage::disk('public')->delete($this->imagemExistente);
            }
            $caminhoImagem = $this->imagem->store('imobiliarias', 'public');
        }

        // Upload da logo quadrada
        $caminhoLogo = $this->logoExistente;
        if ($this->logo) {
            if ($this->logoExistente && Storage::disk('public')->exists($this->logoExistente)) {
                Storage::disk('public')->delete($this->logoExistente);
            }
            $caminhoLogo = $this->logo->store('imobiliarias/logos', 'public');
        }

        $dados = [
            'nome'                => $this->nome,
            'cnpj'                => $this->cnpj,
            'email'               => $this->email,
            'whatsapp'            => $this->whatsapp,
            'creci'               => $this->creci,
            'horario_atendimento' => $this->horario ?: 'Segunda a Sexta-feira: 10:00 às 16:00',
            'ativo'               => $this->ativo,
            'imagem_botao'        => $caminhoImagem ?: null,
            'logo_url'            => $caminhoLogo ?: null,
        ];

        // Se digitou uma nova senha (ou está cadastrando)
        if ($this->senha) {
            $dados['senha'] = Hash::make($this->senha);
        }

        if ($this->isEditMode) {
            $imobiliaria = Imobiliaria::findOrFail($this->idImobiliaria);
            $imobiliaria->update($dados);
            $this->dispatch('alert', ['type' => 'success', 'message' => 'Parceiro atualizado com sucesso!']);
        } else {
            $imobiliaria = Imobiliaria::create($dados);
            $this->dispatch('alert', ['type' => 'success', 'message' => 'Parceiro cadastrado com sucesso!']);
        }

        // Atualizar vínculo com os estados
        $imobiliaria->estados()->sync($this->selectedEstados);

        $this->fecharModal();
        $this->resetPage();
    }

    public function toggleAtivo(int $id): void
    {
        $imobiliaria = Imobiliaria::findOrFail($id);
        $imobiliaria->update(['ativo' => !$imobiliaria->ativo]);
        $this->dispatch('alert', ['type' => 'success', 'message' => 'Status alterado com sucesso!']);
    }

    public function deletar(int $id): void
    {
        $imobiliaria = Imobiliaria::findOrFail($id);
        
        // Deleta imagem se houver
        if ($imobiliaria->imagem_botao && Storage::disk('public')->exists($imobiliaria->imagem_botao)) {
            Storage::disk('public')->delete($imobiliaria->imagem_botao);
        }

        // Desvincula estados e deleta
        $imobiliaria->estados()->detach();
        $imobiliaria->delete();

        $this->dispatch('alert', ['type' => 'success', 'message' => 'Parceiro removido com sucesso!']);
        $this->resetPage();
    }
}
