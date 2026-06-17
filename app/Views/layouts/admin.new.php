<?php
// Файл для макета администратора
$pageTitle = 'Административная панель';

// Проверяем авторизацию
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: /login');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> - CMS</title>
    <link rel="stylesheet" href="/css/modern.css">
    <style>
        .admin-layout {
            display: grid;
            grid-template-columns: 250px 1fr;
            min-height: 100vh;
        }
        
        .admin-sidebar {
            background-color: var(--text-dark);
            color: white;
            padding: var(--spacing-lg);
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }
        
        .admin-sidebar h4 {
            color: white;
            margin: var(--spacing-lg) 0 var(--spacing-md) 0;
            font-size: 0.9rem;
            text-transform: uppercase;
            opacity: 0.7;
        }
        
        .admin-sidebar a {
            display: block;
            padding: var(--spacing-md);
            color: #cbd5e1;
            border-radius: var(--radius-md);
            transition: all 0.3s ease;
            margin-bottom: var(--spacing-sm);
        }
        
        .admin-sidebar a:hover,
        .admin-sidebar a.active {
            background-color: var(--primary);
            color: white;
        }
        
        .admin-main {
            display: flex;
            flex-direction: column;
        }
        
        .admin-header {
            background-color: white;
            border-bottom: 1px solid var(--border);
            padding: var(--spacing-lg);
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow-sm);
        }
        
        .admin-content {
            flex: 1;
            padding: var(--spacing-2xl);
            overflow-y: auto;
        }
        
        @media (max-width: 768px) {
            .admin-layout {
                grid-template-columns: 1fr;
            }
            
            .admin-sidebar {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="admin-layout">
        <!-- БОКОВАЯ ПАНЕЛЬ -->
        <aside class="admin-sidebar">
            <div style="margin-bottom: var(--spacing-2xl);">
                <h3 style="color: white; font-size: 1.25rem;">CMS Admin</h3>
            </div>
            
            <h4>Главное</h4>
            <a href="/admin/dashboard" class="<?= strpos($_SERVER['REQUEST_URI'], 'dashboard') !== false ? 'active' : '' ?>">📊 Панель управления</a>
            
            <h4>Контент</h4>
            <a href="/admin/posts" class="<?= strpos($_SERVER['REQUEST_URI'], 'posts') !== false ? 'active' : '' ?>">📝 Посты</a>
            <a href="/admin/posts/create">➕ Новый пост</a>
            <a href="/admin/categories" class="<?= strpos($_SERVER['REQUEST_URI'], 'categories') !== false ? 'active' : '' ?>">📂 Категории</a>
            <a href="/admin/media" class="<?= strpos($_SERVER['REQUEST_URI'], 'media') !== false ? 'active' : '' ?>">🖼️ Медиа</a>
            
            <h4>Управление</h4>
            <a href="/admin/comments" class="<?= strpos($_SERVER['REQUEST_URI'], 'comments') !== false ? 'active' : '' ?>">💬 Комментарии</a>
            <a href="/admin/users" class="<?= strpos($_SERVER['REQUEST_URI'], 'users') !== false ? 'active' : '' ?>">👥 Пользователи</a>
            <a href="/admin/settings" class="<?= strpos($_SERVER['REQUEST_URI'], 'settings') !== false ? 'active' : '' ?>">⚙️ Настройки</a>
            
            <h4 style="margin-top: var(--spacing-2xl);">Аккаунт</h4>
            <a href="/admin/profile">👤 Профиль</a>
            <a href="/logout">🚪 Выход</a>
        </aside>
        
        <!-- ОСНОВНОЙ КОНТЕНТ -->
        <div class="admin-main">
            <!-- ВЕРХНЯЯ ПАНЕЛЬ -->
            <header class="admin-header">
                <div>
                    <h2><?= $pageTitle ?></h2>
                </div>
                <div style="display: flex; gap: var(--spacing-lg); align-items: center;">
                    <a href="/" class="btn btn-secondary btn-sm">Сайт</a>
                    <span><?= Security::escape($_SESSION['user_name']) ?></span>
                </div>
            </header>
            
            <!-- СОДЕРЖАНИЕ -->
            <main class="admin-content">
                <?php include $templatePath ?? ''; ?>
            </main>
        </div>
    </div>
</body>
</html>
