<?php

declare(strict_types=1);

$appUrl = getenv('APP_URL');
if (!is_string($appUrl) || trim($appUrl) === '') {
    $appUrl = 'http://localhost:8080';
}

return [
    'app_url' => rtrim($appUrl, '/'),
    'templates_path' => dirname(__DIR__) . '/templates',
    'compile_path' => dirname(__DIR__) . '/var/templates_c',
    'cache_path' => dirname(__DIR__) . '/var/cache',
];
