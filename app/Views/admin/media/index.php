<?php
AuthMiddleware::checkAuth();

$pageTitle = 'Медиабиблиотека';
$mediaModel = new Media();
$media = $mediaModel->getAll(12) ?? [];

ob_start();
?>

<h1>🖼️ Медиабиблиотека</h1>

<div class="card" style="margin-bottom: 2rem;">
    <h3 style="margin-bottom: 1rem;">📤 Загрузить файл</h3>
    <form action="/admin/media/upload" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
        <div style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: end;">
            <div style="flex: 1; min-width: 250px;">
                <input type="file" name="file" accept="image/*" required
                       style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: var(--radius-md);">
            </div>
            <button type="submit" class="btn btn-primary">Загрузить</button>
        </div>
    </form>
</div>

<?php if (!empty($media)): ?>
    <div class="card">
        <h3 style="margin-bottom: 1.5rem;">Загруженные файлы</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 1.5rem;">
            <?php foreach ($media as $item): ?>
                <div style="text-align: center; padding: 1rem; border: 1px solid var(--border); border-radius: var(--radius-md);">
                    <?php if (strpos($item['mime_type'], 'image') !== false): ?>
                        <img src="/uploads/<?= Security::escape($item['filename']) ?>" alt="<?= Security::escape($item['original_name']) ?>"
                             style="width: 100%; height: 150px; object-fit: cover; border-radius: var(--radius-md); margin-bottom: 0.5rem;">
                    <?php else: ?>
                        <div style="width: 100%; height: 150px; background: var(--light); display: flex; align-items: center; justify-content: center; border-radius: var(--radius-md); margin-bottom: 0.5rem; font-size: 2rem;">
                            📄
                        </div>
                    <?php endif; ?>
                    <p style="font-size: 0.875rem; margin: 0.5rem 0;">
                        <?= Security::escape(substr($item['original_name'], 0, 20)) ?>
                    </p>
                    <small style="color: var(--text-light); display: block; margin-bottom: 0.5rem;">
                        <?= round($item['size'] / 1024) ?> KB
                    </small>
                    <a href="/admin/media/<?= $item['id'] ?>/delete" class="btn btn-sm btn-danger" onclick="return confirm('Удалить?');">Удалить</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php else: ?>
    <div class="card" style="text-align: center; padding: 3rem;">
        <p style="color: var(--text-light); font-size: 1.1rem;">Нет загруженных файлов</p>
    </div>
<?php endif; ?>

<?php
$content = ob_get_clean();
require __DIR__ . '/../../layouts/admin.php';
?>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</div>

<script>
function deleteMedia(id) {
    if (!confirm('Удалить файл?')) return;
    
    fetch('/admin/media/' + id + '/delete', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'}
    }).then(() => location.reload());
}

const uploadArea = document.getElementById('uploadArea');
uploadArea?.addEventListener('click', () => document.getElementById('fileInput').click());
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../../layouts/admin.php';
?>
