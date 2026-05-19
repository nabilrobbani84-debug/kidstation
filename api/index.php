<?php

use Illuminate\Http\Request;

$runtimeViewPath = getenv('VIEW_COMPILED_PATH') ?: ($_SERVER['VIEW_COMPILED_PATH'] ?? $_ENV['VIEW_COMPILED_PATH'] ?? '/tmp/views');
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
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

if ($requestPath === '/__runtime-check') {
    header('Content-Type: application/json');

    echo json_encode([
        'commit' => getenv('VERCEL_GIT_COMMIT_SHA') ?: null,
        'app_key_configured' => $hasValue($appKey),
        'google_client_id_configured' => $hasValue(getenv('GOOGLE_CLIENT_ID') ?: ($_SERVER['GOOGLE_CLIENT_ID'] ?? $_ENV['GOOGLE_CLIENT_ID'] ?? null)),
        'google_client_secret_configured' => $hasValue($googleClientSecret),
        'google_redirect_configured' => $hasValue(getenv('GOOGLE_REDIRECT_URI') ?: ($_SERVER['GOOGLE_REDIRECT_URI'] ?? $_ENV['GOOGLE_REDIRECT_URI'] ?? null)),
        'runtime_view_path' => $runtimeViewPath,
        'runtime_view_path_exists' => is_dir($runtimeViewPath),
    ], JSON_PRETTY_PRINT);

    return;
}

require __DIR__ . '/../public/index.php';
