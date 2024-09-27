# Используем официальный образ PHP с Apache
FROM php:8.2-fpm

# Устанавливаем необходимые расширения PHP
RUN docker-php-ext-install pdo pdo_mysql

# Копируем файлы проекта в контейнер
COPY . /var/www/html

# Настраиваем права доступа
RUN chown -R www-data:www-data /var/www/html
