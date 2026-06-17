<?php
/**
 * Класс для инициализации базы данных
 */

class DatabaseMigration
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function migrate()
    {
        try {
            $this->createUsersTable();
            $this->createCategoriesTable();
            $this->createPostsTable();
            $this->createMediaTable();
            $this->createCommentsTable();
            $this->createSettingsTable();
            
            Logger::info('Database migration completed successfully');
            return true;
        } catch (Exception $e) {
            Logger::error('Database migration failed: ' . $e->getMessage());
            return false;
        }
    }

    private function createUsersTable()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS `users` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(100) NOT NULL,
                `email` VARCHAR(100) UNIQUE NOT NULL,
                `password` VARCHAR(255) NOT NULL,
                `role` ENUM('admin', 'editor', 'author') DEFAULT 'author',
                `status` ENUM('active', 'inactive') DEFAULT 'active',
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX `idx_email` (`email`),
                INDEX `idx_role` (`role`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        
        try {
            $this->db->query($sql);
            Logger::info('Table `users` created or already exists');
        } catch (Exception $e) {
            Logger::warning('Could not create users table: ' . $e->getMessage());
        }
    }

    private function createCategoriesTable()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS `categories` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(100) NOT NULL,
                `slug` VARCHAR(100) UNIQUE NOT NULL,
                `description` TEXT,
                `parent_id` INT,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX `idx_slug` (`slug`),
                INDEX `idx_parent_id` (`parent_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        
        try {
            $this->db->query($sql);
            Logger::info('Table `categories` created or already exists');
        } catch (Exception $e) {
            Logger::warning('Could not create categories table: ' . $e->getMessage());
        }
    }

    private function createPostsTable()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS `posts` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `title` VARCHAR(255) NOT NULL,
                `slug` VARCHAR(255) UNIQUE NOT NULL,
                `content` LONGTEXT NOT NULL,
                `excerpt` TEXT,
                `category_id` INT,
                `author_id` INT NOT NULL,
                `status` ENUM('draft', 'published') DEFAULT 'draft',
                `featured_image_id` INT,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `published_at` TIMESTAMP NULL,
                INDEX `idx_slug` (`slug`),
                INDEX `idx_status` (`status`),
                INDEX `idx_author_id` (`author_id`),
                INDEX `idx_category_id` (`category_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        
        try {
            $this->db->query($sql);
            Logger::info('Table `posts` created or already exists');
        } catch (Exception $e) {
            Logger::warning('Could not create posts table: ' . $e->getMessage());
        }
    }

    private function createMediaTable()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS `media` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `filename` VARCHAR(255) NOT NULL,
                `original_name` VARCHAR(255) NOT NULL,
                `mime_type` VARCHAR(100),
                `size` INT,
                `uploaded_by` INT NOT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX `idx_uploaded_by` (`uploaded_by`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        
        try {
            $this->db->query($sql);
            Logger::info('Table `media` created or already exists');
        } catch (Exception $e) {
            Logger::warning('Could not create media table: ' . $e->getMessage());
        }
    }

    private function createCommentsTable()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS `comments` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `post_id` INT NOT NULL,
                `author_id` INT,
                `author_name` VARCHAR(100),
                `author_email` VARCHAR(100),
                `content` TEXT NOT NULL,
                `status` ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX `idx_post_id` (`post_id`),
                INDEX `idx_status` (`status`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        
        try {
            $this->db->query($sql);
            Logger::info('Table `comments` created or already exists');
        } catch (Exception $e) {
            Logger::warning('Could not create comments table: ' . $e->getMessage());
        }
    }

    private function createSettingsTable()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS `settings` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `key` VARCHAR(100) UNIQUE NOT NULL,
                `value` LONGTEXT,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX `idx_key` (`key`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        
        try {
            $this->db->query($sql);
            Logger::info('Table `settings` created or already exists');
        } catch (Exception $e) {
            Logger::warning('Could not create settings table: ' . $e->getMessage());
        }
    }
}
