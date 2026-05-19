<?php

use Illuminate\Http\Request;

$runtimeViewPath = getenv('VIEW_COMPILED_PATH') ?: ($_SERVER['VIEW_COMPILED_PATH'] ?? $_ENV['VIEW_COMPILED_PATH'] ?? '/tmp/views');

if (is_string($runtimeViewPath)) {
    $runtimeViewPath = trim($runtimeViewPath, "\"' ");

    if ($runtimeViewPath !== '' && ! is_dir($runtimeViewPath)) {
        mkdir($runtimeViewPath, 0755, true);
    }
}

require __DIR__ . '/../public/index.php';
