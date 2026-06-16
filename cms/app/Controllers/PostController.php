<?php
/**
 * Контроллер постов (администраторская часть)
 */

class PostController
{
    private $postModel;
    private $categoryModel;
    private $mediaModel;

    public function __construct()
    {
        AuthMiddleware::checkAuth();
        $this->postModel = new Post();
        $this->categoryModel = new Category();
        $this->mediaModel = new Media();
    }

    public function index()
    {
        $page = $_GET['page'] ?? 1;
        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        $posts = $this->postModel->getAll($perPage, $offset);
        $total = $this->postModel->count();
        $totalPages = ceil($total / $perPage);

        require __DIR__ . '/../Views/admin/posts/index.php';
    }

    public function create()
    {
        $categories = $this->categoryModel->getAll();
        $csrfToken = Security::generateCSRFToken();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->store();
        } else {
            require __DIR__ . '/../Views/admin/posts/create.php';
        }
    }

    public function store()
    {
        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            die('CSRF токен недействителен');
        }

        // Проверяем права
        if (!in_array($_SESSION['user_role'], ['admin', 'editor'])) {
            die('У вас нет прав для создания постов');
        }

        $validator = new Validator($_POST);
        if (!$validator->validate([
            'title' => 'required|min:5',
            'content' => 'required|min:20',
            'category_id' => 'required',
        ])) {
            $errors = $validator->getErrors();
            $categories = $this->categoryModel->getAll();
            require __DIR__ . '/../Views/admin/posts/create.php';
            return;
        }

        $data = [
            'title' => $_POST['title'],
            'content' => $_POST['content'],
            'excerpt' => $_POST['excerpt'] ?? '',
            'category_id' => $_POST['category_id'],
            'status' => $_POST['status'] ?? 'draft',
        ];

        $postId = $this->postModel->create($data);
        Logger::info("Post created: ID $postId");

        header('Location: /admin/posts');
        exit;
    }

    public function edit($id)
    {
        $post = $this->postModel->findById($id);
        if (!$post) {
            http_response_code(404);
            die('Пост не найден');
        }

        // Проверяем права (автор может редактировать только свои посты)
        if ($_SESSION['user_role'] === 'author' && $post['author_id'] !== $_SESSION['user_id']) {
            die('У вас нет прав редактировать этот пост');
        }

        $categories = $this->categoryModel->getAll();
        $csrfToken = Security::generateCSRFToken();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->update($id);
        } else {
            require __DIR__ . '/../Views/admin/posts/edit.php';
        }
    }

    public function update($id)
    {
        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            die('CSRF токен недействителен');
        }

        $post = $this->postModel->findById($id);
        if (!$post) {
            http_response_code(404);
            return;
        }

        // Проверяем права
        if ($_SESSION['user_role'] === 'author' && $post['author_id'] !== $_SESSION['user_id']) {
            die('Доступ запрещен');
        }

        $validator = new Validator($_POST);
        if (!$validator->validate([
            'title' => 'required|min:5',
            'content' => 'required|min:20',
        ])) {
            $errors = $validator->getErrors();
            $categories = $this->categoryModel->getAll();
            require __DIR__ . '/../Views/admin/posts/edit.php';
            return;
        }

        $data = [
            'title' => $_POST['title'],
            'content' => $_POST['content'],
            'excerpt' => $_POST['excerpt'] ?? '',
            'category_id' => $_POST['category_id'] ?? null,
            'status' => $_POST['status'] ?? 'draft',
        ];

        $this->postModel->update($id, $data);
        Logger::info("Post updated: ID $id");

        header('Location: /admin/posts');
        exit;
    }

    public function delete($id)
    {
        $post = $this->postModel->findById($id);
        if (!$post) {
            http_response_code(404);
            return;
        }

        // Проверяем права
        if (!in_array($_SESSION['user_role'], ['admin', 'editor'])) {
            die('Доступ запрещен');
        }

        $this->postModel->delete($id);
        Logger::info("Post deleted: ID $id");

        header('Location: /admin/posts');
        exit;
    }

    public function publish($id)
    {
        $post = $this->postModel->findById($id);
        if (!$post) {
            http_response_code(404);
            return;
        }

        // Проверяем права
        if (!in_array($_SESSION['user_role'], ['admin', 'editor'])) {
            die('Доступ запрещен');
        }

        $this->postModel->publish($id);
        Logger::info("Post published: ID $id");

        header('Location: /admin/posts');
        exit;
    }
}
