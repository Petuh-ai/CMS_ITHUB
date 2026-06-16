INSTALLATION_GUIDE.md

# Руководство по установке CMS System

## Быстрый старт

### Windows
1. Откройте Command Prompt или PowerShell в папке проекта
2. Запустите: `install.bat`
3. Отредактируйте `config/database.php`
4. Импортируйте БД в MySQL

### Linux/Mac
1. Откройте терминал в папке проекта
2. Запустите: `chmod +x install.sh && ./install.sh`
3. Отредактируйте `config/database.php`
4. Импортируйте БД в MySQL

## Требования

### Системные требования
- ОС: Linux, macOS, Windows
- PHP: 7.4 или выше
- MySQL: 5.7 или выше
- Веб-сервер: Apache (с mod_rewrite) или Nginx
- Свободное место: минимум 100MB

### PHP расширения
- PDO (обычно по умолчанию)
- PDO MySQL
- Mbstring
- JSON (обычно по умолчанию)

## Пошаговая инструкция

### 1. Загрузка файлов
Скопируйте все файлы в корневую папку веб-сервера.

### 2. Создание базы данных

```bash
# В MySQL:
CREATE DATABASE cms_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;

# Импорт схемы:
mysql -u root -p cms_db < database/schema.sql
```

### 3. Конфигурация БД

Отредактируйте `config/database.php`:

```php
'host' => 'localhost',        // Хост MySQL
'database' => 'cms_db',       // Имя БД
'username' => 'root',         // Пользователь MySQL
'password' => 'пароль',       // Пароль MySQL
```

### 4. Конфигурация приложения

В `config/app.php` можно изменить:

```php
'name' => 'My CMS',           // Название сайта
'debug' => false,             // false в production
'timezone' => 'UTC',          // Временная зона
```

### 5. Права доступа (Linux/Mac)

```bash
chmod 755 public/
chmod 755 storage/
chmod 755 storage/uploads/
chmod 755 storage/logs/
chmod 755 storage/backups/
chmod 777 storage/uploads/    # Для загрузок
```

### 6. Конфигурация веб-сервера

#### Apache (.htaccess уже включен)
Убедитесь, что mod_rewrite включен:
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

#### Nginx
Добавьте в конфиг серверного блока:
```nginx
location / {
    if (!-e $request_filename) {
        rewrite ^(.*)$ /index.php last;
    }
}

location ~ \.php$ {
    fastcgi_pass 127.0.0.1:9000;
    fastcgi_index index.php;
    include fastcgi_params;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
}
```

### 7. Создание первого администратора

Выполните этот SQL запрос в MySQL:

```sql
-- Пароль: password
INSERT INTO users (name, email, password, role, status, created_at) VALUES (
    'Administrator',
    'admin@example.com',
    '$2y$12$iLPxIhQoMx5h0s8MhDKfMe3vJdGfq2nR1ZP8mXpjuwKqD8XY3MQay',
    'admin',
    'active',
    NOW()
);
```

Или используйте PHP для генерации хеша:
```php
<?php
echo password_hash('ваш_пароль', PASSWORD_BCRYPT, ['cost' => 12]);
?>
```

### 8. Проверка установки

Откройте браузер и перейдите на:
- `http://yourdomain.com/login` - вход в систему
- `http://yourdomain.com/` - фронтенд сайта

## Устранение проблем

### Ошибка подключения к БД
- Проверьте данные в `config/database.php`
- Убедитесь, что MySQL запущен
- Проверьте права пользователя MySQL

### Ошибка 404 на всех страницах
- Включите mod_rewrite (Apache)
- Проверьте конфиг Nginx
- Убедитесь, что веб-сервер указывает на папку `public/`

### Ошибка прав доступа при загрузке файлов
- Проверьте права на папку `public/uploads/`
- Убедитесь, что веб-сервис может писать в эту папку
- На Linux/Mac: `chmod 777 public/uploads/`

### Белый экран (White Screen of Death)
- Включите debug в `config/app.php`: `'debug' => true`
- Проверьте логи в `storage/logs/`
- Проверьте ошибки PHP: `php -l public/index.php`

## Безопасность

### Обязательные действия
1. Измените пароль администратора после первого входа
2. Отключите debug в production: `'debug' => false`
3. Установите HTTPS сертификат
4. Регулярно обновляйте пароли

### Рекомендации
1. Используйте сильные пароли (16+ символов)
2. Регулярно создавайте резервные копии
3. Установите firewall
4. Ограничьте доступ к админ-панели по IP

## Обновление

Для обновления системы:
1. Создайте резервную копию БД
2. Скопируйте новые файлы
3. Выполните миграции БД

## Поддержка

Если у вас возникли проблемы:
1. Проверьте требования выше
2. Посмотрите логи в `storage/logs/`
3. Убедитесь, что PHP и MySQL расстроены правильно

---

**Готово!** Система установлена и готова к использованию.
