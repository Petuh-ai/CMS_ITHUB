<?php
AuthMiddleware::checkAuth();

$pageTitle = 'Создать пост';
$errors = $errors ?? [];
$categories = (new Category())->getAll();

ob_start();
?>

<h1>✍️ Создать новый пост</h1>

<div class="card" style="margin-bottom: 2rem;">
    <form method="POST" action="/admin/posts" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">

        <!-- НАЗВАНИЕ -->
        <div style="margin-bottom: 1.5rem;">
            <label for="title" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Название *</label>
            <input type="text" id="title" name="title" required 
                   value="<?= Security::escape($_POST['title'] ?? '') ?>"
                   style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: var(--radius-md); font-size: 1rem;">
            <?php if (!empty($errors['title'])): ?>
                <div style="color: var(--danger); font-size: 0.875rem; margin-top: 0.25rem;">
                    <?= Security::escape($errors['title'][0]) ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- КАТЕГОРИЯ -->
        <div style="margin-bottom: 1.5rem;">
            <label for="category_id" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Категория *</label>
            <select id="category_id" name="category_id" required
                    style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: var(--radius-md); font-size: 1rem;">
                <option value="">Выберите категорию</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>"
                        <?= (isset($_POST['category_id']) && $_POST['category_id'] == $category['id']) ? 'selected' : '' ?>>
                        <?= str_repeat('→ ', $category['depth'] ?? 0) . Security::escape($category['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- КРАТКОЕ ОПИСАНИЕ -->
        <div style="margin-bottom: 1.5rem;">
            <label for="excerpt" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Краткое описание</label>
            <textarea id="excerpt" name="excerpt" rows="3"
                      style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: var(--radius-md); font-size: 1rem;">
<?= Security::escape($_POST['excerpt'] ?? '') ?></textarea>
        </div>

        <!-- СОДЕРЖАНИЕ -->
        <div style="margin-bottom: 1.5rem;">
            <label for="content" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Содержание *</label>
            <textarea id="content" name="content" required rows="15"
                      style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: var(--radius-md); font-size: 1rem; font-family: monospace;">
<?= Security::escape($_POST['content'] ?? '') ?></textarea>
            <?php if (!empty($errors['content'])): ?>
                <div style="color: var(--danger); font-size: 0.875rem; margin-top: 0.25rem;">
                    <?= Security::escape($errors['content'][0]) ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- СТАТУС -->
        <div style="margin-bottom: 1.5rem;">
            <label for="status" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Статус</label>
            <select id="status" name="status"
                    style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: var(--radius-md); font-size: 1rem;">
                <option value="draft" <?= (($_POST['status'] ?? '') === 'draft') ? 'selected' : '' ?>>📝 Черновик</option>
                <option value="published" <?= (($_POST['status'] ?? '') === 'published') ? 'selected' : '' ?>>✅ Опубликовано</option>
            </select>
        </div>

        <!-- КНОПКИ -->
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <button type="submit" class="btn btn-primary">✅ Создать пост</button>
            <a href="/admin/posts" class="btn btn-secondary">↩️ Отмена</a>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../../layouts/admin.php';
?>
