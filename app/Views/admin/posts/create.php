<?php
$pageTitle = 'Создать пост';
ob_start();
?>

<div class="form-page">
    <h2>Создать новый пост</h2>

    <form method="POST" action="/admin/posts" class="form-full">
        <input type="hidden" name="csrf_token" value="<?php echo Security::generateCSRFToken(); ?>">

        <div class="form-group">
            <label for="title">Название:</label>
            <input type="text" id="title" name="title" required 
                   value="<?php echo Security::escape($_POST['title'] ?? ''); ?>">
            <?php if (!empty($errors['title'])): ?>
                <span class="error"><?php echo Security::escape($errors['title'][0]); ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="category_id">Категория:</label>
            <select id="category_id" name="category_id" required>
                <option value="">Выберите категорию</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>"
                        <?php echo (isset($_POST['category_id']) && $_POST['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                        <?php echo str_repeat('— ', $category['depth'] ?? 0) . Security::escape($category['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="excerpt">Краткое описание:</label>
            <textarea id="excerpt" name="excerpt" rows="3"><?php echo Security::escape($_POST['excerpt'] ?? ''); ?></textarea>
        </div>

        <div class="form-group">
            <label for="content">Содержание:</label>
            <textarea id="content" name="content" required rows="15" class="editor">
<?php echo Security::escape($_POST['content'] ?? ''); ?></textarea>
            <?php if (!empty($errors['content'])): ?>
                <span class="error"><?php echo Security::escape($errors['content'][0]); ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="status">Статус:</label>
            <select id="status" name="status">
                <option value="draft" <?php echo (($_POST['status'] ?? '') === 'draft') ? 'selected' : ''; ?>>Черновик</option>
                <option value="published" <?php echo (($_POST['status'] ?? '') === 'published') ? 'selected' : ''; ?>>Опубликовано</option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Создать</button>
            <a href="/admin/posts" class="btn">Отмена</a>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../../layouts/admin.php';
?>
