<?php
/**
 * Database Connection Test Script
 * Use this to verify production database connectivity.
 * After testing, DELETE THIS FILE.
 */

// Load environment manually since we are outside the Laravel bootstrap for a quick test
function getEnvValue($key, $default = null) {
    $envFile = __DIR__ . '/../.env';
    if (file_exists($envFile)) {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue;
            list($name, $value) = explode('=', $line, 2);
            if (trim($name) == $key) {
                return trim($value, '"\' ');
            }
        }
    }
    return $default;
}

$host = getEnvValue('DB_HOST', '127.0.0.1');
$port = getEnvValue('DB_PORT', '3306');
$db   = getEnvValue('DB_DATABASE');
$user = getEnvValue('DB_USERNAME');
$pass = getEnvValue('DB_PASSWORD');

header('Content-Type: application/json');

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERR_MODE            => PDO::ERR_MODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    
    $start = microtime(true);
    $pdo = new PDO($dsn, $user, $pass, $options);
    $end = microtime(true);
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Conexão com o banco de dados estabelecida com sucesso!',
        'details' => [
            'host' => $host,
            'database' => $db,
            'user' => $user,
            'latency' => round(($end - $start) * 1000, 2) . 'ms'
        ]
    ]);
} catch (\PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Falha na conexão: ' . $e->getMessage(),
        'config_check' => [
            'host' => $host,
            'database' => $db,
            'user' => $user,
            'port' => $port
        ]
    ], JSON_PRETTY_PRINT);
}
