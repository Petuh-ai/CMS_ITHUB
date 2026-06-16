<?php
$pageTitle = 'Dashboard';
ob_start();
?>

<div class="dashboard">
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Всего постов</h3>
            <p class="stat-number"><?php echo $stats['total_posts']; ?></p>
        </div>
        <div class="stat-card">
            <h3>Опубликовано</h3>
            <p class="stat-number"><?php echo $stats['published_posts']; ?></p>
        </div>
        <div class="stat-card">
            <h3>Черновиков</h3>
            <p class="stat-number"><?php echo $stats['draft_posts']; ?></p>
        </div>
        <div class="stat-card">
            <h3>Категорий</h3>
            <p class="stat-number"><?php echo $stats['total_categories']; ?></p>
        </div>
        <div class="stat-card">
            <h3>Пользователей</h3>
            <p class="stat-number"><?php echo $stats['total_users']; ?></p>
        </div>
        <div class="stat-card">
            <h3>Медиафайлов</h3>
            <p class="stat-number"><?php echo $stats['total_media']; ?></p>
        </div>
    </div>

    <div class="dashboard-grid">
        <div class="widget">
            <h3>Недавние посты</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Название</th>
                        <th>Автор</th>
                        <th>Статус</th>
                        <th>Дата</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentPosts as $post): ?>
                        <tr>
                            <td><?php echo Security::escape($post['title']); ?></td>
                            <td><?php echo Security::escape($post['author_name']); ?></td>
                            <td><span class="badge badge-<?php echo $post['status']; ?>">
                                <?php echo $post['status'] === 'published' ? 'Опубликовано' : 'Черновик'; ?>
                            </span></td>
                            <td><?php echo date('d.m.Y', strtotime($post['created_at'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="widget">
            <h3>Комментарии на модерации</h3>
            <?php if (!empty($pendingComments)): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Автор</th>
                            <th>Пост</th>
                            <th>Дата</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pendingComments as $comment): ?>
                            <tr>
                                <td><?php echo Security::escape($comment['author_name']); ?></td>
                                <td><?php echo Security::escape($comment['post_title']); ?></td>
                                <td><?php echo date('d.m.Y H:i', strtotime($comment['created_at'])); ?></td>
                                <td>
                                    <a href="/admin/comments/<?php echo $comment['id']; ?>/approve" 
                                       class="btn-link">Одобрить</a>
                                    <a href="/admin/comments/<?php echo $comment['id']; ?>/reject" 
                                       class="btn-link">Отклонить</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Нет комментариев на модерации</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/admin.php';
?>
