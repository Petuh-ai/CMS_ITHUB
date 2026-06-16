@echo off
cd /d "%~dp0"
if not exist ".env" (
  echo.
  echo ERROR: Файл .env не найден.
  echo Скопируйте .env.local.example в .env и настройте подключение к базе.
  echo.
) else (
  echo Запуск локального сервера на http://localhost:8000
)
php -S localhost:8000 router.php
