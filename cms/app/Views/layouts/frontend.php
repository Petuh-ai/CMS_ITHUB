<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo Security::escape($siteDescription ?? ''); ?>">
    <meta name="keywords" content="<?php echo Security::escape($siteKeywords ?? ''); ?>">
    <title><?php echo Security::escape($pageTitle ?? 'Home'); ?> - CMS</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="/css/frontend.css">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
</head>
<body>
    <header class="navbar">
        <div class="container">
            <div class="logo">
                <h1><a href="/">CMS System</a></h1>
            </div>

            <button id="navToggle" class="nav-toggle" aria-label="Toggle navigation">☰</button>

            <nav class="nav" id="mainNav">
                <a href="#features">О сервисе</a>
                <a href="#profiles">Анкеты</a>
                <a href="#signup">Заполнить анкету</a>
                <form action="/" method="GET" class="nav-search" role="search">
                    <input type="search" name="q" placeholder="Поиск...">
                </form>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/admin/dashboard" class="btn btn-tertiary">Панель</a>
                    <a href="/logout" class="btn btn-tertiary">Выход</a>
                <?php else: ?>
                    <a href="/login" class="btn btn-primary">Вход</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main class="container">
        <div class="content">
            <?php echo $content ?? ''; ?>
        </div>

        <aside class="sidebar">
            <div class="widget">
                <h3>Категории</h3>
                <ul class="categories-list">
                    <?php foreach ($categories ?? [] as $category): ?>
                        <li>
                            <a href="/category/<?php echo Security::escape($category['slug']); ?>">
                                <?php echo Security::escape($category['name']); ?>
                                (<?php echo Security::escape($category['post_count']); ?>)
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="widget">
                <h3>Недавние посты</h3>
                <ul class="posts-list">
                    <?php foreach ($recentPosts ?? [] as $post): ?>
                        <li>
                            <a href="/post/<?php echo Security::escape($post['slug']); ?>">
                                <?php echo Security::escape($post['title']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </aside>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 CMS System. Все права защищены.</p>
        </div>
    </footer>

    <script src="/js/frontend.js"></script>
</body>
</html>
