# Use the official PHP 8.2.5 image as the base image
FROM php:8.2.5-fpm-alpine

# Set the working directory in the container
WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip

# Copy composer.lock and composer.json
COPY composer.lock composer.json ./

# Install composer dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-scripts

# Copy the rest of the application code
COPY . .

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage

# Expose port 9000 and start PHP-FPM server
EXPOSE 9000
CMD ["php-fpm"]

