FROM php:8.2-apache

# 1. Устанавливаем нужные расширения для MySQL и SQLite
RUN apt-get update && apt-get install -y default-mysql-client libsqlite3-dev && \
    docker-php-ext-install pdo_mysql pdo_sqlite

# 2. Копируем файлы проекта
COPY . /var/www/html/

# 3. Копируем статические файлы из public в корневой веб-каталог
RUN cp -r /var/www/html/public/css /var/www/html/css && \
    cp -r /var/www/html/public/js /var/www/html/js && \
    cp -r /var/www/html/public/uploads /var/www/html/uploads

# 4. Создаем папки и даем права (чтобы не было ошибки Logger.php)
RUN mkdir -p /var/www/html/storage/logs && \
    chmod -R 777 /var/www/html/storage && \
    chmod -R 777 /var/www/html/public/uploads && \
    chmod -R 777 /var/www/html/uploads

# 4. Включаем модуль Apache Rewrite (часто нужен для PHP сайтов)
RUN a2enmod rewrite

# 5. Устанавливаем рабочую директорию для Apache
ENV APACHE_DOCUMENT_ROOT=/var/www/html
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 6. Устанавливаем .htaccess для корректной маршрутизации
RUN echo '<Directory /var/www/html>\n  Options -MultiViews\n  RewriteEngine On\n  RewriteCond %{REQUEST_FILENAME} !-f\n  RewriteCond %{REQUEST_FILENAME} !-d\n  RewriteRule ^ index.php [QSA,L]\n</Directory>' > /etc/apache2/conf-available/rewrite.conf && \
    a2enconf rewrite

EXPOSE 80