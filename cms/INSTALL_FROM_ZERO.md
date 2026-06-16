# 🚀 УСТАНОВКА С НУЛЯ НА WINDOWS (для начинающих)

**Если у вас вообще ничего нет - вы в правильном месте!**

Этот гайд поможет вам установить всё необходимое и запустить CMS за 30 минут.

---

## 📋 ЧТО НУЖНО УСТАНОВИТЬ

1. **PHP 7.4+** - язык программирования
2. **MySQL 8.0** - база данных
3. **CMS System** - наша система управления контентом

---

## ✅ ШАГИ УСТАНОВКИ

### ШАГ 1: Установка PHP (5 минут)

#### Вариант A: Быстрый способ (рекомендуется для новичков)

Скачайте **PHP 8.2** с официального сайта:

1. Откройте браузер и перейдите на: https://windows.php.net/downloads/releases/
2. Найдите **PHP 8.2 (Latest)** → скачайте **VS16 x64 ZTS Installer**
3. Это файл вида `php-8.2.x-Win32-vs16-x64.msi`
4. Двойной клик на установщик
5. Установите в папку: `C:\php\`
6. На экране конфигурации установите:
   - Web Server: выберите "None" (или пропустите)
   - Нажмите "Далее" для остального
7. В конце нажмите "Готово"

#### Проверка установки PHP:

1. Откройте **CMD** (нажмите Win+R, введите `cmd`, нажмите Enter)
2. Введите команду:
   ```
   php --version
   ```
3. Если увидите версию PHP - всё OK! ✅

**Если не работает:**
- Добавьте PHP в PATH:
  1. Win + R → введите `sysdm.cpl`
  2. Вкладка "Дополнительно" → "Переменные окружения"
  3. В "Системные переменные" найдите "Path"
  4. Нажмите "Изменить"
  5. Нажмите "Создать" и добавьте: `C:\php\`
  6. Нажмите OK, OK, OK
  7. Перезагрузитесь

---

### ШАГ 2: Установка MySQL (10 минут)

#### Вариант A: MySQL Community Server (рекомендуется)

1. Откройте: https://dev.mysql.com/downloads/mysql/
2. Скачайте **MySQL 8.0** → выберите вашу версию Windows
3. Скачайте файл `.msi`
4. Запустите установщик
5. Выберите "Developer Default" (рекомендуется)
6. Нажимайте "Next" до конца
7. Когда спросит о порте - оставьте **3306** (по умолчанию)
8. Когда спросит о пароле для root:
   - Установите пароль: **root** (или любой другой)
   - **ЗАПОМНИТЕ ЭТОТ ПАРОЛЬ!**
9. Завершите установку

#### Вариант B: Быстрый способ (если лень возиться с установщиком)

Скачайте **XAMPP** - это всё-в-одном (Apache + PHP + MySQL):
1. Откройте: https://www.apachefriends.org/
2. Нажмите "Download XAMPP"
3. Скачайте Windows версию
4. Установите в `C:\xampp\`
5. Запустите `xampp-control.exe`
6. Нажмите "Start" рядом с Apache и MySQL

---

### ШАГ 3: Создание базы данных (5 минут)

Откройте **MySQL Command Line Client:**

1. Нажмите Win+R → введите `cmd`
2. Введите:
   ```
   mysql -u root -p
   ```
3. Введите пароль (который установили на ШАГ 2)
4. Скопируйте и вставьте всё это (один раз сразу):

```sql
CREATE DATABASE cms;
USE cms;

CREATE TABLE `users` (
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
);

CREATE TABLE `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) UNIQUE NOT NULL,
  `description` TEXT,
  `parent_id` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_slug` (`slug`),
  INDEX `idx_parent_id` (`parent_id`)
);

CREATE TABLE `posts` (
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
);

CREATE TABLE `media` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `filename` VARCHAR(255) NOT NULL,
  `original_name` VARCHAR(255) NOT NULL,
  `mime_type` VARCHAR(100),
  `size` INT,
  `uploaded_by` INT NOT NULL,
  `uploaded_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_mime_type` (`mime_type`),
  INDEX `idx_uploaded_by` (`uploaded_by`)
);

CREATE TABLE `comments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `post_id` INT NOT NULL,
  `user_id` INT,
  `author_name` VARCHAR(100),
  `author_email` VARCHAR(100),
  `content` TEXT NOT NULL,
  `status` ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_post_id` (`post_id`),
  INDEX `idx_status` (`status`),
  INDEX `idx_user_id` (`user_id`)
);

CREATE TABLE `settings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `key` VARCHAR(100) UNIQUE NOT NULL,
  `value` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_key` (`key`)
);

CREATE TABLE `audit_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT,
  `action` VARCHAR(100),
  `model` VARCHAR(100),
  `model_id` INT,
  `changes` JSON,
  `ip_address` VARCHAR(45),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_user_id` (`user_id`),
  INDEX `idx_created_at` (`created_at`)
);

-- Добавить администратора
INSERT INTO `users` (`name`, `email`, `password`, `role`) VALUES (
  'Administrator',
  'admin@example.com',
  '$2y$12$xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
  'admin'
);

-- Добавить первого пользователя
UPDATE `users` SET `password` = '$2y$12$8/BV.Sd8xwYhJTsALc6AAuwlPj3CEHVhzWiAK0r5A8hGVLq.hQCiK' WHERE `email` = 'admin@example.com';
```

5. Введите `exit` и нажмите Enter

✅ База данных создана!

---

### ШАГ 4: Настройка CMS (5 минут)

#### Если используете PHP встроенный сервер:

1. Откройте папку: `c:\Users\SystemX\Desktop\cms\`
2. Откройте CMD в этой папке (Shift + правый клик → "Открыть PowerShell здесь")
3. Введите:
   ```
   php -S localhost:8000 -t public
   ```
4. Откройте в браузере: **http://localhost:8000**
5. Введите:
   - Email: `admin@example.com`
   - Пароль: `admin123`

#### Если используете XAMPP:

1. Скопируйте папку `cms` в: `C:\xampp\htdocs\`
2. Откройте браузер: **http://localhost/cms/public**
3. Введите:
   - Email: `admin@example.com`
   - Пароль: `admin123`

#### Если используете Apache (продвинутый способ):

1. Скопируйте папку `cms` в: `C:\Apache24\htdocs\` (или где у вас Apache)
2. Отредактируйте `config/database.php`:
   ```php
   'host' => 'localhost',
   'user' => 'root',
   'password' => 'root', // пароль который вы установили
   'database' => 'cms'
   ```
3. Откройте браузер: **http://localhost/cms/public**

---

## 🎯 САМЫЙ БЫСТРЫЙ СПОСОБ (для нетерпеливых)

Если вам нужно ОЧЕНЬ БЫСТРО:

### 1. Скачайте XAMPP
https://www.apachefriends.org/
- Установите в `C:\xampp\`
- Запустите `xampp-control.exe`
- Нажмите "Start" на Apache и MySQL

### 2. Скопируйте CMS в XAMPP
```
Скопировать: c:\Users\SystemX\Desktop\cms\
В папку: C:\xampp\htdocs\
```

### 3. Создайте базу данных

Откройте браузер, перейдите на: **http://localhost/phpmyadmin**

1. Нажмите вкладку "SQL"
2. Скопируйте весь код из файла: `database/schema.sql`
3. Вставьте в текстовое поле и нажмите "Выполнить"

### 4. Откройте CMS

Откройте браузер: **http://localhost/cms/public**

Вот и всё! Вы прекрасны! 🎉

---

## 🔑 ДАННЫЕ ДЛЯ ВХОДА

```
URL:      http://localhost/cms/public (или http://localhost:8000)
Email:    admin@example.com
Пароль:   admin123
```

---

## ❌ РЕШЕНИЕ ПРОБЛЕМ

### "PHP не найдена"
✅ Вы не перезагружали компьютер после установки  
✅ Решение: Перезагрузитесь и введите `php --version` заново

### "MySQL не запускается"
✅ Порт 3306 занят другой программой  
✅ Решение: Откройте MySQL на другом порту или закройте конфликтующее приложение

### "Ошибка подключения к BD"
✅ Проверьте пароль в `config/database.php`  
✅ Убедитесь что MySQL запущена  
✅ Убедитесь что база `cms` создана

### "Белый экран при входе"
✅ Включите отладку в `config/app.php` (измените `'debug' => false` на `true`)  
✅ Проверьте файл `storage/logs/` для ошибок

### "Изображения не загружаются"
✅ Проверьте права на папку `public/uploads/`  
✅ Используйте команду (в PowerShell админ):
```
icacls "C:\xampp\htdocs\cms\public\uploads" /grant Everyone:(F)
```

---

## 📚 ДАЛЬШЕ?

Когда всё установлено и работает:

1. 📖 Откройте **QUICK_START.md** - основные операции
2. 📚 Откройте **README.md** - полная документация
3. 🏗️ Откройте **PROJECT_STRUCTURE.md** - архитектура

---

## 🆘 ЕСЛИ СОВСЕМ НЕ РАБОТАЕТ

Выполните эту команду в CMD (под администратором):

```cmd
cd c:\Users\SystemX\Desktop\cms
php public/test.php
```

Это покажет вам все ошибки и недостающие компоненты.

---

**Вопросы? Прочитайте INSTALLATION_GUIDE.md для подробной информации!**

---

*Версия: 1.0.0*  
*Дата: 30.03.2026*  
*Статус: ✅ Готово*
