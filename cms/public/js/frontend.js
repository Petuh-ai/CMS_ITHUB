// Frontend JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Улучшенная демонстрация загрузки
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('fileInput');

    if (uploadArea && fileInput) {
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.style.backgroundColor = '#f0f0f0';
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.style.backgroundColor = 'transparent';
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            fileInput.files = e.dataTransfer.files;
        });
    }

    // Валидация комментариев
    const commentForm = document.querySelector('.comment-form');
    if (commentForm) {
        commentForm.addEventListener('submit', function(e) {
            const content = this.querySelector('textarea[name="content"]').value;
            if (content.trim().length < 10) {
                e.preventDefault();
                alert('Комментарий должен содержать минимум 10 символов');
            }
        });
    }
});

// Mobile navigation toggle
document.addEventListener('DOMContentLoaded', function() {
    const navToggle = document.getElementById('navToggle');
    const mainNav = document.getElementById('mainNav');
    if (navToggle && mainNav) {
        navToggle.addEventListener('click', () => {
            mainNav.classList.toggle('open');
            navToggle.classList.toggle('open');
        });
    }
});

// Функция удаления медиафайла
function deleteMedia(id) {
    if (!confirm('Удалить этот файл?')) return;
    
    fetch('/admin/media/' + id + '/delete', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }
    }).then(response => response.json())
      .then(data => {
          if (data.success) {
              location.reload();
          } else {
              alert('Ошибка при удалении файла');
          }
      });
}
