<?php
/**
 * Контроллер аутентификации
 */

class AuthController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function loginPage()
    {
        AuthMiddleware::checkGuest();
        require __DIR__ . '/../Views/auth/login.php';
    }

    public function login()
    {
        AuthMiddleware::checkGuest();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return;
        }

        // Проверяем CSRF токен
        if (!isset($_POST['csrf_token']) || !Security::verifyCSRFToken($_POST['csrf_token'])) {
            die('CSRF токен недействителен');
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // Валидируем
        $validator = new Validator($_POST);
        if (!$validator->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ])) {
            $errors = $validator->getErrors();
            require __DIR__ . '/../Views/auth/login.php';
            return;
        }

        // Ищем пользователя
        $user = $this->userModel->findByEmail($email);
        if (!$user) {
            Logger::warning("Login failed: user not found for email {$email}");
            $errors = ['auth' => ['Неверный email или пароль']];
            require __DIR__ . '/../Views/auth/login.php';
            return;
        }

        if (!Security::verifyPassword($password, $user['password'])) {
            Logger::warning("Login failed: password mismatch for email {$email}");
            $errors = ['auth' => ['Неверный email или пароль']];
            require __DIR__ . '/../Views/auth/login.php';
            return;
        }

        // Логин успешен
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];

        Logger::info("User logged in: {$user['email']}");

        header('Location: /admin/dashboard');
        exit;
    }

    public function logout()
    {
        session_destroy();
        Logger::info("User logged out");
        header('Location: /');
        exit;
    }

    public function forgotPassword()
    {
        AuthMiddleware::checkGuest();
        require __DIR__ . '/../Views/auth/forgot-password.php';
    }

    public function sendResetLink()
    {
        AuthMiddleware::checkGuest();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return;
        }

        if (!isset($_POST['csrf_token']) || !Security::verifyCSRFToken($_POST['csrf_token'])) {
            die('CSRF токен недействителен');
        }

        $email = $_POST['email'] ?? '';

        $validator = new Validator(['email' => $email]);
        if (!$validator->validate(['email' => 'required|email'])) {
            $errors = $validator->getErrors();
            require __DIR__ . '/../Views/auth/forgot-password.php';
            return;
        }

        $user = $this->userModel->findByEmail($email);
        if ($user) {
            // Здесь нужно слать письмо с ссылкой на восстановление
            // Для демонстрации просто логируем
            Logger::info("Password reset requested for: $email");
        }

        // Всегда показываем одно сообщение для безопасности
        $success_message = 'Если этот email зарегистрирован, вы получите письмо с инструкциями';
        require __DIR__ . '/../Views/auth/forgot-password.php';
    }
}
