<?php
$pageTitle = Security::escape($post['title']);
ob_start();
?>

<article class="post-single">
    <h1><?php echo Security::escape($post['title']); ?></h1>
    
    <div class="post-meta">
        <span class="author">Автор: <?php echo Security::escape($post['author_name']); ?></span>
        <span class="date"><?php echo date('d.m.Y H:i', strtotime($post['published_at'])); ?></span>
        <?php if (!empty($post['category_name'])): ?>
            <span class="category">
                <a href="/category/<?php echo Security::escape($post['category_slug']); ?>">
                    <?php echo Security::escape($post['category_name']); ?>
                </a>
            </span>
        <?php endif; ?>
    </div>

    <div class="post-content">
        <?php echo $post['content']; // Контент должен быть безопасно очищен в контроллере ?>
    </div>

    <hr>

    <section class="comments-section">
        <h3>Комментарии (<?php echo count($comments); ?>)</h3>

        <?php if (!empty($comments)): ?>
            <div class="comments-list">
                <?php foreach ($comments as $comment): ?>
                    <div class="comment">
                        <div class="comment-author">
                            <strong><?php echo Security::escape($comment['author_name']); ?></strong>
                            <small><?php echo date('d.m.Y H:i', strtotime($comment['created_at'])); ?></small>
                        </div>
                        <div class="comment-content">
                            <?php echo Security::escape($comment['content']); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <h4>Оставить комментарий</h4>
        <form method="POST" class="comment-form">
            <input type="hidden" name="csrf_token" value="<?php echo Security::generateCSRFToken(); ?>">

            <div class="form-group">
                <label for="author_name">Имя:</label>
                <input type="text" id="author_name" name="author_name" required>
            </div>

            <div class="form-group">
                <label for="author_email">Email:</label>
                <input type="email" id="author_email" name="author_email" required>
            </div>

            <div class="form-group">
                <label for="content">Комментарий:</label>
                <textarea id="content" name="content" rows="5" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Отправить</button>
        </form>
    </section>
</article>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/frontend.php';
?>
