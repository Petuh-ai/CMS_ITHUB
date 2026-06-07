<?php
/**
 * Конфигурация базы данных
 */

$driver = getenv('DB_DRIVER') ?: 'mysql';

$config = [
    'driver' => $driver,
    'host' => getenv('DB_HOST') ?: 'localhost',
    'port' => getenv('DB_PORT') ?: ($driver === 'pgsql' ? 5432 : 3306),
    'database' => getenv('DB_NAME') ?: 'cms_bd',
    'username' => getenv('DB_USER') ?: 'root',
    'password' => getenv('DB_PASSWORD') ?: '',
];

// PostgreSQL специфичные параметры
if ($driver === 'pgsql') {
    $config['charset'] = 'UTF8';
} else {
    // MySQL параметры
    $config['charset'] = 'utf8mb4';
    $config['collation'] = 'utf8mb4_unicode_ci';
}

return $config;
