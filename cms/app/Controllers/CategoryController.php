<?php
/**
 * Контроллер категорий
 */

class CategoryController
{
    private $categoryModel;

    public function __construct()
    {
        AuthMiddleware::checkAuth();
        AuthMiddleware::checkRole(['admin', 'editor']);
        $this->categoryModel = new Category();
    }

    public function index()
    {
        $categories = $this->categoryModel->getHierarchy();
        require __DIR__ . '/../Views/admin/categories/index.php';
    }

    public function create()
    {
        $categories = $this->categoryModel->getParents();
        $csrfToken = Security::generateCSRFToken();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->store();
        } else {
            require __DIR__ . '/../Views/admin/categories/create.php';
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
        ])) {
            $errors = $validator->getErrors();
            $categories = $this->categoryModel->getParents();
            require __DIR__ . '/../Views/admin/categories/create.php';
            return;
        }

        // Генерируем slug
        $slug = $this->generateSlug($_POST['name']);

        $data = [
            'name' => $_POST['name'],
            'slug' => $slug,
            'description' => $_POST['description'] ?? '',
            'parent_id' => $_POST['parent_id'] ?? null,
        ];

        $categoryId = $this->categoryModel->create($data);
        Logger::info("Category created: ID $categoryId");

        header('Location: /admin/categories');
        exit;
    }

    public function edit($id)
    {
        $category = $this->categoryModel->findById($id);
        if (!$category) {
            http_response_code(404);
            die('Категория не найдена');
        }

        $categories = $this->categoryModel->getParents();
        $csrfToken = Security::generateCSRFToken();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->update($id);
        } else {
            require __DIR__ . '/../Views/admin/categories/edit.php';
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
        ])) {
            $errors = $validator->getErrors();
            $category = $this->categoryModel->findById($id);
            $categories = $this->categoryModel->getParents();
            require __DIR__ . '/../Views/admin/categories/edit.php';
            return;
        }

        $slug = $this->generateSlug($_POST['name']);

        $data = [
            'name' => $_POST['name'],
            'slug' => $slug,
            'description' => $_POST['description'] ?? '',
            'parent_id' => $_POST['parent_id'] ?? null,
        ];

        $this->categoryModel->update($id, $data);
        Logger::info("Category updated: ID $id");

        header('Location: /admin/categories');
        exit;
    }

    public function delete($id)
    {
        try {
            $this->categoryModel->delete($id);
            Logger::info("Category deleted: ID $id");
            header('Location: /admin/categories');
        } catch (Exception $e) {
            $error = $e->getMessage();
            $categories = $this->categoryModel->getHierarchy();
            require __DIR__ . '/../Views/admin/categories/index.php';
        }
        exit;
    }

    private function generateSlug($name)
    {
        $slug = strtolower($name);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');
        return $slug;
    }
}
