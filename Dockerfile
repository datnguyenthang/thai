# Use the official PHP image as the base image
FROM php:8.2.5-fpm-alpine

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    libzip-dev \
    zip \
    unzip \
    libpq-dev

# Clear the default web directory
RUN rm -rf /var/www/html

# Set the working directory
WORKDIR /var/www

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy the project files to the container
COPY . /var/www

# Install Composer dependencies
RUN composer install --no-interaction

# Expose port 9000
EXPOSE 9000

# Start the PHP-FPM server
CMD ["php-fpm"]

