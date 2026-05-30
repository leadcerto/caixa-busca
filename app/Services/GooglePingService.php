<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GooglePingService
{
    private const SITEMAP_URL = 'https://venda.imoveisdacaixa.com.br/sitemap.xml';

    public static function pingSitemap(): bool
    {
        try {
            $response = Http::timeout(10)->get('https://www.google.com/ping', [
                'sitemap' => self::SITEMAP_URL,
            ]);

            Log::info('GooglePing: sitemap enviado ao Google.', ['status' => $response->status()]);
            return $response->successful() || $response->status() === 301;
        } catch (\Throwable $e) {
            Log::warning('GooglePing: falha ao pingar Google.', ['erro' => $e->getMessage()]);
            return false;
        }
    }
}
