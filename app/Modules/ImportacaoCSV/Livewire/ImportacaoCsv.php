<?php

namespace App\Modules\ImportacaoCSV\Livewire;

use App\Modules\ImportacaoCSV\Jobs\ProcessCaixaCsvJob;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;

/**
 * Componente Livewire para Interface de Importao de CSV da Caixa.
 * Focado no "Epicenter Design" e na experincia do usurio administrativa.
 */
class ImportacaoCsv extends Component
{
    use WithFileUploads;

    /**
     * O arquivo CSV carregado temporariamente.
     */
    public $csvFile;

    /**
     * Mensagem de feedback para a UI.
     */
    public $message = '';

    /**
     * Tipo da mensagem (success ou error).
     */
    public $messageType = 'success';

    /**
     * Regras de validao estritas.
     */
    protected $rules = [
        'csvFile' => 'required|mimes:csv,txt|max:10240', // Limite de 10MB
    ];

    /**
     * Validao customizada para mensagens em PT-BR.
     */
    protected $messages = [
        'csvFile.required' => 'Por favor, selecione um arquivo.',
        'csvFile.mimes' => 'O arquivo deve ser obrigatoriamente um CSV.',
        'csvFile.max' => 'O arquivo  muito grande. O limite  de 10MB.',
    ];

    /**
     * Processa o upload e dispara o motor de background.
     */
    public function save()
    {
        $this->validate();

        try {
            // Salva o arquivo em storage/app/imports para processamento pelo Job
            // O uso de storeAs garante que saibamos o caminho exato
            $fileName = 'caixa_import_' . now()->format('Ymd_His') . '.csv';
            $path = $this->csvFile->storeAs('imports', $fileName);
            $absolutePath = storage_path('app/' . $path);

            // Dispara o Job para o Worker processar sem travar a interface
            ProcessCaixaCsvJob::dispatch($absolutePath);

            // Atualiza o estado da interface (Caminho Feliz)
            $this->message = "O arquivo foi enviado para processamento em background. Voc pode continuar navegando com segurana.";
            $this->messageType = 'success';
            
            // Limpa o input de arquivo
            $this->reset('csvFile');

            Log::info("UI_IMPORTACAO: Upload realizado com sucesso. Caminho: {$absolutePath}");

        } catch (\Exception $e) {
            $this->message = "Erro ao iniciar importao: " . $e->getMessage();
            $this->messageType = 'error';
            Log::error("UI_IMPORTACAO: Falha no upload: " . $e->getMessage());
        }
    }

    /**
     * Renderiza a view do componente.
     */
    public function render()
    {
        return view('modules.importacao-csv.livewire.importacao-csv');
    }
}
