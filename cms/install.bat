# Установка кроссплатформенно
# После установки, обновите конфиг и импортируйте esquema БД

@echo off
echo ======================================
echo     CMS System Installation
echo ======================================
echo.

REM Проверка php
where php >nul 2>nul
if %errorlevel% neq 0 (
    echo Error: PHP не установлен или не в PATH
    exit /b 1
)

echo Создание директорий...
mkdir storage\logs 2>nul
mkdir storage\backups 2>nul
mkdir public\uploads 2>nul
mkdir database 2>nul

echo.
echo ======================================
echo     Установка завершена!
echo ======================================
echo.
echo Следующие шаги:
echo 1. Отредактируйте config/database.php
echo 2. Импортируйте schema: mysql -u root -p cms_db less database/schema.sql
echo 3. Создайте админ-пользователя
echo 4. Откройте http://localhost/cms/public
echo.
pause
