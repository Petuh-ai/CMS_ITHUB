# 🚀 Полный гайд развертывания CMS на Render.com

**Статус:** Готово для развертывания  
**Дата:** 2026-06-07  
**GitHub репозиторий:** https://github.com/Petuh-ai/CMS

---

## ✅ Предварительная подготовка

### 1️⃣ Убедитесь, что код загружен в GitHub

Проверьте, что в вашем репозитории есть все файлы проекта:
```bash
# Если не загружали - выполните в папке проекта:
git add .
git commit -m "CMS project ready for deployment"
git push origin main
```

**Важные файлы для развертывания должны быть в репозитории:**
- ✅ `Dockerfile`
- ✅ `render.yaml`
- ✅ `composer.json`
- ✅ `/app/` - папка с кодом
- ✅ `/config/` - конфигурационные файлы
- ✅ `/database/schema.sql`
- ✅ `/scripts/` - скрипты инициализации

---

## 📋 Пошаговое развертывание на Render.com

### Шаг 1: Создание MySQL базы данных

1. Зайдите на https://dashboard.render.com
2. Нажмите **"New +"** в верхней части
3. Выберите **"MySQL Database"** (или **"PostgreSQL"** если используете PostgreSQL)
4. Заполните параметры:
   - **Name:** `cms-db`
   - **Database name:** `cms_database`
   - **Username:** `cms_admin`
   - **Region:** Выберите регион ближайший к вам (например, Frankfurt)
   - **Plan:** `Free` (для тестирования) или `Standard` (для production)

5. Нажмите **"Create Database"**
6. ⏱️ Дождитесь создания (обычно 1-2 минуты)

Если в вашем аккаунте Render нет возможности создать MySQL Database, используйте внешний MySQL-хост.
Для бесплатного теста лучше всего подходит `db4free.net`.

**Внешняя база подключается так:**
- `DB_DRIVER=mysql`
- `DB_HOST=db4free.net`
- `DB_PORT=3306`
- `DB_NAME=cms_bd`
- `DB_USER=cms_user`
- `DB_PASSWORD=your-password`

> В `render.yaml` приложение уже готово к внешней БД через переменные окружения.

Если вы создаёте базу на Render, используйте Connection String:
- На странице БД нажмите иконку копирования рядом с **"Internal Database URL"**
- Это строка вида: `mysql://cms_admin:PASSWORD@hostname:3306/cms_database`

**Сохраните эти значения:**
```
DB_HOST: hostname из Connection String
DB_PORT: 3306
DB_USER: cms_admin
DB_PASSWORD: PASSWORD (из Connection String)
DB_NAME: cms_database
```

---

### Шаг 2: Создание Web Service (основное приложение)

1. На Render Dashboard нажмите **"New +"**
2. Выберите **"Web Service"**
3. Выберите **"Deploy an existing project from a Git repository"**
4. Подключите ваш GitHub репозиторий:
   - Выберите **"Petuh-ai/CMS"** или найдите ваш репозиторий
   - Нажмите **"Connect"**

5. Заполните параметры:
   - **Name:** `cms-app` (или другое имя)
   - **Environment:** `Docker`
   - **Region:** Тот же регион, что и БД
   - **Branch:** `main` (или ваша основная ветка)
   - **Dockerfile path:** `./Dockerfile` (по умолчанию)
   - **Build command:** оставьте пустым (Render сам определит)
   - **Start command:** оставьте пустым (Render сам определит)

6. В секции **"Environment"** добавьте переменные окружения:

```
APP_DEBUG=false
APP_TIMEZONE=Europe/Moscow
LOG_LEVEL=info
DB_DRIVER=mysql
DB_HOST=your_db_host_here
DB_PORT=3306
DB_NAME=cms_database
DB_USER=cms_admin
DB_PASSWORD=your_db_password_here
SESSION_SECURE=true
SESSION_HTTPONLY=true
```

**Замените значения:**
- `DB_HOST` → значение из Connection String
- `DB_USER` → `cms_admin` (или то имя, которое вы указали)
- `DB_PASSWORD` → пароль из Connection String
- `DB_NAME` → `cms_database` (или то имя, которое вы указали)

7. Нажмите **"Create Web Service"**
8. ⏱️ Дождитесь сборки Docker образа (5-15 минут)
9. Статус изменится на **"Live"** - приложение готово!

---

### Шаг 3: Инициализация базы данных

После первого развертывания нужно создать таблицы в БД.

#### Способ 1: Через Shell на Render (рекомендуется)

1. На странице Web Service найдите кнопку **"Shell"** (в верхнем меню)
2. Выполните команду для создания таблиц:

```bash
cd /var/www/html
php scripts/init_postgres.php
```

3. Затем (опционально) создайте администратора:

```bash
php scripts/seed_admin_postgres.php
```

**Учетные данные администратора (если создали):**
- Логин: `admin`
- Пароль: `admin123` (измените после первого входа!)

---

### Шаг 4: Проверка развертывания

1. Откройте URL вашего приложения - должно быть в виде:
   `https://cms-app.onrender.com`

2. Попробуйте перейти на главную страницу `/`
3. Если видите сайт - ✅ **Развертывание успешно!**
4. Попробуйте войти в админ-панель `/admin`

---

## 🔧 Дополнительные настройки

### Настройка собственного домена

1. На странице Web Service перейдите в **"Settings"**
2. Найдите раздел **"Custom Domains"**
3. Добавьте ваш домен
4. Следуйте инструкциям по настройке DNS записей

### Просмотр логов

1. На странице Web Service перейдите в **"Logs"**
2. Здесь видны все ошибки и события приложения
3. Если что-то не работает - посмотрите логи для диагностики

### Перезагрузка приложения

1. На странице Web Service нажмите **"Redeploy"** (в правом углу)
2. Или просто загрузьте новые изменения в GitHub - развертывание произойдет автоматически

---

## ✨ Быстрый чек-лист

- [ ] GitHub репозиторий содержит все файлы проекта
- [ ] MySQL БД создана на Render
- [ ] Web Service создан и настроен
- [ ] Переменные окружения добавлены корректно
- [ ] Приложение показывает статус "Live"
- [ ] БД инициализирована (таблицы созданы)
- [ ] Главная страница открывается
- [ ] Админ-панель доступна

---

## 🆘 Решение проблем

### Приложение не запускается / ошибка 500

**Решение:**
1. Проверьте **Logs** на странице Web Service
2. Проверьте переменные окружения (особенно DB_HOST, DB_PASSWORD)
3. Убедитесь, что БД создана и инициализирована
4. Попробуйте нажать **"Redeploy"**

### Не могу подключиться к БД

**Решение:**
1. Проверьте, что БД статус **"Available"**
2. Проверьте Connection String и переменные окружения
3. Убедитесь, что используете правильное имя БД и пользователя
4. Попробуйте подключиться из Shell'а Web Service'а

### Файлы загрузок не сохраняются

**Решение:** Render имеет ограничения на хранилище. Рекомендуется:
1. Использовать S3 (AWS) или похожий сервис для файлов
2. Или использовать более мощный тариф на Render

---

## 📚 Полезные ссылки

- [Render Dashboard](https://dashboard.render.com)
- [Render Documentation](https://render.com/docs)
- [CMS Documentation](INDEX.md)
- [API Documentation](API_DOCUMENTATION.md)

---

**Поздравляем! 🎉 Ваше приложение готово к работе!**

По любым вопросам обратитесь к документации или свяжитесь с командой разработки.
