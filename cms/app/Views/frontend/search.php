<?php
$pageTitle = 'Поиск: ' . Security::escape($_GET['q'] ?? '');
ob_start();
?>

<div class="search-page">
    <h1>Результаты поиска</h1>

    <form method="GET" action="/search" class="search-form">
        <input type="text" name="q" value="<?php echo Security::escape($query); ?>" placeholder="Поиск...">
        <button type="submit">Поиск</button>
    </form>

    <?php if (!empty($error)): ?>
        <div class="alert alert-info"><?php echo Security::escape($error); ?></div>
    <?php elseif (empty($posts)): ?>
        <p>По запросу "<?php echo Security::escape($query); ?>" ничего не найдено</p>
    <?php else: ?>
        <p>Найдено результатов: <?php echo count($posts); ?></p>

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
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/frontend.php';
?>
