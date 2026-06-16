<?php
$pageTitle = 'Пользователи';
ob_start();
?>

<div class="users-page">
    <div class="page-header">
        <h2>Пользователи</h2>
        <a href="/admin/users/create" class="btn btn-primary">Добавить пользователя</a>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Имя</th>
                <th>Email</th>
                <th>Роль</th>
                <th>Статус</th>
                <th>Дата регистрации</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo Security::escape($user['name']); ?></td>
                    <td><?php echo Security::escape($user['email']); ?></td>
                    <td><?php echo Security::escape($user['role']); ?></td>
                    <td>
                        <span class="badge badge-<?php echo $user['status']; ?>">
                            <?php echo $user['status'] === 'active' ? 'Активен' : 'Неактивен'; ?>
                        </span>
                    </td>
                    <td><?php echo date('d.m.Y', strtotime($user['created_at'])); ?></td>
                    <td>
                        <a href="/admin/users/<?php echo $user['id']; ?>/edit" class="btn-link">Редактировать</a>
                        <a href="/admin/users/<?php echo $user['id']; ?>/delete" class="btn-link btn-danger"
                           onclick="return confirm('Вы уверены?');">Удалить</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Пагинация -->
    <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <?php if ($i == $page): ?>
                    <span class="current"><?php echo $i; ?></span>
                <?php else: ?>
                    <a href="/admin/users?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../../layouts/admin.php';
?>
