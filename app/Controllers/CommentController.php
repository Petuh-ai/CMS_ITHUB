<?php
/**
 * Контроллер управления комментариями
 */

class CommentController
{
    private $commentModel;

    public function __construct()
    {
        AuthMiddleware::checkAuth();
        AuthMiddleware::checkRole(['admin', 'editor']);
        $this->commentModel = new Comment();
    }

    public function index()
    {
        $comments = $this->commentModel->getPending();
        require __DIR__ . '/../Views/admin/comments/index.php';
    }

    public function approve($id)
    {
        $this->commentModel->approve($id);
        Logger::info("Comment approved: ID $id");
        header('Location: /admin/comments');
        exit;
    }

    public function reject($id)
    {
        $this->commentModel->reject($id);
        Logger::info("Comment rejected: ID $id");
        header('Location: /admin/comments');
        exit;
    }

    public function delete($id)
    {
        $this->commentModel->delete($id);
        Logger::info("Comment deleted: ID $id");
        header('Location: /admin/comments');
        exit;
    }
}
