FROM php:8.4-fpm-alpine

# Build deps + runtime deps yang umum buat Laravel
RUN apk add --no-cache \
  $PHPIZE_DEPS \
  bash curl git unzip \
  icu-dev oniguruma-dev libzip-dev \
  && docker-php-ext-install -j"$(nproc)" \
    pdo_mysql intl mbstring zip \
  && docker-php-ext-enable opcache || true

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html