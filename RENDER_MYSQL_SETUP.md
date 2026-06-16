# Развертывание на Render с внешней MySQL базой данных

Инструкция по развертыванию PHP CMS проекта на Render с использованием стороннего MySQL сервера.

## Шаг 1: Подготовка проекта

1. Создайте репозиторий на GitHub и загрузьте код:
```bash
git init
git add .
git commit -m "Initial commit"
git remote add origin https://github.com/ваш-username/cms.git
git push -u origin main
```

## Шаг 2: Подготовка MySQL базы данных

Используйте любого провайдера MySQL хостинга, например:
- **Render MySQL** (render.com/docs/deploy-mysql)
- **AWS RDS MySQL**
- **Digital Ocean Managed MySQL**
- **Planetscale** (MySQL-совместимая)
- **Hostinger**
- Любой другой MySQL хостер

После создания БД запишите:
- **Host** (например: `mysql.example.com`)
- **Port** (обычно `3306`)
- **Database name** (например: `cms_bd`)
- **Username** (например: `cms_user`)
- **Password** (ваш пароль)

## Шаг 3: Инициализация MySQL базы данных

Перед деплоем нужно создать таблицы. Есть 3 варианта:

### Вариант 1: Через MySQL консоль (phpmyadmin/adminer)
1. Откройте консоль управления MySQL
2. Выполните SQL из файла `database/schema.sql`

### Вариант 2: Локально с помощью PHP скрипта
```bash
# Скопируйте .env.example в .env и заполните данные MySQL
cp .env.example .env

# Отредактируйте .env с данными вашей MySQL БД
# DB_HOST=your-mysql-host
# DB_USER=cms_user
# DB_PASSWORD=your-password
# DB_NAME=cms_bd

# Запустите инициализацию
php scripts/init_local_db.php
```

### Вариант 3: После развертывания на Render через Shell
1. На странице Web Service нажмите **"Shell"**
2. Выполните:
```bash
cd /var/www/html
php scripts/init_local_db.php
```

## Шаг 4: Создание Web Service на Render

1. На [render.com](https://render.com) нажмите **"New +"** → **"Web Service"**
2. Выберите **"Deploy an existing project from a Git repository"**
3. Подключите ваш GitHub репозиторий
4. Заполните параметры:
   - **Name**: `cms` (или другое имя)
   - **Environment**: `Docker`
   - **Region**: Выберите ближайший регион
   - **Branch**: `main`
   - **Pricing Plan**: `Free` или `Standard`

## Шаг 5: Добавление переменных окружения

На странице Web Service перейдите в раздел **"Environment"** и добавьте:

```
APP_DEBUG=false
APP_TIMEZONE=UTC
LOG_LEVEL=info
DB_DRIVER=mysql
DB_HOST=your-mysql-host
DB_PORT=3306
DB_NAME=cms_bd
DB_USER=cms_user
DB_PASSWORD=your_password_here
SESSION_SECURE=true
```

**Замените значения:**
- `DB_HOST` - хост вашего MySQL сервера
- `DB_PORT` - порт MySQL (обычно 3306)
- `DB_USER` - имя пользователя БД
- `DB_PASSWORD` - пароль БД
- `DB_NAME` - имя базы данных

## Шаг 6: Развертывание

1. Нажмите **"Create Web Service"**
2. Render начнет строить Docker образ (5-10 минут)
3. Когда статус изменится на **"Live"**, приложение готово
4. Перейдите по URL в верхней части страницы Web Service

## Шаг 7: Проверка приложения

1. Откройте URL вашего приложения
2. Попробуйте войти (если админ был создан):
   - Email: `admin@example.com`
   - Password: `password` (измените после первого входа!)
3. Проверьте функциональность

## Полезные команды управления

### Просмотр логов
Web Service → **"Logs"**

### Перезагрузка приложения
Web Service → **"Redeploy"**

### Изменение переменных окружения
Web Service → **"Environment"** → отредактируйте и сохраните

## Проблемы и решения

### Ошибка: "Connection refused"
- Проверьте `DB_HOST`, `DB_PORT`, `DB_USER`, `DB_PASSWORD`
- Убедитесь, что MySQL сервер доступен из интернета
- Проверьте firewall правила MySQL хостера

### Ошибка: "Access denied for user"
- Проверьте правильность пароля в `DB_PASSWORD`
- Проверьте имя пользователя в `DB_USER`
- Убедитесь, что пользователь может подключаться с Render IP

### Ошибка: "Database does not exist"
- Выполните `php scripts/init_local_db.php` (вариант 3 выше)
- Или создайте БД вручную через phpmyadmin

### Таблицы не создаются
1. Откройте Shell в Web Service
2. Выполните: `php scripts/init_local_db.php`

### Файлы загрузок не сохраняются
- На Render файловая система эфемерна (временна)
- Используйте облачное хранилище (AWS S3, Cloudinary и т.д.)

## Ограничения бесплатного плана Render

- Web Service: спящий режим после 15 минут неактивности
- Один MySQL инстанс (если используете Render MySQL)
- Ограниченное хранилище

## Дополнительно

- [Документация Render](https://render.com/docs)
- [Поддержка MySQL на Render](https://render.com/docs/deploy-mysql)
- [Planetscale MySQL](https://planetscale.com) - рекомендуется для бесплатного плана
