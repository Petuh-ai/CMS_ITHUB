<?php
/**
 * Тестовый скрипт для проверки подключения к БД
 */

// Предотвращаем вывод кэша
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Content-Type: text/plain; charset=utf-8');

echo "=== ТЕСТ ПОДКЛЮЧЕНИЯ К БД ===\n\n";

// Пытаемся подключиться к БД напрямую
try {
    $dsn = 'mysql:host=localhost;port=3306;dbname=cms_db;charset=utf8mb4';
    $pdo = new PDO($dsn, 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    
    echo "✅ Подключение успешно!\n\n";
    
    // Проверяем таблицы
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "📊 Таблицы в БД:\n";
    foreach ($tables as $table) {
        echo "  - $table\n";
    }
    
    echo "\n👤 Пользователи в БД:\n";
    $stmt = $pdo->query("SELECT id, name, email FROM users");
    $users = $stmt->fetchAll();
    
    if (empty($users)) {
        echo "  Нет пользователей!\n";
    } else {
        foreach ($users as $user) {
            echo "  [{$user['id']}] {$user['name']} ({$user['email']})\n";
        }
    }
    
} catch (PDOException $e) {
    echo "❌ ОШИБКА ПОДКЛЮЧЕНИЯ:\n";
    echo $e->getMessage() . "\n\n";
    
    echo "Проверьте:\n";
    echo "1. MySQL запущен в XAMPP?\n";
    echo "2. База данных 'cms_db' создана?\n";
    echo "3. Правильны ли учетные данные (root, без пароля)?\n";
}
?>
