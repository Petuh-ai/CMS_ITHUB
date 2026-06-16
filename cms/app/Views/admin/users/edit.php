<?php
$pageTitle = 'Редактировать пользователя';
ob_start();
?>

<div class="form-page">
    <h2>Редактировать пользователя</h2>

    <form method="POST" action="/admin/users/<?php echo $user['id']; ?>" class="form-full">
        <input type="hidden" name="csrf_token" value="<?php echo Security::generateCSRFToken(); ?>">

        <div class="form-group">
            <label for="name">Имя:</label>
            <input type="text" id="name" name="name" required 
                   value="<?php echo Security::escape($_POST['name'] ?? $user['name']); ?>">
            <?php if (!empty($errors['name'])): ?>
                <span class="error"><?php echo Security::escape($errors['name'][0]); ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required 
                   value="<?php echo Security::escape($_POST['email'] ?? $user['email']); ?>">
            <?php if (!empty($errors['email'])): ?>
                <span class="error"><?php echo Security::escape($errors['email'][0]); ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="password">Новый пароль (оставьте пустым, если не хотите менять):</label>
            <input type="password" id="password" name="password">
        </div>

        <div class="form-group">
            <label for="role">Роль:</label>
            <select id="role" name="role" required>
                <option value="author" <?php echo ($user['role'] === 'author') ? 'selected' : ''; ?>>Автор</option>
                <option value="editor" <?php echo ($user['role'] === 'editor') ? 'selected' : ''; ?>>Редактор</option>
                <option value="admin" <?php echo ($user['role'] === 'admin') ? 'selected' : ''; ?>>Администратор</option>
            </select>
        </div>

        <div class="form-group">
            <label for="status">Статус:</label>
            <select id="status" name="status">
                <option value="active" <?php echo ($user['status'] === 'active') ? 'selected' : ''; ?>>Активен</option>
                <option value="inactive" <?php echo ($user['status'] === 'inactive') ? 'selected' : ''; ?>>Неактивен</option>
                <option value="suspended" <?php echo ($user['status'] === 'suspended') ? 'selected' : ''; ?>>Заморожен</option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Сохранить</button>
            <a href="/admin/users" class="btn">Отмена</a>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../../layouts/admin.php';
?>
