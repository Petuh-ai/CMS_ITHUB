<?php
$pageTitle = 'Посты';
ob_start();
?>

<div class="posts-page">
    <div class="page-header">
        <h2>Посты</h2>
        <a href="/admin/posts/create" class="btn btn-primary">Создать пост</a>
    </div>

    <div class="filters">
        <form method="GET" action="/admin/posts">
            <input type="text" name="search" placeholder="Поиск...">
            <select name="status">
                <option value="">Все статусы</option>
                <option value="published">Опубликовано</option>
                <option value="draft">Черновики</option>
            </select>
            <button type="submit" class="btn">Фильтр</button>
        </form>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Название</th>
                <th>Автор</th>
                <th>Категория</th>
                <th>Статус</th>
                <th>Дата</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($posts as $post): ?>
                <tr>
                    <td><?php echo Security::escape($post['title']); ?></td>
                    <td><?php echo Security::escape($post['author_name']); ?></td>
                    <td><?php echo Security::escape($post['category_name'] ?? '-'); ?></td>
                    <td>
                        <span class="badge badge-<?php echo $post['status']; ?>">
                            <?php echo $post['status'] === 'published' ? 'Опубликовано' : 'Черновик'; ?>
                        </span>
                    </td>
                    <td><?php echo date('d.m.Y', strtotime($post['created_at'])); ?></td>
                    <td>
                        <a href="/admin/posts/<?php echo $post['id']; ?>/edit" class="btn-link">Редактировать</a>
                        <?php if ($post['status'] === 'draft'): ?>
                            <a href="/admin/posts/<?php echo $post['id']; ?>/publish" class="btn-link">Опубликовать</a>
                        <?php endif; ?>
                        <a href="/admin/posts/<?php echo $post['id']; ?>/delete" class="btn-link btn-danger"
                           onclick="return confirm('Вы уверены?');">Удалить</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Пагинация -->
    <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <?php if ($i == $page): ?>
                    <span class="current"><?php echo $i; ?></span>
                <?php else: ?>
                    <a href="/admin/posts?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../../layouts/admin.php';
?>
