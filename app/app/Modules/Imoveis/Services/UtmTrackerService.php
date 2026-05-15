<?php

namespace App\Modules\Imoveis\Services;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Request;

/**
 * Servio responsvel por capturar e persistir UTMs de marketing.
 * Segue a regra 8 dos Requisitos: Inteligncia de Rastreamento.
 */
class UtmTrackerService
{
    /**
     * Chaves de UTM que o sistema deve monitorar.
     */
    protected $utmKeys = [
        'utm_source', 
        'utm_medium', 
        'utm_campaign', 
        'utm_term', 
        'utm_content'
    ];

    /**
     * Captura as UTMs presentes na URL e as persiste em cookies.
     * Isso garante que se o usurio entrar por um anncio e navegar, a origem no seja perdida.
     */
    public function captureFromRequest()
    {
        foreach ($this->utmKeys as $key) {
            if ($value = Request::query($key)) {
                // Persiste por 30 dias (Regra de persistncia persistente)
                Cookie::queue($key, $value, 60 * 24 * 30);
            }
        }
    }

    /**
     * Recupera as UTMs rastreadas para envio no Webhook.
     * Prioriza o cookie, mas aceita a query string se disponvel no momento.
     */
    public function getTrackedUtms(): array
    {
        $data = [];
        foreach ($this->utmKeys as $key) {
            $data[$key] = Request::cookie($key) ?? Request::query($key);
        }
        
        // Retorna apenas as chaves que possuem valor
        return array_filter($data);
    }
}
