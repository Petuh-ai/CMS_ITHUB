<?php
AuthMiddleware::checkAuth();

$pageTitle = 'Создать категорию';
$errors = $errors ?? [];
$categories = (new Category())->getAll();

ob_start();
?>

<h1>📂 Создать категорию</h1>

<div class="card" style="max-width: 600px;">
    <form method="POST" action="/admin/categories">
        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">

        <!-- НАЗВАНИЕ -->
        <div style="margin-bottom: 1.5rem;">
            <label for="name" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Название *</label>
            <input type="text" id="name" name="name" required 
                   value="<?= Security::escape($_POST['name'] ?? '') ?>"
                   style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: var(--radius-md);">
            <?php if (!empty($errors['name'])): ?>
                <div style="color: var(--danger); font-size: 0.875rem; margin-top: 0.25rem;">
                    <?= Security::escape($errors['name'][0]) ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- ОПИСАНИЕ -->
        <div style="margin-bottom: 1.5rem;">
            <label for="description" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Описание</label>
            <textarea id="description" name="description" rows="4"
                      style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: var(--radius-md);">
<?= Security::escape($_POST['description'] ?? '') ?></textarea>
        </div>

        <!-- КНОПКИ -->
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <button type="submit" class="btn btn-primary">✅ Создать</button>
            <a href="/admin/categories" class="btn btn-secondary">↩️ Отмена</a>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../../layouts/admin.php';
?>