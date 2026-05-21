<?php
/**
 * Конфигурация базы данных
 */

$driver = $_ENV['DB_DRIVER'] ?? 'pgsql';

$config = [
    'driver' => $driver,
    'host' => $_ENV['DB_HOST'] ?? 'localhost',
    'port' => $_ENV['DB_PORT'] ?? ($driver === 'pgsql' ? 5432 : 3306),
    'database' => $_ENV['DB_NAME'] ?? 'cms_bd',
    'username' => $_ENV['DB_USER'] ?? 'postgres',
    'password' => $_ENV['DB_PASSWORD'] ?? '',
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
