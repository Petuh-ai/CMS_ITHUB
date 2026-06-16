<?php
$pageTitle = 'Создать пользователя';
ob_start();
?>

<div class="form-page">
    <h2>Добавить нового пользователя</h2>

    <form method="POST" action="/admin/users" class="form-full">
        <input type="hidden" name="csrf_token" value="<?php echo Security::generateCSRFToken(); ?>">

        <div class="form-group">
            <label for="name">Имя:</label>
            <input type="text" id="name" name="name" required 
                   value="<?php echo Security::escape($_POST['name'] ?? ''); ?>">
            <?php if (!empty($errors['name'])): ?>
                <span class="error"><?php echo Security::escape($errors['name'][0]); ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required 
                   value="<?php echo Security::escape($_POST['email'] ?? ''); ?>">
            <?php if (!empty($errors['email'])): ?>
                <span class="error"><?php echo Security::escape($errors['email'][0]); ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password" required>
            <?php if (!empty($errors['password'])): ?>
                <span class="error"><?php echo Security::escape($errors['password'][0]); ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="role">Роль:</label>
            <select id="role" name="role" required>
                <option value="">Выберите роль</option>
                <option value="author">Автор</option>
                <option value="editor">Редактор</option>
                <option value="admin">Администратор</option>
            </select>
            <?php if (!empty($errors['role'])): ?>
                <span class="error"><?php echo Security::escape($errors['role'][0]); ?></span>
            <?php endif; ?>
        </div>

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
