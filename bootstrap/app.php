<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'imobiliaria' => \App\Http\Middleware\ImobiliariaAuthMiddleware::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            '/verificar-erro-sistema',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

// Define o caminho público correto em produção para resolver o Vite e carregamento de assets
if (file_exists('/home/u541302702')) {
    $app->usePublicPath('/home/u541302702/domains/imoveisdacaixa.com.br/public_html/venda/public');
}

return $app;
