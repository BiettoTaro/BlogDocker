FROM php:8.2-fpm

# Install system dependencies and PostgreSQL extension
RUN apt-get update && apt-get install -y \
    build-essential \
    libpq-dev \
    zip \
    unzip

# Install PHP extensions: pdo_pgsql and pcntl
RUN docker-php-ext-install pdo_pgsql pcntl \
    && pecl install redis \
    && docker-php-ext-enable redis

# Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

# Install system dependencies including libexif-dev
RUN apt-get update && apt-get install -y libexif-dev

# Install the exif extension
RUN docker-php-ext-install exif


WORKDIR /var/www

# Copy application files
COPY . .

# Install PHP dependencies with Composer
RUN composer install

CMD ["php-fpm"]

EXPOSE 9000
