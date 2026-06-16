<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo Security::escape($pageTitle ?? 'CMS System'); ?></title>
    <link rel="stylesheet" href="/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <aside class="sidebar">
            <div class="logo">
                <h1>CMS System</h1>
            </div>
            <nav class="menu">
                <a href="/admin/dashboard" class="menu-item">Dashboard</a>
                <?php if (in_array($_SESSION['user_role'], ['admin', 'editor'])): ?>
                    <a href="/admin/posts" class="menu-item">Посты</a>
                    <a href="/admin/categories" class="menu-item">Категории</a>
                    <a href="/admin/media" class="menu-item">Медиа</a>
                    <a href="/admin/comments" class="menu-item">Комментарии</a>
                <?php endif; ?>
                <?php if (in_array($_SESSION['user_role'], ['author'])): ?>
                    <a href="/admin/posts" class="menu-item">Мои посты</a>
                <?php endif; ?>
                <?php if ($_SESSION['user_role'] === 'admin'): ?>
                    <a href="/admin/users" class="menu-item">Пользователи</a>
                    <a href="/admin/settings" class="menu-item">Настройки</a>
                <?php endif; ?>
            </nav>
        </aside>

        <main class="content">
            <header class="topbar">
                <h2><?php echo Security::escape($pageTitle ?? 'Dashboard'); ?></h2>
                <div class="user-menu">
                    <span><?php echo Security::escape($_SESSION['user_name']); ?></span>
                    <a href="/admin/profile" class="btn-link">Профиль</a>
                    <a href="/logout" class="btn-link">Выход</a>
                </div>
            </header>

            <div class="page-content">
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php foreach ($errors as $field => $messages): ?>
                                <?php foreach ($messages as $message): ?>
                                    <li><?php echo Security::escape($message); ?></li>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success_message)): ?>
                    <div class="alert alert-success">
                        <?php echo Security::escape($success_message); ?>
                    </div>
                <?php endif; ?>

                <?php echo $content ?? ''; ?>
            </div>
        </main>
    </div>

    <script src="/js/admin.js"></script>
</body>
</html>
