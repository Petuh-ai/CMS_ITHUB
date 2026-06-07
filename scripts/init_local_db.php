<?php
/**
 * Локальная инициализация базы данных для CMS.
 *
 * Использование:
 *   php scripts/init_local_db.php
 */

function loadEnv(string $path): void
{
    if (!file_exists($path)) {
        echo "Файл $path не найден. Создайте .env на основе .env.local.example\n";
        exit(1);
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0 || strpos($line, '=') === false) {
            continue;
        }

        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);

        if (preg_match('/^"(.*)"$/', $value, $matches) || preg_match('/^\'(.*)\'$/', $value, $matches)) {
            $value = $matches[1];
        }

        $_ENV[$key] = $value;
        putenv("$key=$value");
    }
}

function getDbConfig(): array
{
    $driver = getenv('DB_DRIVER') ?: 'mysql';
    $host = getenv('DB_HOST') ?: '127.0.0.1';
    $port = getenv('DB_PORT') ?: ($driver === 'pgsql' ? 5432 : 3306);
    $database = getenv('DB_NAME') ?: 'cms_bd';
    $username = getenv('DB_USER') ?: 'root';
    $password = getenv('DB_PASSWORD') ?: '';

    return compact('driver', 'host', 'port', 'database', 'username', 'password');
}

function createDatabase(array $config): void
{
    echo "Создаю базу данных {$config['database']} ({$config['driver']})...\n";

    if ($config['driver'] === 'pgsql') {
        $dsn = sprintf('pgsql:host=%s;port=%d;dbname=postgres', $config['host'], $config['port']);
        $pdo = new PDO($dsn, $config['username'], $config['password']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = sprintf("SELECT 1 FROM pg_database WHERE datname = '%s'", $config['database']);
        $exists = $pdo->query($sql)->fetchColumn();

        if (!$exists) {
            $pdo->exec(sprintf("CREATE DATABASE %s WITH ENCODING='UTF8'", $config['database']));
            echo "База данных создана.\n";
        } else {
            echo "База данных уже существует.\n";
        }
    } else {
        $dsn = sprintf('mysql:host=%s;port=%d;charset=utf8mb4', $config['host'], $config['port']);
        $pdo = new PDO($dsn, $config['username'], $config['password']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec(sprintf(
            'CREATE DATABASE IF NOT EXISTS `%s` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci',
            $config['database']
        ));
        echo "База данных создана или уже существует.\n";
    }
}

function openDatabase(array $config): PDO
{
    if ($config['driver'] === 'pgsql') {
        $dsn = sprintf('pgsql:host=%s;port=%d;dbname=%s', $config['host'], $config['port'], $config['database']);
    } else {
        $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', $config['host'], $config['port'], $config['database']);
    }

    $pdo = new PDO($dsn, $config['username'], $config['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
}

function splitSqlStatements(string $sql): array
{
    $statements = [];
    $buffer = '';
    $inString = false;
    $stringChar = '';
    $len = strlen($sql);

    for ($i = 0; $i < $len; $i++) {
        $char = $sql[$i];
        $nextChar = $i + 1 < $len ? $sql[$i + 1] : '';

        if ($inString) {
            if ($char === $stringChar && $sql[$i - 1] !== '\\') {
                $inString = false;
            }
            $buffer .= $char;
            continue;
        }

        if ($char === '"' || $char === "'") {
            $inString = true;
            $stringChar = $char;
            $buffer .= $char;
            continue;
        }

        if ($char === '-' && $nextChar === '-') {
            while ($i < $len && $sql[$i] !== "\n") {
                $i++;
            }
            continue;
        }

        if ($char === '/' && $nextChar === '*') {
            $i += 2;
            while ($i < $len && !($sql[$i] === '*' && ($i + 1 < $len && $sql[$i + 1] === '/'))) {
                $i++;
            }
            $i++;
            continue;
        }

        if ($char === ';') {
            $statement = trim($buffer);
            if ($statement !== '') {
                $statements[] = $statement;
            }
            $buffer = '';
            continue;
        }

        $buffer .= $char;
    }

    $final = trim($buffer);
    if ($final !== '') {
        $statements[] = $final;
    }

    return $statements;
}

function importSchema(PDO $pdo): void
{
    $schemaFile = __DIR__ . '/../database/schema.sql';
    if (!file_exists($schemaFile)) {
        echo "Файл схемы $schemaFile не найден.\n";
        exit(1);
    }

    echo "Импорт схемы из $schemaFile...\n";
    $schema = file_get_contents($schemaFile);
    $queries = splitSqlStatements($schema);

    foreach ($queries as $query) {
        if (trim($query) === '') {
            continue;
        }
        $pdo->exec($query);
    }
    echo "Схема импортирована.\n";
}

function seedAdmin(PDO $pdo): void
{
    $exists = $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
    if ($exists > 0) {
        echo "Админ не создаётся: таблица users уже содержит записи.\n";
        return;
    }

    $password = password_hash('password', PASSWORD_BCRYPT);
    $sql = 'INSERT INTO users (name, email, password, role, status, created_at, updated_at) VALUES (:name, :email, :password, :role, :status, NOW(), NOW())';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':name' => 'Admin',
        ':email' => 'admin@example.com',
        ':password' => $password,
        ':role' => 'admin',
        ':status' => 'active',
    ]);

    echo "Создан администратор: admin@example.com / password\n";
}

loadEnv(__DIR__ . '/../.env');
$config = getDbConfig();
createDatabase($config);
$pdo = openDatabase($config);
importSchema($pdo);
seedAdmin($pdo);

echo "\nГотово. Теперь запустите проект через run-local.bat или run-local.sh и откройте http://localhost:8000\n";
