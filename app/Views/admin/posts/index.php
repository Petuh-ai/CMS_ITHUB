<?php
// Страница со списком постов в админке

AuthMiddleware::checkAuth();
$postModel = new Post();
$categoryModel = new Category();

$page = $_GET['page'] ?? 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;

$posts = $postModel->getAll($perPage, $offset);
$totalPosts = $postModel->count();
$totalPages = ceil($totalPosts / $perPage);

$pageTitle = 'Управление постами';
ob_start();
?>

<h1>📝 Управление постами</h1>

<a href="/admin/posts/create" class="btn btn-primary" style="margin-bottom: 1.5rem;">➕ Создать пост</a>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Название</th>
                <th>Категория</th>
                <th>Автор</th>
                <th>Статус</th>
                <th>Дата</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($posts as $post): ?>
                <tr>
                    <td><strong><?= Security::escape($post['title']) ?></strong></td>
                    <td><?= Security::escape($post['category_name'] ?? '-') ?></td>
                    <td><?= Security::escape($post['author_name'] ?? 'N/A') ?></td>
                    <td>
                        <span style="display: inline-block; padding: 0.25rem 0.75rem; border-radius: 0.375rem; font-size: 0.875rem; background: <?= $post['status'] === 'published' ? '#d1fae5' : '#fef3c7' ?>; color: <?= $post['status'] === 'published' ? '#065f46' : '#78350f' ?>;">
                            <?= ucfirst($post['status']) ?>
                        </span>
                    </td>
                    <td><?= date('d.m.Y', strtotime($post['created_at'])) ?></td>
                    <td>
                        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                            <a href="/admin/posts/<?= $post['id'] ?>/edit" class="btn btn-sm btn-primary">Редактировать</a>
                            <a href="/post/<?= Security::escape($post['slug']) ?>" class="btn btn-sm btn-secondary" target="_blank">Просмотр</a>
                            <a href="/admin/posts/<?= $post['id'] ?>/delete" class="btn btn-sm btn-danger" onclick="return confirm('Удалить?');">Удалить</a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- ПАГИНАЦИЯ -->
<?php if ($totalPages > 1): ?>
    <div style="margin-top: 1.5rem; display: flex; justify-content: center; gap: 0.5rem; flex-wrap: wrap;">
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1 ?>" class="btn btn-secondary">← Предыдущая</a>
        <?php endif; ?>
        
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>" class="btn <?= $i === $page ? 'btn-primary' : 'btn-secondary' ?>"><?= $i ?></a>
        <?php endfor; ?>
        
        <?php if ($page < $totalPages): ?>
            <a href="?page=<?= $page + 1 ?>" class="btn btn-secondary">Следующая →</a>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php
$content = ob_get_clean();
require __DIR__ . '/../../layouts/admin.php';
?>
