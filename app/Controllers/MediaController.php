<?php
/**
 * Контроллер медиабиблиотеки
 */

class MediaController
{
    private $mediaModel;

    public function __construct()
    {
        AuthMiddleware::checkAuth();
        $this->mediaModel = new Media();
    }

    public function index()
    {
        $page = $_GET['page'] ?? 1;
        $perPage = 20;
        $offset = ($page - 1) * $perPage;

        $media = $this->mediaModel->getAll($perPage, $offset);
        $total = $this->mediaModel->count();
        $totalPages = ceil($total / $perPage);

        require __DIR__ . '/../Views/admin/media/index.php';
    }

    public function upload()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return;
        }

        if (!isset($_FILES['file'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Файл не найден']);
            exit;
        }

        try {
            $fileId = $this->mediaModel->upload($_FILES['file']);
            echo json_encode([
                'success' => true,
                'id' => $fileId,
                'message' => 'Файл загружен успешно'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }

    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return;
        }

        try {
            $this->mediaModel->delete($id);
            Logger::info("Media deleted: ID $id");
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }

    public function api($id = null)
    {
        header('Content-Type: application/json');

        if ($id) {
            $media = $this->mediaModel->findById($id);
            if (!$media) {
                http_response_code(404);
                echo json_encode(['error' => 'Файл не найден']);
                exit;
            }
            echo json_encode($media);
        } else {
            $media = $this->mediaModel->getAll();
            echo json_encode($media);
        }
        exit;
    }
}
