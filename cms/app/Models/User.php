<?php
/**
 * Модель User
 */

class User
{
    protected $db;
    protected $table = 'users';

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function create($data)
    {
        $data['password'] = Security::hashPassword($data['password']);
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Security::hashPassword($data['password']);
        }
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->update($this->table, $data, 'id = ?', [$id]);
    }

    public function delete($id)
    {
        $this->db->delete($this->table, 'id = ?', [$id]);
    }

    public function findById($id)
    {
        return $this->db->fetchOne(
            "SELECT * FROM {$this->table} WHERE id = ? LIMIT 1",
            [$id]
        );
    }

    public function findByEmail($email)
    {
        return $this->db->fetchOne(
            "SELECT * FROM {$this->table} WHERE email = ? LIMIT 1",
            [$email]
        );
    }

    public function getAll($limit = null, $offset = 0)
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        if ($limit) {
            $sql .= " LIMIT ? OFFSET ?";
            return $this->db->fetchAll($sql, [$limit, $offset]);
        }
        return $this->db->fetchAll($sql);
    }

    public function getByRole($role)
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE role = ? ORDER BY name ASC",
            [$role]
        );
    }

    public function verifyPassword($userId, $password)
    {
        $user = $this->findById($userId);
        if (!$user) {
            return false;
        }
        return Security::verifyPassword($password, $user['password']);
    }

    public function count()
    {
        $result = $this->db->fetchOne("SELECT COUNT(*) as count FROM {$this->table}");
        return $result['count'] ?? 0;
    }

    public function ensureDefaultAdmin()
    {
        $existing = $this->findByEmail('admin@example.com');
        if (!$existing) {
            $this->create([
                'name' => 'Администратор',
                'email' => 'admin@example.com',
                'password' => 'admin123',
                'role' => 'admin',
                'status' => 'active',
            ]);

            Logger::info('Default admin user created: admin@example.com / admin123');
            return;
        }

        if (!$this->verifyPassword($existing['id'], 'admin123')) {
            $this->update($existing['id'], ['password' => 'admin123']);
            Logger::warning('Default admin password reset for admin@example.com');
        }
    }
}
