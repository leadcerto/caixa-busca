<?php

namespace App\Providers;

use App\Models\Atendimento;
use App\Modules\Imoveis\Livewire\BuyerAnalysisGate;
use App\Observers\AtendimentoObserver;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        Atendimento::observe(AtendimentoObserver::class);

        Livewire::component('buyer-analysis-gate', BuyerAnalysisGate::class);
    }
}
