<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
$maintenancePaths = [
    __DIR__.'/../storage/framework/maintenance.php',
    __DIR__.'/../../../laravel-app/storage/framework/maintenance.php',
    __DIR__.'/../../../../../laravel-app/storage/framework/maintenance.php',
];
foreach ($maintenancePaths as $mPath) {
    if (file_exists($mPath)) {
        require $mPath;
        break;
    }
}

// Register the Composer autoloader...
$autoloadPaths = [
    __DIR__.'/../vendor/autoload.php',
    __DIR__.'/../../../laravel-app/vendor/autoload.php',
    __DIR__.'/../../../../../laravel-app/vendor/autoload.php',
];
$autoloadPath = null;
foreach ($autoloadPaths as $path) {
    if (file_exists($path)) {
        $autoloadPath = $path;
        break;
    }
}
if (!$autoloadPath) {
    die("Composer autoloader not found! Checked: " . implode(', ', $autoloadPaths));
}
require $autoloadPath;

// Bootstrap Laravel and handle the request...
$appPaths = [
    __DIR__.'/../bootstrap/app.php',
    __DIR__.'/../../../laravel-app/bootstrap/app.php',
    __DIR__.'/../../../../../laravel-app/bootstrap/app.php',
];
$appPath = null;
foreach ($appPaths as $path) {
    if (file_exists($path)) {
        $appPath = $path;
        break;
    }
}
if (!$appPath) {
    die("Laravel bootstrap/app.php not found!");
}
/** @var Application $app */
$app = require_once $appPath;

$app->handleRequest(Request::capture());
