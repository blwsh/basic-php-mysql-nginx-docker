# Setup
FROM composer

FROM php:7.3-fpm-alpine

# Intall composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Build Args
ARG ENVIRONMENT

# Install extensions
RUN apk update && apk add libzip-dev php7-pecl-apcu php7-opcache \
    && docker-php-ext-install zip \
    && docker-php-ext-install opcache \
    && docker-php-ext-install pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

# Set WORKDIR
WORKDIR /src

# Set up the application
COPY src/ /src/
COPY etc/php.ini /usr/local/etc/php/php.ini

# Permissions fix
RUN chown -R www-data:www-data . && chmod -R 755 .

# Ports
EXPOSE 80
