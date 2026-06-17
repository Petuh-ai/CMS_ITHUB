<?php
AuthMiddleware::checkAuth();

$pageTitle = 'Создать пользователя';
$errors = $errors ?? [];

ob_start();
?>

<h1>👤 Добавить пользователя</h1>

<div class="card" style="max-width: 600px;">
    <form method="POST" action="/admin/users">
        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">

        <!-- ИМЯ -->
        <div style="margin-bottom: 1.5rem;">
            <label for="name" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Имя *</label>
            <input type="text" id="name" name="name" required 
                   value="<?= Security::escape($_POST['name'] ?? '') ?>"
                   style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: var(--radius-md);">
            <?php if (!empty($errors['name'])): ?>
                <div style="color: var(--danger); font-size: 0.875rem; margin-top: 0.25rem;">
                    <?= Security::escape($errors['name'][0]) ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- EMAIL -->
        <div style="margin-bottom: 1.5rem;">
            <label for="email" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Email *</label>
            <input type="email" id="email" name="email" required 
                   value="<?= Security::escape($_POST['email'] ?? '') ?>"
                   style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: var(--radius-md);">
            <?php if (!empty($errors['email'])): ?>
                <div style="color: var(--danger); font-size: 0.875rem; margin-top: 0.25rem;">
                    <?= Security::escape($errors['email'][0]) ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- ПАРОЛЬ -->
        <div style="margin-bottom: 1.5rem;">
            <label for="password" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Пароль *</label>
            <input type="password" id="password" name="password" required
                   style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: var(--radius-md);">
            <?php if (!empty($errors['password'])): ?>
                <div style="color: var(--danger); font-size: 0.875rem; margin-top: 0.25rem;">
                    <?= Security::escape($errors['password'][0]) ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- РОЛЬ -->
        <div style="margin-bottom: 1.5rem;">
            <label for="role" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Роль *</label>
            <select id="role" name="role" required
                    style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: var(--radius-md);">
                <option value="">Выберите роль</option>
                <option value="user" <?= (($_POST['role'] ?? '') === 'user') ? 'selected' : '' ?>>📝 Автор</option>
                <option value="editor" <?= (($_POST['role'] ?? '') === 'editor') ? 'selected' : '' ?>>✏️ Редактор</option>
                <option value="admin" <?= (($_POST['role'] ?? '') === 'admin') ? 'selected' : '' ?>>👑 Администратор</option>
            </select>
            <?php if (!empty($errors['role'])): ?>
                <div style="color: var(--danger); font-size: 0.875rem; margin-top: 0.25rem;">
                    <?= Security::escape($errors['role'][0]) ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- КНОПКИ -->
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <button type="submit" class="btn btn-primary">✅ Создать</button>
            <a href="/admin/users" class="btn btn-secondary">↩️ Отмена</a>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../../layouts/admin.php';
?>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Создать</button>
            <a href="/admin/users" class="btn">Отмена</a>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../../layouts/admin.php';
?>
