# Base image
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpng-dev \
    zip \
    unzip \
    git \
    openssl \
    curl \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip 

# Install Composer
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer

# Copy the application files
COPY . /var/www/html

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Install Laravel dependencies
RUN composer install
RUN composer dump-autoload --optimize

# Generate key
RUN php artisan key:generate

# Expose port
#EXPOSE 8080

# Run Laravel application
CMD php artisan serve --host=0.0.0.0 --port=80
