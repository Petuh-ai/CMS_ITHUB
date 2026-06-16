<?php
/**
 * Модель Settings
 */

class Settings
{
    protected $db;
    protected $table = 'settings';
    private static $cache = [];

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->loadSettings();
    }

    public function get($key, $default = null)
    {
        return self::$cache[$key] ?? $default;
    }

    public function set($key, $value)
    {
        // Проверяем, существует ли уже этот ключ
        $existing = $this->db->fetchOne(
            "SELECT id FROM {$this->table} WHERE setting_key = ? LIMIT 1",
            [$key]
        );

        if ($existing) {
            $this->db->update($this->table, ['value' => $value], 'setting_key = ?', [$key]);
        } else {
            $this->db->insert($this->table, ['setting_key' => $key, 'value' => $value]);
        }

        self::$cache[$key] = $value;
        Logger::info("Setting updated: $key");
    }

    public function getAll()
    {
        return self::$cache;
    }

    private function loadSettings()
    {
        $settings = $this->db->fetchAll("SELECT * FROM {$this->table}");
        foreach ($settings as $setting) {
            if (!isset($setting['setting_key'])) {
                continue;
            }
            self::$cache[$setting['setting_key']] = $setting['value'] ?? null;
        }
    }

    public function getSiteTitle()
    {
        return $this->get('site_title', 'CMS System');
    }

    public function getSiteDescription()
    {
        return $this->get('site_description', '');
    }

    public function getSiteKeywords()
    {
        return $this->get('site_keywords', '');
    }
}
