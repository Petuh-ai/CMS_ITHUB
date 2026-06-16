<?php
/**
 * Модель Category
 */

class Category
{
    protected $db;
    protected $table = 'categories';

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function create($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->update($this->table, $data, 'id = ?', [$id]);
    }

    public function delete($id)
    {
        // Проверяем, нет ли постов в этой категории
        $posts = $this->db->fetchOne(
            "SELECT COUNT(*) as count FROM posts WHERE category_id = ?",
            [$id]
        );

        if ($posts['count'] > 0) {
            throw new Exception('Нельзя удалить категорию с постами');
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

    public function getAll()
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} ORDER BY parent_id, name ASC"
        );
    }

    public function getHierarchy()
    {
        $categories = $this->getAll();
        return $this->buildTree($categories);
    }

    private function buildTree($categories, $parentId = null)
    {
        $result = [];

        foreach ($categories as $category) {
            if ($category['parent_id'] == $parentId) {
                $category['children'] = $this->buildTree($categories, $category['id']);
                $result[] = $category;
            }
        }

        return $result;
    }

    public function getChildren($parentId)
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE parent_id = ? ORDER BY name ASC",
            [$parentId]
        );
    }

    public function getParents()
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE parent_id IS NULL ORDER BY name ASC"
        );
    }

    public function getWithPostCount()
    {
        return $this->db->fetchAll(
            "SELECT c.*, COUNT(p.id) as post_count
             FROM {$this->table} c
             LEFT JOIN posts p ON c.id = p.category_id AND p.status = 'published'
             GROUP BY c.id
             ORDER BY c.name ASC"
        );
    }

    public function count()
    {
        $result = $this->db->fetchOne("SELECT COUNT(*) as count FROM {$this->table}");
        return $result['count'] ?? 0;
    }
}
