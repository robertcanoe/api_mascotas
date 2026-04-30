<?php

declare(strict_types=1);

use Dotenv\Dotenv;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

require_once __DIR__ . '/config/paths.php';
require_once ROOT_PATH . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(ROOT_PATH);
$dotenv->safeLoad();

// Render (y similares) inyectan RENDER_EXTERNAL_URL; unificamos a APP_URL para el resto de la app
$appUrl = env('APP_URL');
if (($appUrl === null || $appUrl === '') && env('RENDER_EXTERNAL_URL')) {
    $_ENV['APP_URL'] = rtrim((string) env('RENDER_EXTERNAL_URL'), '/');
}

$requiredEnv = [
    'APP_ENV',
    'APP_URL',
    'DB_DRIVER',
    'DB_HOST',
    'DB_PORT',
    'DB_NAME',
    'DB_USER',
    'JWT_SECRET',
    'JWT_ISSUER',
    'JWT_TTL',
];

$missing = [];
foreach ($requiredEnv as $key) {
    if ($key === 'APP_URL') {
        $value = env('APP_URL');
        if (($value === null || $value === '') && (env('RENDER_EXTERNAL_URL') === null || env('RENDER_EXTERNAL_URL') === '')) {
            $missing[] = $key;
        }
        continue;
    }
    $value = env($key);
    if ($value === null || $value === '') {
        $missing[] = $key;
    }
}

if ($missing !== []) {
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'error' => 'Configuration error',
        'message' => 'Missing required environment variables.',
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$config = require CONFIG_PATH . '/app.php';

if (!defined('APP_ENV')) {
    define('APP_ENV', (string) $config['app']['env']);
}

if (!defined('APP_DEBUG')) {
    define('APP_DEBUG', (bool) $config['app']['debug']);
}

if (!defined('BASE_URL')) {
    define('BASE_URL', (string) $config['app']['url']);
}

if (!defined('BASE_PATH')) {
    $parsedPath = parse_url(BASE_URL, PHP_URL_PATH) ?: '';
    $normalized = '/' . trim((string) $parsedPath, '/');
    define('BASE_PATH', $normalized === '/' ? '' : $normalized);
}

date_default_timezone_set((string) $config['app']['timezone']);

$requiredDirs = [
    CACHE_PATH,
    LOGS_PATH,
    PUBLIC_PATH . '/uploads',
    PUBLIC_PATH . '/uploads/contactos',
];

foreach ($requiredDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_name('protectora_session');
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', APP_DEBUG ? '1' : '0');

if (APP_DEBUG) {
    $whoops = new Run();
    $whoops->pushHandler(new PrettyPageHandler());
    $whoops->register();
} else {
    set_exception_handler(static function (Throwable $exception): void {
        app_log($exception->getMessage() . ' in ' . $exception->getFile() . ':' . $exception->getLine(), 'ERROR');

        http_response_code(500);
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode([
            'error' => 'Internal Server Error',
            'message' => 'Unexpected server error.',
        ], JSON_UNESCAPED_UNICODE);
    });
}
