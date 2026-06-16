<?php
$pageTitle = 'Настройки';
ob_start();
?>

<div class="settings-page">
    <h2>Настройки системы</h2>

    <form method="POST" action="/admin/settings" class="form-full">
        <input type="hidden" name="csrf_token" value="<?php echo Security::generateCSRFToken(); ?>">

        <fieldset>
            <legend>Основные настройки</legend>

            <div class="form-group">
                <label for="site_title">Название сайта:</label>
                <input type="text" id="site_title" name="site_title" 
                       value="<?php echo Security::escape($settings['site_title'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="site_description">Описание:</label>
                <textarea id="site_description" name="site_description" rows="5"><?php echo Security::escape($settings['site_description'] ?? ''); ?></textarea>
            </div>

            <div class="form-group">
                <label for="site_keywords">Ключевые слова:</label>
                <input type="text" id="site_keywords" name="site_keywords" 
                       value="<?php echo Security::escape($settings['site_keywords'] ?? ''); ?>">
            </div>
        </fieldset>

        <fieldset>
            <legend>Комментарии</legend>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="enable_comments" value="1"
                        <?php echo (isset($settings['enable_comments']) && $settings['enable_comments']) ? 'checked' : ''; ?>>
                    Включить комментарии
                </label>
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="comments_moderation" value="1"
                        <?php echo (isset($settings['comments_moderation']) && $settings['comments_moderation']) ? 'checked' : ''; ?>>
                    Модерация комментариев
                </label>
            </div>
        </fieldset>

        <fieldset>
            <legend>Пагинация</legend>

            <div class="form-group">
                <label for="posts_per_page">Постов на странице:</label>
                <input type="number" id="posts_per_page" name="posts_per_page" min="1" 
                       value="<?php echo Security::escape($settings['posts_per_page'] ?? 10); ?>">
            </div>
        </fieldset>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>
    </form>

    <hr>

    <div class="backup-section">
        <h3>Резервное копирование</h3>
        <p>Создайте резервную копию базы данных</p>
        <a href="/admin/settings/backup" class="btn btn-secondary">Скачать резервную копию</a>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../../layouts/admin.php';
?>
