<?php $pageTitle = 'Восстановление пароля'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo Security::escape($pageTitle); ?> - CMS</title>
    <link rel="stylesheet" href="/css/auth.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-box">
            <h1>Восстановление пароля</h1>

            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success">
                    <?php echo Security::escape($success_message); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach ($errors as $field => $messages): ?>
                            <?php foreach ($messages as $message): ?>
                                <li><?php echo Security::escape($message); ?></li>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="/forgot-password">
                <input type="hidden" name="csrf_token" value="<?php echo Security::generateCSRFToken(); ?>">

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required 
                           value="<?php echo Security::escape($_POST['email'] ?? ''); ?>">
                    <small>Введите email, связанный с вашим аккаунтом</small>
                </div>

                <button type="submit" class="btn btn-primary">Отправить ссылку</button>
            </form>

            <p class="form-footer">
                <a href="/login">Вернуться к входу</a>
            </p>
        </div>
    </div>
</body>
</html>
