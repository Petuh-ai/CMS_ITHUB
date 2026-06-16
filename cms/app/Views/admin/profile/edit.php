<?php
$pageTitle = 'Профиль';
ob_start();
?>

<div class="profile-page">
    <h2>Мой профиль</h2>

    <form method="POST" action="/admin/profile" class="form-full">
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

        <fieldset>
            <legend>Изменить пароль</legend>

            <div class="form-group">
                <label for="current_password">Текущий пароль:</label>
                <input type="password" id="current_password" name="current_password">
                <?php if (!empty($errors['current_password'])): ?>
                    <span class="error"><?php echo Security::escape($errors['current_password'][0]); ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="password">Новый пароль:</label>
                <input type="password" id="password" name="password">
            </div>

            <div class="form-group">
                <label for="password_confirmation">Подтвердите пароль:</label>
                <input type="password" id="password_confirmation" name="password_confirmation">
            </div>
        </fieldset>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Сохранить</button>
            <a href="/admin/dashboard" class="btn">Отмена</a>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../../layouts/admin.php';
?>
