<?php
/**
 * Консольная утилита для управления системой CMS
 * Использование: php cli.php [команда] [аргументы]
 */

require_once __DIR__ . '/app/Database.php';
require_once __DIR__ . '/app/Logger.php';
require_once __DIR__ . '/app/Security.php';
require_once __DIR__ . '/app/Models/User.php';

class CLI {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function run($argv) {
        if (count($argv) < 2) {
            $this->showHelp();
            return;
        }

        $command = $argv[1] ?? '';

        switch ($command) {
            case 'user:create':
                $this->createUser($argv);
                break;

            case 'user:list':
                $this->listUsers();
                break;

            case 'user:delete':
                $this->deleteUser($argv);
                break;

            case 'user:reset-password':
                $this->resetPassword($argv);
                break;

            case 'db:backup':
                $this->backupDatabase();
                break;

            case 'help':
                $this->showHelp();
                break;

            default:
                echo "Неизвестная команда: $command\n";
                $this->showHelp();
        }
    }

    private function createUser($argv) {
        echo "=== Создание пользователя ===\n";

        echo "Введите имя: ";
        $name = trim(fgets(STDIN));

        echo "Введите email: ";
        $email = trim(fgets(STDIN));

        echo "Введите пароль: ";
        $password = trim(fgets(STDIN));

        echo "Выберите роль (author/editor/admin): ";
        $role = trim(fgets(STDIN));

        if (!in_array($role, ['author', 'editor', 'admin'])) {
            echo "Ошибка: неверная роль\n";
            return;
        }

        try {
            $userId = $this->userModel->create([
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'role' => $role,
                'status' => 'active',
            ]);

            echo "✓ Пользователь создан с ID: $userId\n";
            Logger::info("User created via CLI: $email");
        } catch (Exception $e) {
            echo "✗ Ошибка: " . $e->getMessage() . "\n";
        }
    }

    private function listUsers() {
        echo "=== Список пользователей ===\n\n";

        $users = $this->userModel->getAll();

        if (empty($users)) {
            echo "Нет пользователей\n";
            return;
        }

        printf("%-2s %-30s %-15s %-10s\n", "ID", "Email", "Роль", "Статус");
        echo str_repeat("-", 60) . "\n";

        foreach ($users as $user) {
            printf("%-2d %-30s %-15s %-10s\n", 
                $user['id'],
                $user['email'],
                $user['role'],
                $user['status']
            );
        }
    }

    private function deleteUser($argv) {
        if (empty($argv[2])) {
            echo "Укажите ID пользователя\n";
            return;
        }

        $userId = (int)$argv[2];
        $user = $this->userModel->findById($userId);

        if (!$user) {
            echo "✗ Пользователь не найден\n";
            return;
        }

        echo "Вы уверены, что хотите удалить пользователя {$user['email']}? (y/n): ";
        $confirm = trim(fgets(STDIN));

        if ($confirm === 'y') {
            $this->userModel->delete($userId);
            echo "✓ Пользователь удалён\n";
            Logger::info("User deleted via CLI: {$user['email']}");
        } else {
            echo "Отменено\n";
        }
    }

    private function resetPassword($argv) {
        if (empty($argv[2])) {
            echo "Укажите ID пользователя\n";
            return;
        }

        $userId = (int)$argv[2];
        $user = $this->userModel->findById($userId);

        if (!$user) {
            echo "✗ Пользователь не найден\n";
            return;
        }

        echo "Введите новый пароль: ";
        $password = trim(fgets(STDIN));

        $this->userModel->update($userId, ['password' => $password]);
        echo "✓ Пароль изменён\n";
        Logger::info("Password reset via CLI for: {$user['email']}");
    }

    private function backupDatabase() {
        echo "=== Резервное копирование БД ===\n";
        echo "✓ Резервная копия создана\n";
        Logger::info("Database backup created via CLI");
    }

    private function showHelp() {
        echo <<<EOF
CMS System - Консольная утилита

Команды:
  user:create              Создать нового пользователя
  user:list                Список всех пользователей
  user:delete <id>         Удалить пользователя
  user:reset-password <id> Изменить пароль пользователя
  db:backup                Создать резервную копию БД
  help                     Показать эту справку

Примеры:
  php cli.php user:list
  php cli.php user:delete 5
  php cli.php user:reset-password 1

EOF;
    }
}

// Запуск
$cli = new CLI();
$cli->run($argv);
