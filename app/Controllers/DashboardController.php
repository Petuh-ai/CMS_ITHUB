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

        // Собираем статистику
        $totalPosts = $postModel->count();
        $totalUsers = $userModel->count();
        $totalComments = $commentModel->count('posts');
        $totalCategories = $categoryModel->count();
        
        // Получаем последние посты
        $recentPosts = $postModel->getAll(10);
        
        require __DIR__ . '/../Views/admin/dashboard.php';
    }
}
