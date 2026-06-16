# PROJECT_STRUCTURE.md

# Структура проекта CMS System

```
cms/
│
├── app/                                  # Код приложения
│   ├── Controllers/                      # Контроллеры
│   │   ├── AuthController.php           # Аутентификация и авторизация
│   │   ├── DashboardController.php      # Панель управления
│   │   ├── PostController.php           # Управление постами
│   │   ├── CategoryController.php       # Управление категориями
│   │   ├── MediaController.php          # Управление медиа
│   │   ├── UserController.php           # Управление пользователями
│   │   ├── CommentController.php        # Управление комментариями
│   │   ├── SettingsController.php       # Настройки системы
│   │   └── FrontendController.php       # Фронтенд (публичная часть)
│   │
│   ├── Models/                          # Модели данных
│   │   ├── User.php                     # Модель пользователя
│   │   ├── Post.php                     # Модель поста
│   │   ├── Category.php                 # Модель категории
│   │   ├── Media.php                    # Модель медиафайла
│   │   ├── Comment.php                  # Модель комментария
│   │   └── Settings.php                 # Модель настроек
│   │
│   ├── Middleware/                      # Middleware
│   │   └── AuthMiddleware.php           # Проверка аутентификации
│   │
│   ├── Views/                           # Шаблоны представлений
│   │   ├── admin/                       # Админ-панель
│   │   │   ├── dashboard.php
│   │   │   ├── posts/
│   │   │   │   ├── index.php
│   │   │   │   ├── create.php
│   │   │   │   └── edit.php
│   │   │   ├── categories/
│   │   │   │   ├── index.php
│   │   │   │   ├── create.php
│   │   │   │   └── edit.php
│   │   │   ├── media/
│   │   │   │   └── index.php
│   │   │   ├── users/
│   │   │   │   ├── index.php
│   │   │   │   ├── create.php
│   │   │   │   └── edit.php
│   │   │   ├── comments/
│   │   │   │   └── index.php
│   │   │   ├── settings/
│   │   │   │   └── index.php
│   │   │   └── profile/
│   │   │       └── edit.php
│   │   │
│   │   ├── frontend/                   # Публичная часть
│   │   │   ├── index.php               # Главная страница
│   │   │   ├── post.php                # Отдельный пост
│   │   │   ├── category.php            # Страница категории
│   │   │   └── search.php              # Результаты поиска
│   │   │
│   │   ├── auth/                       # Страницы аутентификации
│   │   │   ├── login.php
│   │   │   └── forgot-password.php
│   │   │
│   │   └── layouts/                    # Шаблоны оформления
│   │       ├── admin.php               # Основной лайаут админ-панели
│   │       └── frontend.php            # Основной лайаут фронтенда
│   │
│   ├── Database.php                     # Класс работы с БД (PDO)
│   ├── Logger.php                       # Система логирования
│   ├── Validator.php                    # Валидация данных
│   ├── Security.php                     # Функции безопасности
│   ├── Router.php                       # Маршрутизация URL
│   └── .htaccess                        # Переписи URL (Apache)
│
├── config/                              # Конфигурационные файлы
│   ├── database.php                    # Настройки БД
│   ├── app.php                         # Основные настройки приложения
│   └── roles.php                       # Определение ролей
│
├── database/                            # Файлы БД
│   ├── schema.sql                      # Схема БД
│   └── seed_admin.sql                  # SQL для создания админа
│
├── public/                              # Публичная папка веб-сервера
│   ├── index.php                       # Точка входа приложения
│   ├── test.php                        # Тестовая страница
│   ├── .htaccess                       # Правила mod_rewrite
│   │
│   ├── css/                            # Стилевые файлы
│   │   ├── admin.css                  # Стили админ-панели
│   │   ├── frontend.css               # Стили фронтенда
│   │   └── auth.css                   # Стили страниц входа
│   │
│   ├── js/                             # JavaScript файлы
│   │   ├── admin.js                   # Скрипты админ-панели
│   │   └── frontend.js                # Скрипты фронтенда
│   │
│   └── uploads/                        # Загруженные медиафайлы
│       └── (автоматически создается)
│
├── storage/                             # Хранилище
│   ├── logs/                           # Логи приложения
│   │   └── YYYY-MM-DD.log            # Логи по датам
│   │
│   └── backups/                        # Резервные копии БД
│       └── (автоматически создается)
│
├── composer.json                        # Зависимости Composer
├── cli.php                             # Консольная утилита
├── install.sh                          # Установочный скрипт (Linux/Mac)
├── install.bat                         # Установочный скрипт (Windows)
├── .gitignore                          # Git игнор файлы
├── LICENSE                             # MIT License
│
├── README.md                           # Основная документация
├── INSTALLATION_GUIDE.md               # Руководство по установке
├── API_DOCUMENTATION.md                # Документация API
├── PROJECT_STRUCTURE.md                # Этот файл
├── CHANGELOG.md                        # История изменений
└── CONTRIBUTING.md                     # Рекомендации для контрибьюторов
```

## Основные компоненты

### Database.php
Singleton класс для работы с MySQL через PDO. 
- Защита от SQL-инъекций параметризованные запросы
- Методы: query(), fetchOne(), fetchAll(), insert(), update(), delete()

### Router.php
Простой маршрутизатор для обработки URL.
- Методы: get(), post(), dispatch()
- Поддержка параметров в URL ({id}, {slug})

### Security.php
Функции безопасности:
- escape() - экранирование HTML символов
- hashPassword() / verifyPassword() - BCRYPT хеширование
- generateCSRFToken() / verifyCSRFToken() - защита от CSRF
- isValidMimeType() / isValidExtension() - валидация файлов
- sanitizeFilename() - безопасное имя файла

### Validator.php
Система валидации данных:
- Типы валидации: required, email, min, max, unique, confirmed
- Возвращает массив ошибок по полям

### Logger.php
Логирование приложения:
- Уровни: debug, info, warning, error, critical
- Логи сохраняются в storage/logs/{дата}.log

### AuthMiddleware.php
Проверка прав доступа:
- checkAuth() - проверка аутентификации
- checkRole() - проверка роли пользователя
- getCurrentUser() - данные текущего пользователя

## Роли и права

### Администратор (admin)
- Полный доступ ко всем функциям
- Управление пользователями
- Управление системными настройками

### Редактор (editor)
- Полное управление постами
- Управление категориями
- Управление медиа
- Модерация комментариев

### Автор (author)
- Создание своих черновиков
- Редактирование своих постов
- Загрузка медиа
- Просмотр статистики

## Таблицы БД

### users
- id, name, email, password, role, status, created_at, updated_at

### posts
- id, title, slug, content, excerpt, category_id, author_id, status, featured_image_id, created_at, updated_at, published_at

### categories
- id, name, slug, description, parent_id, created_at, updated_at

### media
- id, filename, original_name, mime_type, size, uploaded_by, uploaded_at

### comments
- id, post_id, user_id, author_name, author_email, content, status, created_at, updated_at

### settings
- id, key, value, created_at, updated_at

### audit_logs
- id, user_id, action, model, model_id, changes, ip_address, created_at

## Маршруты

### Аутентификация
- GET/POST /login - вход
- GET /logout - выход
- GET/POST /forgot-password - восстановление пароля

### Админ-панель
- GET /admin/dashboard - главная панель
- GET/POST /admin/posts - управление постами
- GET/POST /admin/categories - управление категориями
- GET/POST /admin/media - загрузка медиа
- GET/POST /admin/users - управление пользователями
- GET/POST /admin/comments - модерация комментариев
- GET/POST /admin/settings - системные настройки
- GET/POST /admin/profile - профиль пользователя

### Публичная часть
- GET / - главная страница
- GET /post/{slug} - чтение поста
- GET /category/{slug} - примечание категории
- GET /search - поиск

### API
- GET /api/media - получить все медиа
- GET /api/media/{id} - получить конкретное медиа

## Безопасность в деталях

### SQL-инъекции
Все запросы используют PDO параметры:
```php
$db->query("SELECT * FROM users WHERE id = ?", [$id]);
```

### XSS-атаки
Все данные экранируются перед выводом:
```php
echo Security::escape($user['name']);
```

### CSRF
Все формы содержат CSRF токен:
```php
<input type="hidden" name="csrf_token" value="<?php echo Security::generateCSRFToken(); ?>">
```

### Пароли
BCRYPT хеширование с cost=12:
```php
$hash = Security::hashPassword($password);
```

## Производительность

- Индексы на часто-используемых колонках (email, slug, status)
- Кеширование настроек в памяти
- Пагинация для больших наборов данных
- Минимизация запросов к БД

## Расширяемость

Архитектура позволяет легко добавлять:
- Новые контроллеры и модели
- Новые типы медиа
- Новые роли и права
- Новые плагины

---

**Версия документа:** 1.0.0  
**Обновлено:** 30.03.2026
