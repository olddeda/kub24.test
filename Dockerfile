FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev \
    unzip \
    nginx \
    supervisor \
    postgresql-client \
    redis-tools \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql mbstring exif pcntl bcmath gd zip \
    && pecl install redis \
    && docker-php-ext-enable redis

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents with proper ownership
COPY --chown=www-data:www-data . /var/www

# Make scripts executable
RUN chmod +x /var/www/docker/scripts/*.sh

# Copy nginx configuration
COPY docker/nginx/default.conf /etc/nginx/sites-available/default

# Copy supervisor configuration
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copy PHP configuration
COPY docker/php/php.ini /usr/local/etc/php/php.ini

# Create runtime and assets directories and set permissions
RUN mkdir -p /var/www/runtime /var/www/runtime/debug /var/www/web/assets \
    && chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/web \
    && chmod -R 777 /var/www/runtime \
    && chmod -R 777 /var/www/web/assets

# Expose port 80
EXPOSE 80

# Start application with initialization
CMD ["/var/www/docker/scripts/start.sh"]
