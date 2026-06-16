<?php
/**
 * Файл для быстрого тестирования функциональности
 * Удалите этот файл в production!
 * 
 * Использование:
 * 1. Откройте http://localhost/cms/public/test.php в браузере
 * 2. Следуйте инструкциям на странице
 */

// Проверка окружения
if ($_SERVER['HTTP_HOST'] !== 'localhost' && $_SERVER['HTTP_HOST'] !== '127.0.0.1' && !isset($_GET['unsafe'])) {
    die('Тестовый файл доступен только на localhost!');
}

require_once __DIR__ . '/app/Database.php';
require_once __DIR__ . '/app/Logger.php';
require_once __DIR__ . '/app/Security.php';

$errors = [];
$success = [];

// Проверка PHP версии
$php_version = phpversion();
if (version_compare($php_version, '7.4.0', '>=')) {
    $success[] = "PHP версия $php_version ✓";
} else {
    $errors[] = "PHP версия $php_version (минимум 7.4.0 требуется)";
}

// Проверка соединения с БД
try {
    $db = Database::getInstance();
    $result = $db->fetchOne("SELECT VERSION() as version");
    $success[] = "Соединение с MySQL установлено ✓ (версия: {$result['version']})";
} catch (Exception $e) {
    $errors[] = "Ошибка подключения к БД: " . $e->getMessage();
}

// Проверка папок
$folders_to_check = [
    'storage/logs' => "Логи",
    'storage/backups' => "Резервные копии",
    'public/uploads' => "Загрузки",
];

foreach ($folders_to_check as $folder => $name) {
    if (is_dir(__DIR__ . '/' . $folder)) {
        if (is_writable(__DIR__ . '/' . $folder)) {
            $success[] = "$name доступна для записи ✓";
        } else {
            $errors[] = "$name не доступна для записи (требуется chmod 755)";
        }
    } else {
        $errors[] = "$name не найдена (требуется создать)";
    }
}

// Проверка PHP расширений
$required_extensions = [
    'pdo' => 'PDO',
    'pdo_mysql' => 'PDO MySQL',
    'mbstring' => 'Mbstring',
    'json' => 'JSON',
];

foreach ($required_extensions as $ext => $name) {
    if (extension_loaded($ext)) {
        $success[] = "$name расширение установлено ✓";
    } else {
        $errors[] = "$name расширение НЕ установлено";
    }
}

// Проверка файлов конфигурации
$config_files = [
    'config/database.php',
    'config/app.php',
    'config/roles.php',
];

foreach ($config_files as $file) {
    if (file_exists(__DIR__ . '/' . $file)) {
        $success[] = "Файл конфигурации $file найден ✓";
    } else {
        $errors[] = "Файл конфигурации $file НЕ найден";
    }
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS System - Тест системы</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .section {
            margin-bottom: 30px;
        }
        .section h2 {
            color: #2c3e50;
            font-size: 18px;
            margin-bottom: 15px;
            border-bottom: 2px solid #ecf0f1;
            padding-bottom: 10px;
        }
        .success, .error {
            padding: 10px;
            margin-bottom: 8px;
            border-radius: 4px;
            font-size: 14px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        .status {
            text-align: center;
            margin-top: 30px;
            font-size: 18px;
            font-weight: bold;
        }
        .status.ready {
            color: #28a745;
        }
        .status.error {
            color: #dc3545;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Тест системы CMS</h1>

        <div class="section">
            <h2>✅ Успешные проверки</h2>
            <?php foreach ($success as $msg): ?>
                <div class="success"><?php echo htmlspecialchars($msg); ?></div>
            <?php endforeach; ?>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="section">
                <h2>❌ Ошибки и предупреждения</h2>
                <?php foreach ($errors as $msg): ?>
                    <div class="error"><?php echo htmlspecialchars($msg); ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="status <?php echo empty($errors) ? 'ready' : 'error'; ?>">
            <?php 
            if (empty($errors)) {
                echo "✅ Система готова к работе!<br>";
                echo "Перейдите на <a href='/login'>страницу входа</a>";
            } else {
                echo "❌ Обнаружены ошибки. Пожалуйста, исправьте их перед использованием системы.";
            }
            ?>
        </div>

        <div class="footer">
            <p>Это тестовая страница. В production удалите этот файл!</p>
            <p>Файл: public/test.php</p>
        </div>
    </div>
</body>
</html>
