# 🚀 Быстрый старт на Render

## Что было сделано для совместимости с Render:

✅ **Dockerfile** - обновлен для корректной работы с Apache и маршрутизацией
✅ **Конфигурация БД** - теперь использует переменные окружения  
✅ **Конфигурация приложения** - добавлена поддержка ENV переменных
✅ **.env.example** - пример файла с необходимыми переменными
✅ **RENDER_DEPLOYMENT.md** - полная инструкция по развертыванию
✅ **render.yaml** - конфиг для One-Click Deploy

## Максимально быстрое развертывание (2-3 минуты):

### 1️⃣ Подготовка
```bash
# Загрузите код на GitHub
git push origin main
```

### 2️⃣ На Render.com
1. Создайте **MySQL базу**: New → MySQL
2. Создайте **Web Service**: New → Web Service → выберите GitHub репо
3. Добавьте Environment переменные из `.env.example`
4. Deploy! 🎉

### 3️⃣ Переменные окружения (скопируйте из MySQL подключения):

```
DB_HOST=***.mysql.render.com
DB_PORT=3306
DB_NAME=cms_bd
DB_USER=cms_user
DB_PASSWORD=<пароль>
APP_DEBUG=false
SESSION_SECURE=true
```

## Файлы для развертывания:

| Файл | Назначение |
|------|-----------|
| `Dockerfile` | Docker образ приложения |
| `.env.example` | Пример переменных окружения |
| `render.yaml` | Конфиг для автодеплоя (опционально) |
| `RENDER_DEPLOYMENT.md` | Подробная инструкция |

## ⚠️ Важно для production:

- **Временное хранилище**: На Render файлы загрузок не сохраняются между перезагрузками
  → Используйте облачное хранилище (S3, Cloudinary)

- **Спящий режим**: На бесплатном плане сервис переходит в спящий режим
  → Используйте платный план для production

- **Безопасность**: Убедитесь, что `APP_DEBUG=false` в production

## Полезные ссылки:

- [Render Documentation](https://render.com/docs)
- [PHP на Docker](https://hub.docker.com/_/php)
- [MySQL на Render](https://render.com/docs/deploy-mysql)

---

**Готово! Ваш проект готов для развертывания на Render.**

Вопросы? Смотрите подробную инструкцию в `RENDER_DEPLOYMENT.md`
