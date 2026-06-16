<?php
/**
 * Контроллер настроек
 */

class SettingsController
{
    private $settingsModel;

    public function __construct()
    {
        AuthMiddleware::checkAuth();
        AuthMiddleware::checkRole(['admin']);
        $this->settingsModel = new Settings();
    }

    public function index()
    {
        $settings = $this->settingsModel->getAll();
        $csrfToken = Security::generateCSRFToken();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->update();
        } else {
            require __DIR__ . '/../Views/admin/settings/index.php';
        }
    }

    public function update()
    {
        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            die('CSRF токен недействителен');
        }

        $allowedSettings = [
            'site_title',
            'site_description',
            'site_keywords',
            'posts_per_page',
            'comments_moderation',
            'enable_comments',
        ];

        foreach ($_POST as $key => $value) {
            if (in_array($key, $allowedSettings)) {
                $this->settingsModel->set($key, $value);
            }
        }

        Logger::info("Settings updated");
        header('Location: /admin/settings');
        exit;
    }

    public function backup()
    {
        AuthMiddleware::checkRole(['admin']);

        $db = Database::getInstance();
        $backupDir = __DIR__ . '/../../storage/backups/';
        $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
        $filepath = $backupDir . $filename;

        // Здесь должно быть создание резервной копии
        // Это просто демонстрация
        Logger::info("Database backup created: $filename");

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        // Чтение и отправка файла
    }
}
