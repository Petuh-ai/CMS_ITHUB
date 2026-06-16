# Развертывание на Render Hosting

Инструкция по развертыванию PHP CMS проекта на Render.

## Шаг 1: Подготовка GitHub репозитория

1. Создайте репозиторий на GitHub и загрузьте код проекта:
```bash
git init
git add .
git commit -m "Initial commit"
git remote add origin https://github.com/ваш-username/cms.git
git push -u origin main
```

## Шаг 2: Создание MySQL базы данных на Render

1. Зайдите на [render.com](https://render.com)
2. Нажмите **"New +"** → **"MySQL"**
3. Заполните параметры:
   - **Name**: `cms-db` (или другое имя)
   - **Database**: `cms_bd`
   - **Username**: `cms_user` (или другое имя)
   - **Region**: Выберите ближайший регион
   - **Pricing Plan**: `Free` или `Standard`
4. Нажмите **"Create Database"**
5. Скопируйте **Connection String** - вам понадобится позже

## Шаг 3: Создание Web Service на Render

1. На Render Dashboard нажмите **"New +"** → **"Web Service"**
2. Выберите **"Deploy an existing project from a Git repository"**
3. Подключите ваш GitHub репозиторий
4. Заполните параметры:
   - **Name**: `cms` (или другое имя)
   - **Environment**: `Docker`
   - **Region**: Выберите ближайший регион
   - **Branch**: `main` (или ваша основная ветка)
   - **Pricing Plan**: `Free` или `Standard`

## Шаг 4: Добавление переменных окружения

На странице Web Service перейдите в раздел **Environment**:

Добавьте следующие переменные (значения из подключения к БД):

```
APP_DEBUG=false
APP_TIMEZONE=UTC
LOG_LEVEL=info
DB_DRIVER=mysql
DB_HOST=***.mysql.render.com
DB_PORT=3306
DB_NAME=cms_bd
DB_USER=cms_user
DB_PASSWORD=your_password_here
SESSION_SECURE=true
```

**Замените значения на реальные из вашей БД на Render:**
- `DB_HOST` - хост из Connection String
- `DB_USER` - ваше имя пользователя БД
- `DB_PASSWORD` - пароль БД
- `DB_NAME` - имя базы данных

## Шаг 5: Развертывание

1. Нажмите **"Create Web Service"**
2. Render начнет строить Docker образ (это займет 5-10 минут)
3. Когда статус изменится на **"Live"**, ваше приложение готово
4. Перейдите по URL в верхней части страницы Web Service

## Шаг 6: Инициализация базы данных

После первого развертывания нужно создать таблицы в PostgreSQL и (опционально) администратора.

### Вариант: Через bash/shell в Render
1. На странице Web Service найдите **"Shell"**
2. Выполните:
```bash
cd /var/www/html
php scripts/init_postgres.php
php scripts/seed_admin_postgres.php
```

Если вы хотите запустить только инициализацию схемы, выполните только первую команду.

## Шаг 7: Проверка приложения

1. Откройте URL вашего приложения
2. Попробуйте войти с учетными данными администратора (если они созданы)
3. Проверьте, что все функции работают корректно

## Полезные команды для управления

### Просмотр логов
В Render Dashboard перейдите на страницу Web Service → **"Logs"**

### Перезагрузка приложения
Web Service → **"Redeploy"**

### Изменение переменных окружения
Web Service → **"Environment"** → отредактируйте и сохраните

## Поддержка MySQL версии

Render поддерживает MySQL 5.7, 8.0. Убедитесь в совместимости вашего кода.

## Ограничения бесплатного плана Render

- Web Service: автоматически переходит в спящий режим после 15 минут неактивности
- Базы данных: один инстанс, ограниченное хранилище
- Для production используйте платные планы

## Проблемы и решения

### Ошибка подключения к БД
- Проверьте переменные окружения `DB_HOST`, `DB_USER`, `DB_PASSWORD`
- Убедитесь, что MySQL база данных создана и активна

### Файлы загрузок не сохраняются
- На Render файловая система эфемерна (временна)
- Используйте облачное хранилище (AWS S3, Cloudinary и т.д.)

### Медленная работа приложения
- Оптимизируйте запросы к БД
- Добавьте кеширование
- Используйте платный план

## Дополнительно

- [Документация Render](https://render.com/docs)
- [Поддержка MySQL на Render](https://render.com/docs/deploy-mysql)
