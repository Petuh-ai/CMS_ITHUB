<?php
/**
 * Добавление администратора в PostgreSQL, если он ещё не существует.
 */

$config = require __DIR__ . '/../config/database.php';

try {
    $dsn = sprintf(
        '%s:host=%s;port=%s;dbname=%s',
        $config['driver'],
        $config['host'],
        $config['port'],
        $config['database']
    );

    $pdo = new PDO(
        $dsn,
        $config['username'],
        $config['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );

    $email = 'admin@example.com';
    $password = password_hash('admin123', PASSWORD_BCRYPT);
    $name = 'Администратор';
    $role = 'admin';
    $status = 'active';

    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);

    if ($stmt->fetch()) {
        echo "Admin already exists.\n";
        exit(0);
    }

    $pdo->prepare(
        'INSERT INTO users (name, email, password, role, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)'
    )->execute([$name, $email, $password, $role, $status]);

    echo "Admin user created: admin@example.com / admin123\n";
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
    exit(1);
}
