# Use the official PHP image as the base image
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    && docker-php-ext-install pdo pdo_mysql intl opcache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy existing application directory contents
COPY . /var/www

# Install Symfony dependencies
RUN COMPOSER_ALLOW_SUPERUSER composer install

# Set permissions for Symfony
RUN chown -R www-data:www-data /var/www/html/var /var/www/html/public

# Expose port 9000 and start php-fpm server
EXPOSE 8000
CMD ["php-fpm"]