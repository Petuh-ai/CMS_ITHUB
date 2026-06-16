<?php
$pageTitle = 'Комментарии';
ob_start();
?>

<div class="comments-page">
    <h2>Комментарии на модерации</h2>

    <?php if (empty($comments)): ?>
        <p>Нет комментариев на модерации</p>
    <?php else: ?>
        <div class="comments-list">
            <?php foreach ($comments as $comment): ?>
                <div class="comment-item">
                    <div class="comment-header">
                        <strong><?php echo Security::escape($comment['author_name']); ?></strong>
                        <small><?php echo date('d.m.Y H:i', strtotime($comment['created_at'])); ?></small>
                    </div>
                    <div class="comment-body">
                        <p><?php echo Security::escape($comment['content']); ?></p>
                    </div>
                    <div class="comment-footer">
                        <small>В посте: <em><?php echo Security::escape($comment['post_title']); ?></em></small>
                    </div>
                    <div class="comment-actions">
                        <a href="/admin/comments/<?php echo $comment['id']; ?>/approve" class="btn btn-success">Одобрить</a>
                        <a href="/admin/comments/<?php echo $comment['id']; ?>/reject" class="btn btn-warning">Отклонить</a>
                        <a href="/admin/comments/<?php echo $comment['id']; ?>/delete" class="btn btn-danger" 
                           onclick="return confirm('Вы уверены?');">Удалить</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../../layouts/admin.php';
?>
