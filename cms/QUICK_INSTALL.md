# ⚡ БЫСТРАЯ УСТАНОВКА (2 варианта)

## 🏃 ВАРИАНТ 1: Без установки чего-либо (САМЫЙ БЫСТРЫЙ - 2 минуты!)

**Если у вас уже установлены PHP и MySQL:**

### Шаг 1: Проверьте наличие PHP
Откройте CMD и введите:
```bash
php --version
```

Должно вывести версию PHP. Если нет - установите PHP с https://windows.php.net/

### Шаг 2: Создайте БД

Откройте CMD и введите:
```bash
mysql -u root -p
```

Затем скопируйте-вставьте содержимое файла `database/schema.sql`

### Шаг 3: Запустите встроенный веб-сервер PHP

Откройте PowerShell в папке `cms` (Shift + правый клик):
```bash
php -S localhost:8000 -t public
```

### Шаг 4: Откройте в браузере
```
http://localhost:8000
Email: admin@example.com
Пароль: admin123
```

✅ **ГОТОВО! CMS работает!**

---

## 🚀 ВАРИАНТ 2: С установкой XAMPP (5 минут)

### Шаг 1: Скачайте и установите XAMPP
- Откройте: https://www.apachefriends.org/
- Нажмите **Download XAMPP**
- Установите в папку по умолчанию
- Запустите `xampp-control.exe`
- Нажмите **Start** на Apache и MySQL

### Шаг 2: Скопируйте CMS
Скопируйте папку `cms` в:
```
C:\xampp\htdocs\
```

### Шаг 3: Создайте БД через phpMyAdmin
- Откройте в браузере: http://localhost/phpmyadmin
- Нажмите вкладку **SQL**
- Скопируйте содержимое `database/schema.sql`
- Вставьте и нажмите **Выполнить**

### Шаг 4: Откройте CMS
```
http://localhost/cms/public
Email: admin@example.com
Пароль: admin123
```

✅ **ГОТОВО! CMS работает!**

---

## 🐛 ПРОВЕРКА

Если что-то не работает, откройте CMD в папке `cms`:
```bash
php public/test.php
```

Это покажет все проблемы.

---

**ДАЛЬШЕ?** Откройте файл **QUICK_START.md**
