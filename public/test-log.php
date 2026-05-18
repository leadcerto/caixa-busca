<?php

// Habilitar erros na tela para o script de diagnóstico
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>🔍 Diagnóstico de Produção - Venda Imóveis da Caixa</h1>";

// 1. Verificar caminhos
$corePath = __DIR__ . '/../../../laravel-app';
$logFile = $corePath . '/storage/logs/laravel.log';

echo "<h3>📁 Caminhos do Sistema</h3>";
echo "Pasta pública: " . __DIR__ . "<br>";
echo "Pasta do Laravel Core: " . realpath($corePath) . " (" . (is_dir($corePath) ? "✅ Existe" : "❌ Não existe") . ")<br>";
echo "Arquivo de Log: " . $logFile . " (" . (file_exists($logFile) ? "✅ Existe" : "❌ Não existe") . ")<br>";

// 2. Tentar ler os últimos erros do laravel.log
if (file_exists($logFile)) {
    echo "<h3>📋 Últimas 100 linhas do laravel.log</h3>";
    $lines = file($logFile);
    $lastLines = array_slice($lines, -100);
    echo "<pre style='background: #f4f4f4; padding: 10px; border: 1px solid #ccc; max-height: 400px; overflow: auto;'>";
    foreach ($lastLines as $line) {
        echo htmlspecialchars($line);
    }
    echo "</pre>";
} else {
    echo "<p>❌ O arquivo de log do Laravel não existe ou não pôde ser lido.</p>";
}

// 3. Informações do PHP
echo "<h3>⚙️ Configurações PHP</h3>";
echo "Versão PHP: " . phpversion() . "<br>";
echo "PDO MySQL: " . (extension_loaded('pdo_mysql') ? "✅ Habilitado" : "❌ Desabilitado") . "<br>";

// 4. Executar Artisan Commands via código
echo "<h3>⚡ Ações Artisan</h3>";
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    try {
        // Carrega o bootstrap do Laravel
        $appFile = $corePath . '/bootstrap/app.php';
        if (file_exists($appFile)) {
            $app = require_once $appFile;
            $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
            
            if ($action === 'migrate') {
                echo "<h4>Executando php artisan migrate --force...</h4>";
                $status = $kernel->call('migrate', ['--force' => true]);
                echo "<pre>Saída:<br>" . htmlspecialchars($kernel->output()) . "</pre>";
                echo "Status de saída: " . $status . "<br>";
            } elseif ($action === 'clear') {
                echo "<h4>Executando php artisan optimize:clear...</h4>";
                $status = $kernel->call('optimize:clear');
                echo "<pre>Saída:<br>" . htmlspecialchars($kernel->output()) . "</pre>";
            } elseif ($action === 'migrate-fresh') {
                echo "<h4>Executando php artisan migrate:fresh --seed --force...</h4>";
                $status = $kernel->call('migrate:fresh', ['--seed' => true, '--force' => true]);
                echo "<pre>Saída:<br>" . htmlspecialchars($kernel->output()) . "</pre>";
            } else {
                echo "❌ Ação desconhecida.";
            }
        } else {
            echo "❌ Não foi possível carregar o bootstrap do Laravel para executar comandos.";
        }
    } catch (\Throwable $e) {
        echo "❌ Erro ao executar Artisan: <br><pre>" . htmlspecialchars($e->getMessage()) . "\n" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    }
} else {
    echo "<p>Disponíveis para executar (adicione ?action=... na URL):</p>";
    echo "<ul>";
    echo "<li><a href='?action=clear'>Limpar caches (optimize:clear)</a></li>";
    echo "<li><a href='?action=migrate'>Rodar migrações novas (migrate)</a></li>";
    echo "<li><a href='?action=migrate-fresh'>Recriar e popular banco (migrate:fresh --seed)</a></li>";
    echo "</ul>";
}
