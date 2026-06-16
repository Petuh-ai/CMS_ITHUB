<?php
/**
 * Контроллер панели управления
 */

class DashboardController
{
    public function __construct()
    {
        AuthMiddleware::checkAuth();
    }

    public function index()
    {
        $postModel = new Post();
        $categoryModel = new Category();
        $userModel = new User();
        $mediaModel = new Media();
        $commentModel = new Comment();

        $stats = [
            'total_posts' => $postModel->count(),
            'published_posts' => $postModel->countPublished(),
            'draft_posts' => $postModel->count('draft'),
            'total_categories' => $categoryModel->count(),
            'total_users' => $userModel->count(),
            'total_media' => $mediaModel->count(),
            'pending_comments' => $commentModel->countPending(),
        ];

        $recentPosts = $postModel->getAll(5);
        $pendingComments = $commentModel->getPending();

        require __DIR__ . '/../Views/admin/dashboard.php';
    }
}
