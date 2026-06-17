<?php
AuthMiddleware::checkAuth();

$pageTitle = 'Управление категориями';
$error = $error ?? null;
$categories = (new Category())->getAll();

ob_start();
?>

<h1>📂 Управление категориями</h1>

<a href="/admin/categories/create" class="btn btn-primary" style="margin-bottom: 1.5rem;">➕ Создать категорию</a>

<?php if (!empty($error)): ?>
    <div style="padding: 1rem; background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; border-radius: var(--radius-md); margin-bottom: 1.5rem;">
        ❌ <?= Security::escape($error) ?>
    </div>
<?php endif; ?>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Название</th>
                <th>Описание</th>
                <th style="text-align: center;">Постов</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $printCategory = function($categories, $level = 0) use (&$printCategory) {
                foreach ($categories as $category) {
                    ?>
                    <tr>
                        <td>
                            <div style="margin-left: <?= ($level * 20) ?>px;">
                                <?= str_repeat('↳ ', $level) . Security::escape($category['name']) ?>
                            </div>
                        </td>
                        <td style="color: var(--text-light);">
                            <?= Security::escape(substr($category['description'] ?? '', 0, 50)) ?>
                        </td>
                        <td style="text-align: center;">
                            <span style="display: inline-block; padding: 0.25rem 0.75rem; background: var(--light); border-radius: var(--radius-sm);">
                                <?= $category['post_count'] ?? 0 ?>
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                <a href="/admin/categories/<?= $category['id'] ?>/edit" class="btn btn-sm btn-primary">Редактировать</a>
                                <a href="/admin/categories/<?= $category['id'] ?>/delete" class="btn btn-sm btn-danger" onclick="return confirm('Удалить?');">Удалить</a>
                            </div>
                        </td>
                    </tr>
                    <?php
                    if (!empty($category['children'])) {
                        $printCategory($category['children'], $level + 1);
                    }
                }
            };
            $printCategory($categories);
            ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../../layouts/admin.php';
?>
            ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../../layouts/admin.php';
?>
