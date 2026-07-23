FROM php:8.2-apache

# 1. Install system dependencies & PHP extensions required by Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    curl \
    git \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Enable Apache rewrite module for Laravel clean URLs
RUN a2enmod rewrite

# 3. Configure Apache DocumentRoot to /var/www/html/public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 4. Allow .htaccess overrides in /var/www/html/public
RUN echo '<Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' >> /etc/apache2/apache2.conf

# 5. Copy official Composer binary
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 6. Set Working Directory
WORKDIR /var/www/html

# 7. Copy project source code
COPY . .

# 8. Install PHP dependencies for production
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 9. Set permissions for Laravel storage and cache directories
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 10. Expose default port
EXPOSE 80

# 11. Handle Render dynamic $PORT and start Apache
CMD ["sh", "-c", "sed -i \"s/Listen 80/Listen ${PORT:-80}/g\" /etc/apache2/ports.conf && sed -i \"s/<VirtualHost \\*:80>/<VirtualHost \\*:${PORT:-80}>/g\" /etc/apache2/sites-available/*.conf && apache2-foreground"]
