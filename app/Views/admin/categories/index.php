<?php
$pageTitle = 'Категории';
ob_start();
?>

<div class="categories-page">
    <div class="page-header">
        <h2>Категории</h2>
        <a href="/admin/categories/create" class="btn btn-primary">Создать категорию</a>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo Security::escape($error); ?></div>
    <?php endif; ?>

    <table class="table">
        <thead>
            <tr>
                <th>Название</th>
                <th>Описание</th>
                <th>Постов</th>
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
                            <span style="margin-left: <?php echo ($level * 20); ?>px;">
                                <?php echo Security::escape($category['name']); ?>
                            </span>
                        </td>
                        <td><?php echo Security::escape(substr($category['description'] ?? '', 0, 50)); ?></td>
                        <td><?php echo $category['post_count'] ?? 0; ?></td>
                        <td>
                            <a href="/admin/categories/<?php echo $category['id']; ?>/edit" class="btn-link">Редактировать</a>
                            <a href="/admin/categories/<?php echo $category['id']; ?>/delete" class="btn-link btn-danger"
                               onclick="return confirm('Вы уверены?');">Удалить</a>
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
