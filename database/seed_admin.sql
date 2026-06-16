-- SQL скрипт для создания администратора
-- Пароль: admin123 (BCRYPT хеш)

INSERT IGNORE INTO users (name, email, password, role, status, created_at, updated_at) VALUES
(
    'Администратор',
    'admin@example.com',
    '$2y$12$iLPxIhQoMx5h0s8MhDKfMe3vJdGfq2nR1ZP8mXpjuwKqD8XY3MQay',
    'admin',
    'active',
    NOW(),
    NOW()
);

-- Для изменения пароля администратора, используйте PHP:
-- echo password_hash('новый_пароль', PASSWORD_BCRYPT, ['cost' => 12]);
-- Затем обновите значение в БД

-- После выполнения этого скрипта:
-- 1. Вход: admin@example.com / admin123
-- 2. Измените пароль в профиле
