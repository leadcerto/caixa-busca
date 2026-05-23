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
        'csvFile' => 'required|file|extensions:csv,txt|max:51200', // Limite de 50MB
    ];

    /**
     * Validao customizada para mensagens em PT-BR.
     */
    protected $messages = [
        'csvFile.required' => 'Por favor, selecione um arquivo.',
        'csvFile.file' => 'O arquivo enviado é inválido.',
        'csvFile.extensions' => 'O arquivo deve ser obrigatoriamente um CSV ou TXT.',
        'csvFile.max' => 'O arquivo é muito grande. O limite é de 50MB.',
        'csvFile.uploaded' => 'O upload falhou. O arquivo de 3.81MB é maior do que o limite atual de 2MB do seu PHP local (Herd). Siga os passos de configuração abaixo para liberar uploads maiores.',
    ];

    /**
     * Processa o upload e dispara o motor de background.
     */
    public function save()
    {
        $this->validate();

        try {
            // Salva o arquivo em storage/app/imports para processamento pelo Job
            // Especificar explicitamente o disco 'local' garante consistência independente do .env
            $fileName = 'caixa_import_' . now()->format('Ymd_His') . '.csv';
            $this->csvFile->storeAs('imports', $fileName, 'local');
            $absolutePath = storage_path('app/imports/' . $fileName);

            // Dispara o Job para o Worker processar sem travar a interface
            ProcessCaixaCsvJob::dispatch($absolutePath);

            // Atualiza o estado da interface (Caminho Feliz)
            $this->message = "O arquivo foi enviado para processamento em background. Você pode continuar navegando com segurança.";
            $this->messageType = 'success';
            
            // Limpa o input de arquivo
            $this->reset('csvFile');

            Log::info("UI_IMPORTACAO: Upload realizado com sucesso. Caminho: {$absolutePath}");

        } catch (\Exception $e) {
            $this->message = "Erro ao iniciar importação: " . $e->getMessage();
            $this->messageType = 'error';
            Log::error("UI_IMPORTACAO: Falha no upload: " . $e->getMessage());
        }
    }

    /**
     * Renderiza a view do componente.
     */
    public function render()
    {
        return view('modules.importacao-csv.livewire.importacao-csv')
            ->layout('layouts.admin', ['title' => 'Importar CSV']);
    }
}
