<?php
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$publicPath = __DIR__ . '/public' . $uri;
$rootPath = __DIR__ . $uri;

if ($uri !== '/') {
    if (is_file($publicPath)) {
        $ext = pathinfo($publicPath, PATHINFO_EXTENSION);
        $types = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject',
            'json' => 'application/json',
            'html' => 'text/html; charset=UTF-8',
            'txt' => 'text/plain; charset=UTF-8',
        ];

        header('Content-Type: ' . ($types[$ext] ?? 'application/octet-stream'));
        readfile($publicPath);
        return true;
    }

    if (is_file($rootPath)) {
        return false;
    }
}

require_once __DIR__ . '/index.php';
