# Use the official CentOS 7 image as the base
FROM centos:7

# Set the working directory inside the container
WORKDIR /var/www/html

# Install required packages
RUN yum -y update && \
    yum -y install epel-release && \
    yum -y install http://rpms.remirepo.net/enterprise/remi-release-7.rpm && \
    yum -y install yum-utils && \
    yum-config-manager --enable remi-php82 && \
    yum -y install php php-common php-opcache php-mcrypt php-cli php-gd php-curl php-mysqlnd && \
    yum -y install curl git unzip && \
    yum -y install nginx && \
    systemctl enable nginx

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy the Laravel project files to the container
COPY . /var/www/html

# Install project dependencies
RUN composer install --no-interaction

# Copy Nginx configuration file
COPY nginx.conf /etc/nginx/nginx.conf

# Expose port 80 for Nginx
EXPOSE 8080

# Start Nginx and PHP-FPM services
CMD ["nginx", "-g", "daemon off;"]
