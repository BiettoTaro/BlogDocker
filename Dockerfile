# Dockerfile

FROM php:8.2-fpm

# 1) System deps, PHP extensions (pgsql, pcntl, exif) + Redis
RUN apt-get update \
 && apt-get upgrade -y \
 && apt-get install -y --no-install-recommends \
      build-essential \
      libpq-dev \
      libexif-dev \
      zip \
      unzip \
      git \
 && docker-php-ext-install pdo_pgsql pcntl exif \
 && pecl install redis \
 && docker-php-ext-enable redis \
 && rm -rf /var/lib/apt/lists/*

# 2) Install Composer
RUN php -r "copy('https://getcomposer.org/installer','composer-setup.php');" \
 && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
 && php -r "unlink('composer-setup.php');"

WORKDIR /var/www

# 3) Copy your app code (including artisan)
COPY . .

# 4) Install PHP deps & Sentry, publish config, cache
RUN composer require sentry/sentry-laravel \
 && composer install --no-dev --optimize-autoloader \
 && php artisan vendor:publish --provider="Sentry\Laravel\ServiceProvider" --tag=config \
 && php artisan config:cache

# 5) Fix permissions
RUN chown -R www-data:www-data /var/www \
 && chmod -R 755 /var/www/storage

EXPOSE 9000
CMD ["php-fpm"]
