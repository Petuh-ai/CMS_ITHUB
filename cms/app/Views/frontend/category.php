<?php
$pageTitle = 'Категория: ' . Security::escape($category['name']);
ob_start();
?>

<div class="category-page">
    <h1><?php echo Security::escape($category['name']); ?></h1>
    
    <?php if (!empty($category['description'])): ?>
        <p class="category-description"><?php echo Security::escape($category['description']); ?></p>
    <?php endif; ?>

    <div class="posts-grid">
        <?php foreach ($posts as $post): ?>
            <article class="post-card">
                <h2><a href="/post/<?php echo Security::escape($post['slug']); ?>">
                    <?php echo Security::escape($post['title']); ?>
                </a></h2>
                <div class="post-meta">
                    <span class="author">Автор: <?php echo Security::escape($post['author_name']); ?></span>
                    <span class="date"><?php echo date('d.m.Y', strtotime($post['published_at'])); ?></span>
                </div>
                <p><?php echo Security::escape(substr($post['excerpt'] ?: $post['content'], 0, 200)); ?>...</p>
                <a href="/post/<?php echo Security::escape($post['slug']); ?>" class="btn btn-link">Читать далее</a>
            </article>
        <?php endforeach; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/frontend.php';
?>
