# Use the official PHP 8.2.5 image as the base image
FROM php:8.2.5-fpm-alpine

RUN apk update && apk add --no-cache \
    zip \
    unzip \
    libzip-dev

RUN docker-php-ext-install pdo_mysql zip

COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && echo "ServerName localhost" >> /etc/apache2/httpd.conf

CMD ["httpd", "-D", "FOREGROUND"]


