<?php
$pageTitle = 'Редактировать категорию';
ob_start();
?>

<div class="form-page">
    <h2>Редактировать категорию</h2>

    <form method="POST" action="/admin/categories/<?php echo $category['id']; ?>" class="form-full">
        <input type="hidden" name="csrf_token" value="<?php echo Security::generateCSRFToken(); ?>">

        <div class="form-group">
            <label for="name">Название:</label>
            <input type="text" id="name" name="name" required 
                   value="<?php echo Security::escape($_POST['name'] ?? $category['name']); ?>">
            <?php if (!empty($errors['name'])): ?>
                <span class="error"><?php echo Security::escape($errors['name'][0]); ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="parent_id">Родительская категория:</label>
            <select id="parent_id" name="parent_id">
                <option value="">Нет</option>
                <?php foreach ($categories as $cat): ?>
                    <?php if ($cat['id'] !== $category['id']): ?>
                        <option value="<?php echo $cat['id']; ?>"
                            <?php echo ($category['parent_id'] == $cat['id']) ? 'selected' : ''; ?>>
                            <?php echo Security::escape($cat['name']); ?>
                        </option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="description">Описание:</label>
            <textarea id="description" name="description" rows="5"><?php echo Security::escape($_POST['description'] ?? $category['description']); ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Сохранить</button>
            <a href="/admin/categories" class="btn">Отмена</a>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../../layouts/admin.php';
?>
