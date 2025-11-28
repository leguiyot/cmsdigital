<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Fallback para highlight_file cuando la función está deshabilitada en php.ini
// Sólo activar en entornos de desarrollo (APP_DEBUG=true)
if (!function_exists('highlight_file')) {
    $appDebug = getenv('APP_DEBUG');
    if ($appDebug === false) {
        $appDebug = isset($_ENV['APP_DEBUG']) ? $_ENV['APP_DEBUG'] : null;
    }

    if ($appDebug === '1' || strtolower($appDebug) === 'true') {
        function highlight_file($file, $return = false)
        {
            $content = @file_get_contents($file);
            if ($content === false) {
                return $return ? false : null;
            }

            $encoded = htmlspecialchars($content, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $out = '<pre style="white-space:pre-wrap;word-wrap:break-word;font-family:monospace;">' . $encoded . '</pre>';

            if ($return) {
                return $out;
            }

            echo $out;
        }
    }
}
// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
