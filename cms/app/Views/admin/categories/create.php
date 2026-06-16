<?php
$pageTitle = 'Создать категорию';
ob_start();
?>

<div class="form-page">
    <h2>Создать новую категорию</h2>

    <form method="POST" action="/admin/categories" class="form-full">
        <input type="hidden" name="csrf_token" value="<?php echo Security::generateCSRFToken(); ?>">

        <div class="form-group">
            <label for="name">Название:</label>
            <input type="text" id="name" name="name" required 
                   value="<?php echo Security::escape($_POST['name'] ?? ''); ?>">
            <?php if (!empty($errors['name'])): ?>
                <span class="error"><?php echo Security::escape($errors['name'][0]); ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="parent_id">Родительская категория:</label>
            <select id="parent_id" name="parent_id">
                <option value="">Нет</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>"
                        <?php echo (isset($_POST['parent_id']) && $_POST['parent_id'] == $category['id']) ? 'selected' : ''; ?>>
                        <?php echo Security::escape($category['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="description">Описание:</label>
            <textarea id="description" name="description" rows="5"><?php echo Security::escape($_POST['description'] ?? ''); ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Создать</button>
            <a href="/admin/categories" class="btn">Отмена</a>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../../layouts/admin.php';
?>
