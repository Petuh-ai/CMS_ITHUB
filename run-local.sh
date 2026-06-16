#!/bin/sh
cd "$(dirname "$0")"
if [ ! -f .env ]; then
  echo "ERROR: Файл .env не найден."
  echo "Скопируйте .env.local.example в .env и настройте подключение к базе."
fi

echo "Запуск локального сервера на http://localhost:8000"
php -S localhost:8000 router.php
