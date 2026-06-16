<?php
$pageTitle = 'Медиабиблиотека';
ob_start();
?>

<div class="media-page">
    <div class="page-header">
        <h2>Медиабиблиотека</h2>
    </div>

    <div class="media-upload">
        <form id="uploadForm" action="/admin/media/upload" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo Security::generateCSRFToken(); ?>">
            <div class="upload-area" id="uploadArea">
                <input type="file" id="fileInput" name="file" multiple accept="image/*,video/*,.pdf" style="display: none;">
                <p>Перетащите файлы сюда или нажмите для выбора</p>
            </div>
        </form>
    </div>

    <div class="media-grid" id="mediaGrid">
        <?php foreach ($media as $item): ?>
            <div class="media-item" data-id="<?php echo $item['id']; ?>">
                <div class="media-preview">
                    <?php if (strpos($item['mime_type'], 'image') !== false): ?>
                        <img src="/uploads/<?php echo Security::escape($item['filename']); ?>" alt="">
                    <?php elseif (strpos($item['mime_type'], 'video') !== false): ?>
                        <div class="video-icon">Video</div>
                    <?php else: ?>
                        <div class="file-icon">📄</div>
                    <?php endif; ?>
                </div>
                <div class="media-info">
                    <p class="filename"><?php echo Security::escape($item['original_name']); ?></p>
                    <small><?php echo round($item['size'] / 1024) . ' KB'; ?></small>
                </div>
                <button type="button" class="btn-delete" onclick="deleteMedia(<?php echo $item['id']; ?>)">Удалить</button>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Пагинация -->
    <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <?php if ($i == $page): ?>
                    <span class="current"><?php echo $i; ?></span>
                <?php else: ?>
                    <a href="/admin/media?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                <?php endif; ?>
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
