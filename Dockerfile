# Use the official PHP image as the base
FROM php:7.4-fpm-alpine

# Set the working directory in the container
WORKDIR /var/www/html

# Install PHP extensions and dependencies
RUN docker-php-ext-install pdo_mysql

# Copy the application files to the container
COPY . .

# Install Composer dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --optimize-autoloader --no-dev

# Set permissions for storage and bootstrap cache folders
RUN chown -R www-data:www-data storage bootstrap/cache

# Expose port 8080
EXPOSE 8080

# Run the Laravel application
CMD php artisan serve --host=0.0.0.0 --port=80
