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
	curl \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip 
	
# Install Docker Compose
RUN curl -L "https://github.com/docker/compose/releases/download/1.29.2/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
RUN chmod +x /usr/local/bin/docker-compose

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
