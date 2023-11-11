# Use the official PHP image with version 8.1
FROM php:8.1-fpm

# Set the working directory in the container
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && \
    apt-get install -y \
    libzip-dev \
    unzip \
    libonig-dev \
    libxml2-dev

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql zip mbstring exif pcntl bcmath opcache

# Copy composer.lock and composer.json to install dependencies
COPY composer.lock composer.json /var/www/html/

# Install composer dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-scripts --no-autoloader

# Copy the application files to the container
COPY . /var/www/html

# Generate autoload files
RUN composer dump-autoload --optimize

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]