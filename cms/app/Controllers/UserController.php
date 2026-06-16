<?php
/**
 * Контроллер управления пользователями
 */

class UserController
{
    private $userModel;

    public function __construct()
    {
        AuthMiddleware::checkAuth();
        AuthMiddleware::checkRole(['admin']);
        $this->userModel = new User();
    }

    public function index()
    {
        $page = $_GET['page'] ?? 1;
        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        $users = $this->userModel->getAll($perPage, $offset);
        $total = $this->userModel->count();
        $totalPages = ceil($total / $perPage);

        require __DIR__ . '/../Views/admin/users/index.php';
    }

    public function create()
    {
        $csrfToken = Security::generateCSRFToken();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->store();
        } else {
            require __DIR__ . '/../Views/admin/users/create.php';
        }
    }

    public function store()
    {
        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            die('CSRF токен недействителен');
        }

        $validator = new Validator($_POST);
        if (!$validator->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'role' => 'required',
        ])) {
            $errors = $validator->getErrors();
            require __DIR__ . '/../Views/admin/users/create.php';
            return;
        }

        $data = [
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'password' => $_POST['password'],
            'role' => $_POST['role'],
            'status' => 'active',
        ];

        $userId = $this->userModel->create($data);
        Logger::info("User created: ID $userId, Email: {$_POST['email']}");

        header('Location: /admin/users');
        exit;
    }

    public function edit($id)
    {
        $user = $this->userModel->findById($id);
        if (!$user) {
            http_response_code(404);
            die('Пользователь не найден');
        }

        $csrfToken = Security::generateCSRFToken();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->update($id);
        } else {
            require __DIR__ . '/../Views/admin/users/edit.php';
        }
    }

    public function update($id)
    {
        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            die('CSRF токен недействителен');
        }

        $validator = new Validator($_POST);
        if (!$validator->validate([
            'name' => 'required|min:3',
            'role' => 'required',
        ])) {
            $errors = $validator->getErrors();
            $user = $this->userModel->findById($id);
            require __DIR__ . '/../Views/admin/users/edit.php';
            return;
        }

        $data = [
            'name' => $_POST['name'],
            'role' => $_POST['role'],
            'status' => $_POST['status'] ?? 'active',
        ];

        if (!empty($_POST['password'])) {
            $data['password'] = $_POST['password'];
        }

        $this->userModel->update($id, $data);
        Logger::info("User updated: ID $id");

        header('Location: /admin/users');
        exit;
    }

    public function delete($id)
    {
        $this->userModel->delete($id);
        Logger::info("User deleted: ID $id");

        header('Location: /admin/users');
        exit;
    }

    public function profile()
    {
        $userId = $_SESSION['user_id'];
        $user = $this->userModel->findById($userId);
        $csrfToken = Security::generateCSRFToken();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->updateProfile($userId);
        } else {
            require __DIR__ . '/../Views/admin/profile/edit.php';
        }
    }

    public function updateProfile($userId)
    {
        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            die('CSRF токен недействителен');
        }

        $validator = new Validator($_POST);
        if (!$validator->validate([
            'name' => 'required|min:3',
            'email' => 'required|email',
        ])) {
            $errors = $validator->getErrors();
            $user = $this->userModel->findById($userId);
            require __DIR__ . '/../Views/admin/profile/edit.php';
            return;
        }

        $data = [
            'name' => $_POST['name'],
            'email' => $_POST['email'],
        ];

        if (!empty($_POST['password'])) {
            // Проверяем текущий пароль
            if (!$this->userModel->verifyPassword($userId, $_POST['current_password'])) {
                $errors = ['current_password' => ['Неверный пароль']];
                $user = $this->userModel->findById($userId);
                require __DIR__ . '/../Views/admin/profile/edit.php';
                return;
            }
            $data['password'] = $_POST['password'];
        }

        $this->userModel->update($userId, $data);
        Logger::info("User profile updated: ID $userId");

        $_SESSION['user_name'] = $_POST['name'];
        $_SESSION['user_email'] = $_POST['email'];

        header('Location: /admin/dashboard');
        exit;
    }
}
