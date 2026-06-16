# API Documentation

## REST API Endpoints

### Медиа (Media)

#### Получение всех медиафайлов
```
GET /api/media
```

Пример ответа:
```json
[
    {
        "id": 1,
        "filename": "image123.jpg",
        "original_name": "photo.jpg",
        "mime_type": "image/jpeg",
        "size": 102400,
        "uploaded_by": 1,
        "uploaded_at": "2026-03-30 14:30:00"
    }
]
```

#### Получение конкретного медиафайла
```
GET /api/media/{id}
```

Пример:
```
GET /api/media/1
```

Ответ:
```json
{
    "id": 1,
    "filename": "image123.jpg",
    "original_name": "photo.jpg",
    "mime_type": "image/jpeg",
    "size": 102400,
    "uploaded_by": 1,
    "uploaded_at": "2026-03-30 14:30:00"
}
```

### Загрузка медиафайла

#### Загрузить файл
```
POST /admin/media/upload
Content-Type: multipart/form-data

file: <файл>
csrf_token: <токен>
```

Пример через curl:
```bash
curl -X POST http://localhost/admin/media/upload \
  -H "Cookie: PHPSESSID=..." \
  -F "file=@image.jpg" \
  -F "csrf_token=..."
```

Успешный ответ:
```json
{
    "success": true,
    "id": 1,
    "message": "Файл загружен успешно"
}
```

### Удаление медиафайла

#### Удалить файл
```
POST /admin/media/{id}/delete
```

Пример:
```bash
curl -X POST http://localhost/admin/media/1/delete \
  -H "Content-Type: application/json"
```

Ответ:
```json
{
    "success": true,
    "message": "Файл удалён"
}
```

## JavaScript примеры

### Получение медиафайлов

```javascript
fetch('/api/media')
    .then(response => response.json())
    .then(files => {
        files.forEach(file => {
            console.log(file.original_name);
        });
    });
```

### Загрузка файла

```javascript
const formData = new FormData();
formData.append('file', document.getElementById('fileInput').files[0]);
formData.append('csrf_token', document.querySelector('input[name="csrf_token"]').value);

fetch('/admin/media/upload', {
    method: 'POST',
    body: formData
})
.then(response => response.json())
.then(data => {
    if (data.success) {
        console.log('Файл загружен с ID:', data.id);
    } else {
        console.error('Ошибка:', data.error);
    }
});
```

### Удаление файла

```javascript
fetch('/admin/media/5/delete', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json'
    }
})
.then(response => response.json())
.then(data => {
    if (data.success) {
        console.log('Файл удалён');
    }
});
```

## PHP примеры

### Получение данных о медиа

```php
<?php
$curl = curl_init('http://localhost/api/media');
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);
$media = json_decode($response, true);

foreach ($media as $file) {
    echo $file['original_name'];
}
?>
```

## Коды ошибок

| Код | Описание |
|-----|---------|
| 200 | OK - Успешно |
| 400 | Bad Request - Неверный запрос |
| 401 | Unauthorized - Требуется аутентификация |
| 403 | Forbidden - Доступ запрещен |
| 404 | Not Found - Не найдено |
| 500 | Server Error - Ошибка сервера |

## Безопасность

- Все запросы должны содержать валидный CSRF токен
- Требуется аутентификация для большинства операций
- Все данные должны быть корректно экранированы

## Лимиты

- Максимальный размер файла: 50MB (по умолчанию)
- Максимум одновременных запросов: 100
- Поддерживаемые типы файлов: JPG, PNG, GIF, PDF, MP4, WebM
