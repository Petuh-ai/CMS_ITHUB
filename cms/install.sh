#!/bin/bash

# Установочный скрипт для CMS System
# Запуск: ./install.sh

echo "======================================"
echo "    CMS System Installation"
echo "======================================"
echo ""

# Проверка PHP версии
echo "Проверка PHP версии..."
required_php_version="7.4"
installed_php_version=$(php -r 'echo phpversion();')

if [ "$(printf '%s\n' "$required_php_version" "$installed_php_version" | sort -V | head -n1)" = "$required_php_version" ]; then 
    echo "✓ PHP версия $installed_php_version (требуется $required_php_version+)"
else
    echo "✗ Требуется PHP версия $required_php_version или выше"
    exit 1
fi

echo ""
echo "Создание необходимых директорий..."

# Создание директорий
mkdir -p storage/logs
mkdir -p storage/backups
mkdir -p public/uploads
mkdir -p database

# Установка прав доступа
echo "Установка прав доступа..."
chmod 755 public/
chmod 755 storage/
chmod 755 storage/logs/
chmod 755 storage/uploads/
chmod 755 storage/backups/
chmod 755 database/

# Проверка composer
echo ""
echo "Проверка Composer..."
if command -v composer &> /dev/null; then
    echo "✓ Composer установлен"
    composer install
else
    echo "⚠ Composer не установлен. Пропуск установки зависимостей."
fi

echo ""
echo "======================================"
echo "    Установка завершена!"
echo "======================================"
echo ""
echo "Следующие шаги:"
echo "1. Отредактируйте config/database.php с вашими данными MySQL"
echo "2. Импортируйте схему БД: mysql -u root -p cms_db < database/schema.sql"
echo "3. Создайте админ-пользователя"
echo "4. Откройте http://localhost/cms/public в браузере"
echo ""
