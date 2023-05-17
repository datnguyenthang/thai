# Use the official PHP 8.2.5 image as the base image
FROM php:8.2.5-fpm-alpine

RUN yum update -y && yum install -y \
    zip \
    unzip \
    libzip-dev

RUN docker-php-ext-install pdo_mysql zip

COPY . /var/www/html

RUN chown -R apache:apache /var/www/html \
    && chmod -R 755 /var/www/html \
    && echo "ServerName localhost" >> /etc/httpd/conf/httpd.conf \
    && ln -sf /dev/stdout /var/log/httpd/access_log \
    && ln -sf /dev/stderr /var/log/httpd/error_log

CMD ["/usr/sbin/httpd", "-D", "FOREGROUND"]


