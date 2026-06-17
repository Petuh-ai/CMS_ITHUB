<?php
// Администраторская панель управления

$pageTitle = 'Панель управления';
ob_start();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> - CMS Admin</title>
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
            background-color: var(--light);
        }
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: var(--spacing-lg);
            margin-bottom: var(--spacing-2xl);
        }
        
        .dashboard-card {
            background-color: white;
            padding: var(--spacing-lg);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            text-align: center;
            border: 1px solid var(--border);
        }
        
        .dashboard-card .number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary);
        }
        
        .dashboard-card .label {
            color: var(--text-light);
            font-size: 0.9rem;
            margin-top: var(--spacing-md);
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
                <h3 style="color: white; font-size: 1.25rem;">📊 CMS Admin</h3>
            </div>
            
            <h4>Главное</h4>
            <a href="/admin/dashboard" class="active">📊 Панель управления</a>
            
            <h4>Контент</h4>
            <a href="/admin/posts">📝 Посты</a>
            <a href="/admin/posts/create">➕ Новый пост</a>
            <a href="/admin/categories">📂 Категории</a>
            <a href="/admin/media">🖼️ Медиа</a>
            
            <h4>Управление</h4>
            <a href="/admin/comments">💬 Комментарии</a>
            <a href="/admin/users">👥 Пользователи</a>
            <a href="/admin/settings">⚙️ Настройки</a>
            
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
                <h1 style="margin-bottom: var(--spacing-xl);">Добро пожаловать, <?= Security::escape($_SESSION['user_name']) ?>!</h1>
                
                <!-- СТАТИСТИКА -->
                <div class="dashboard-grid">
                    <div class="dashboard-card">
                        <div class="number"><?= $totalPosts ?? 0 ?></div>
                        <div class="label">Всего постов</div>
                    </div>
                    <div class="dashboard-card">
                        <div class="number"><?= $totalUsers ?? 1 ?></div>
                        <div class="label">Пользователей</div>
                    </div>
                    <div class="dashboard-card">
                        <div class="number"><?= $totalComments ?? 0 ?></div>
                        <div class="label">Комментариев</div>
                    </div>
                    <div class="dashboard-card">
                        <div class="number"><?= $totalCategories ?? 0 ?></div>
                        <div class="label">Категорий</div>
                    </div>
                </div>
                
                <!-- БЫСТРЫЕ ДЕЙСТВИЯ -->
                <div class="card" style="margin-bottom: var(--spacing-2xl);">
                    <h3>⚡ Быстрые действия</h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--spacing-md); margin-top: var(--spacing-lg);">
                        <a href="/admin/posts/create" class="btn btn-primary btn-lg" style="text-align: center;">Создать пост</a>
                        <a href="/admin/categories" class="btn btn-secondary btn-lg" style="text-align: center;">Управлять категориями</a>
                        <a href="/admin/users" class="btn btn-secondary btn-lg" style="text-align: center;">Управлять пользователями</a>
                        <a href="/admin/settings" class="btn btn-secondary btn-lg" style="text-align: center;">Настройки</a>
                    </div>
                </div>
                
                <!-- ПОСЛЕДНИЕ ПОСТЫ -->
                <div class="card">
                    <h3 style="margin-bottom: var(--spacing-lg);">📝 Последние посты</h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Название</th>
                                <th>Автор</th>
                                <th>Статус</th>
                                <th>Дата</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($recentPosts) && !empty($recentPosts)): ?>
                                <?php foreach (array_slice($recentPosts, 0, 5) as $post): ?>
                                    <tr>
                                        <td><?= Security::escape($post['title']) ?></td>
                                        <td><?= Security::escape($post['author_name'] ?? 'N/A') ?></td>
                                        <td>
                                            <span style="display: inline-block; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.8rem; background: <?= $post['status'] === 'published' ? '#d1fae5' : '#fef3c7' ?>; color: <?= $post['status'] === 'published' ? '#065f46' : '#78350f' ?>;">
                                                <?= ucfirst($post['status']) ?>
                                            </span>
                                        </td>
                                        <td><?= date('d.m.Y', strtotime($post['created_at'])) ?></td>
                                        <td>
                                            <a href="/admin/posts/<?= $post['id'] ?>/edit" class="btn btn-sm btn-primary">Редактировать</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" style="text-align: center; color: var(--text-light);">Нет постов</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
</body>
</html>

<?php
$content = ob_get_clean();
echo $content;
?>
