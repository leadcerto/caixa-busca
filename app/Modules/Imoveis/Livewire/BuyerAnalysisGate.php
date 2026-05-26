<?php

namespace App\Modules\Imoveis\Livewire;

use App\Models\Lead;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Attributes\Locked;
use Livewire\Component;

/**
 * Gated-content gate for the "Análise do Comprador" section.
 *
 * Responsibility: collect + validate lead data, persist it, then dispatch
 * the browser event `buyerAnalysisUnlocked` so the parent Alpine.js wrapper
 * can persist the unlock state in localStorage and reveal the content.
 *
 * Equivalent to: useLeadAccess hook (state) + LeadCaptureForm component (UI).
 */
class BuyerAnalysisGate extends Component
{
    /** Imovel identifier passed by the parent view — tamper-proof. */
    #[Locked]
    public string $imovelId = '';

    public string $nome     = '';
    public string $whatsapp = '';
    public string $email    = '';

    public function mount(string $imovelId): void
    {
        $this->imovelId = $imovelId;
    }

    protected function rules(): array
    {
        return [
            'nome'     => 'required|string|min:3|max:100',
            'whatsapp' => ['required', 'regex:/^\(\d{2}\)\s\d{4,5}-\d{4}$/'],
            'email'    => 'required|email|max:150',
        ];
    }

    protected function messages(): array
    {
        return [
            'nome.required'     => 'Informe seu nome.',
            'nome.min'          => 'Nome deve ter ao menos 3 caracteres.',
            'whatsapp.required' => 'Informe seu WhatsApp com DDD.',
            'whatsapp.regex'    => 'Formato inválido. Ex: (21) 99999-9999',
            'email.required'    => 'Informe seu e-mail.',
            'email.email'       => 'E-mail inválido.',
        ];
    }

    public function submit(): void
    {
        $this->validate();

        // Upsert lead — same pattern used by LeadApiController::convert()
        $lead = Lead::firstOrCreate(
            ['email' => $this->email],
            [
                'nome'     => $this->nome,
                'telefone' => $this->whatsapp,
                'senha'    => Hash::make(Str::random(16)),
            ]
        );

        if (! $lead->wasRecentlyCreated) {
            $lead->update(['nome' => $this->nome, 'telefone' => $this->whatsapp]);
        }

        // Dispatch browser event → caught by parent Alpine.js wrapper.
        // The wrapper persists 'hasUnlockedBuyerAnalysis' in localStorage
        // and flips isUnlocked = true, hiding this form and revealing content.
        $this->dispatch('buyerAnalysisUnlocked');
    }

    public function render()
    {
        return view('modules.imoveis.livewire.buyer-analysis-gate');
    }
}
