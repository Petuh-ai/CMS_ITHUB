-- Таблица пользователей
CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'author',
    status VARCHAR(20) NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    deleted_at TIMESTAMP NULL
);

-- Таблица категорий
CREATE TABLE IF NOT EXISTS categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    parent_id INT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Таблица медиафайлов
CREATE TABLE IF NOT EXISTS media (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    mime_type VARCHAR(100) NOT NULL,
    size INT NOT NULL,
    uploaded_by INT UNSIGNED,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Таблица постов
CREATE TABLE IF NOT EXISTS posts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    content TEXT NOT NULL,
    excerpt TEXT,
    category_id INT UNSIGNED,
    author_id INT UNSIGNED NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'draft',
    featured_image_id INT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    published_at TIMESTAMP NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (featured_image_id) REFERENCES media(id) ON DELETE SET NULL
);

-- Таблица комментариев
CREATE TABLE IF NOT EXISTS comments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    post_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED,
    author_name VARCHAR(255) NOT NULL,
    author_email VARCHAR(255),
    content TEXT NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Таблица настроек
CREATE TABLE IF NOT EXISTS settings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(255) NOT NULL UNIQUE,
    value TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL
);

-- Таблица логов
CREATE TABLE IF NOT EXISTS audit_logs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED,
    action VARCHAR(255) NOT NULL,
    model VARCHAR(100) NOT NULL,
    model_id INT,
    changes TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);
