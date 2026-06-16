<?php
/**
 * Модель Media
 */

class Media
{
    protected $db;
    protected $table = 'media';
    protected $uploadsDir;
    protected $config;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->config = require __DIR__ . '/../../config/app.php';
        $this->uploadsDir = __DIR__ . '/../../public/uploads/';
    }

    public function upload($file)
    {
        // Проверяем размер
        if ($file['size'] > $this->config['uploads']['max_size']) {
            throw new Exception('Размер файла превышает допустимый лимит');
        }

        // Проверяем MIME тип
        if (!Security::isValidMimeType($file['tmp_name'], $this->config['uploads']['allowed_mimes'])) {
            throw new Exception('Недопустимый тип файла');
        }

        // Проверяем расширение
        if (!Security::isValidExtension($file['name'], $this->config['uploads']['allowed_extensions'])) {
            throw new Exception('Недопустимое расширение файла');
        }

        // Генерируем уникальное имя файла
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . time() . '.' . $ext;
        $filepath = $this->uploadsDir . $filename;

        // Загружаем файл
        if (!move_uploaded_file($file['tmp_name'], $filepath)) {
            throw new Exception('Ошибка при загрузке файла');
        }

        // Сохраняем информацию в БД
        $data = [
            'filename' => $filename,
            'original_name' => Security::sanitizeFilename($file['name']),
            'mime_type' => $file['type'],
            'size' => $file['size'],
            'uploaded_by' => $_SESSION['user_id'] ?? null,
            'uploaded_at' => date('Y-m-d H:i:s'),
        ];

        return $this->db->insert($this->table, $data);
    }

    public function delete($id)
    {
        $media = $this->findById($id);
        if (!$media) {
            throw new Exception('Файл не найден');
        }

        $filepath = $this->uploadsDir . $media['filename'];
        if (file_exists($filepath)) {
            unlink($filepath);
        }

        $this->db->delete($this->table, 'id = ?', [$id]);
    }

    public function findById($id)
    {
        return $this->db->fetchOne(
            "SELECT * FROM {$this->table} WHERE id = ? LIMIT 1",
            [$id]
        );
    }

    public function getAll($limit = null, $offset = 0)
    {
        $sql = "SELECT m.*, u.name as uploader_name
                FROM {$this->table} m
                LEFT JOIN users u ON m.uploaded_by = u.id
                ORDER BY m.uploaded_at DESC";

        if ($limit) {
            $sql .= " LIMIT ? OFFSET ?";
            return $this->db->fetchAll($sql, [$limit, $offset]);
        }

        return $this->db->fetchAll($sql);
    }

    public function getByType($type, $limit = null, $offset = 0)
    {
        $sql = "SELECT * FROM {$this->table}
                WHERE mime_type LIKE ?
                ORDER BY uploaded_at DESC";

        if ($limit) {
            $sql .= " LIMIT ? OFFSET ?";
            return $this->db->fetchAll($sql, [$type . '%', $limit, $offset]);
        }

        return $this->db->fetchAll($sql, [$type . '%']);
    }

    public function getByUploader($uploaderId, $limit = null, $offset = 0)
    {
        $sql = "SELECT * FROM {$this->table}
                WHERE uploaded_by = ?
                ORDER BY uploaded_at DESC";

        if ($limit) {
            $sql .= " LIMIT ? OFFSET ?";
            return $this->db->fetchAll($sql, [$uploaderId, $limit, $offset]);
        }

        return $this->db->fetchAll($sql, [$uploaderId]);
    }

    public function count()
    {
        $result = $this->db->fetchOne("SELECT COUNT(*) as count FROM {$this->table}");
        return $result['count'] ?? 0;
    }
}
