# Base image
FROM php:8.2-fpm

# Set working directory
WORKDIR /app

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
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip 

COPY . /app

# Set permissions
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Install Laravel dependencies
RUN composer install --optimize-autoloader --no-dev

# Generate key
RUN php artisan key:generate

RUN php artisan serve --host=0.0.0.0 --port=8080

# Expose port
EXPOSE 8080

# Run Laravel application
CMD ["php-fpm"]
