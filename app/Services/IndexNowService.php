<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IndexNowService
{
    private const HOST     = 'venda.imoveisdacaixa.com.br';
    private const KEY      = 'e8a3f21b4c5d6789012e3f4a5b6c7d8e';
    private const ENDPOINT = 'https://api.indexnow.org/indexnow';
    private const CHUNK    = 10000;

    public static function submitUrls(array $urls): void
    {
        if (empty($urls)) {
            return;
        }

        foreach (array_chunk($urls, self::CHUNK) as $chunk) {
            try {
                $response = Http::timeout(15)
                    ->withHeaders(['Content-Type' => 'application/json; charset=utf-8'])
                    ->post(self::ENDPOINT, [
                        'host'        => self::HOST,
                        'key'         => self::KEY,
                        'keyLocation' => 'https://' . self::HOST . '/' . self::KEY . '.txt',
                        'urlList'     => $chunk,
                    ]);

                Log::info('IndexNow: ' . count($chunk) . ' URLs submetidas.', [
                    'status' => $response->status(),
                ]);
            } catch (\Throwable $e) {
                Log::warning('IndexNow: falha ao enviar URLs.', ['erro' => $e->getMessage()]);
            }
        }
    }
}
