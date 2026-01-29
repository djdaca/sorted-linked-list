FROM php:8.4-cli-alpine

RUN apk add --no-cache \
    bash \
    git \
    curl \
    unzip \
    oniguruma-dev \
    libxml2-dev \
    linux-headers \
    $PHPIZE_DEPS

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY usr/local/etc/php/php.ini /usr/local/etc/php/php.ini

RUN pecl install xdebug && docker-php-ext-enable xdebug

WORKDIR /app
VOLUME /app