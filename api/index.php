<?php

use Illuminate\Http\Request;

$runtimeViewPath = getenv('VIEW_COMPILED_PATH') ?: ($_SERVER['VIEW_COMPILED_PATH'] ?? $_ENV['VIEW_COMPILED_PATH'] ?? '/tmp/views');
$hasValue = static fn ($value): bool => is_string($value) ? trim($value) !== '' : $value !== null;

if (is_string($runtimeViewPath)) {
    $runtimeViewPath = trim($runtimeViewPath, "\"' ");

    if ($runtimeViewPath !== '' && ! is_dir($runtimeViewPath)) {
        mkdir($runtimeViewPath, 0755, true);
    }
}

$appKey = getenv('APP_KEY') ?: ($_SERVER['APP_KEY'] ?? $_ENV['APP_KEY'] ?? null);
$googleClientSecret = getenv('GOOGLE_CLIENT_SECRET') ?: ($_SERVER['GOOGLE_CLIENT_SECRET'] ?? $_ENV['GOOGLE_CLIENT_SECRET'] ?? null);

if (! $hasValue($appKey) && $hasValue($googleClientSecret)) {
    $appKey = 'base64:'.base64_encode(hash('sha256', $googleClientSecret, true));

    $_ENV['APP_KEY'] = $appKey;
    $_SERVER['APP_KEY'] = $appKey;
    putenv('APP_KEY='.$appKey);
}

require __DIR__ . '/../public/index.php';
