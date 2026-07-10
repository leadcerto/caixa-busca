<?php

namespace App\Services;

use App\Models\Bairro;
use App\Models\CampaignPageView;
use Illuminate\Http\Request;

class CampaignTrackingService
{
    /**
     * Registra um PageView na página de bairro.
     * Captura UTMs, IP anonimizado, device e referrer.
     * Falha silenciosa — nunca quebra a página do usuário.
     */
    public function recordPageView(Bairro $bairro, Request $request): void
    {
        try {
            CampaignPageView::create([
                'bairro_id'    => $bairro->id,
                'session_id'   => $this->resolveSessionId($request),
                'ip_hash'      => $this->anonymizeIp($request->ip()),
                'utm_source'   => $this->clean($request->query('utm_source')),
                'utm_medium'   => $this->clean($request->query('utm_medium')),
                'utm_campaign' => $this->clean($request->query('utm_campaign')),
                'utm_content'  => $this->clean($request->query('utm_content')),
                'utm_term'     => $this->clean($request->query('utm_term')),
                'referrer'     => $this->truncate($request->headers->get('referer'), 500),
                'user_agent'   => $this->truncate($request->userAgent(), 500),
                'device_type'  => $this->detectDevice($request->userAgent() ?? ''),
            ]);
        } catch (\Throwable) {
            // Falha silenciosa — tracking nunca interrompe a experiência do usuário
        }
    }

    /**
     * Gera um hash de sessão anônimo baseado no IP + user-agent.
     * Serve para identificar a mesma sessão sem armazenar dados pessoais.
     */
    private function resolveSessionId(Request $request): string
    {
        $raw = $request->ip() . '|' . $request->userAgent() . '|' . today()->toDateString();
        return hash('sha256', $raw . config('app.key'));
    }

    /**
     * Anonimiza o IP via SHA-256 com salt da chave da aplicação.
     * Em conformidade com a LGPD — não é reversível.
     */
    private function anonymizeIp(?string $ip): ?string
    {
        if (!$ip) return null;
        return hash('sha256', $ip . config('app.key'));
    }

    /**
     * Detecta o tipo de dispositivo pelo User-Agent.
     */
    private function detectDevice(string $userAgent): string
    {
        $ua = strtolower($userAgent);

        if (str_contains($ua, 'tablet') || str_contains($ua, 'ipad')) {
            return 'tablet';
        }

        if (
            str_contains($ua, 'mobile')   ||
            str_contains($ua, 'android')  ||
            str_contains($ua, 'iphone')   ||
            str_contains($ua, 'windows phone')
        ) {
            return 'mobile';
        }

        return 'desktop';
    }

    /**
     * Limpa e sanitiza valores de string dos parâmetros UTM.
     */
    private function clean(?string $value): ?string
    {
        if ($value === null) return null;
        $clean = strip_tags(trim($value));
        return $clean !== '' ? mb_substr($clean, 0, 200) : null;
    }

    /**
     * Trunca string no limite informado.
     */
    private function truncate(?string $value, int $limit): ?string
    {
        if ($value === null) return null;
        return mb_substr($value, 0, $limit);
    }
}
