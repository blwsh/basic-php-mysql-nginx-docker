FROM php:7.3-fpm-alpine

# Build Args
ARG ENVIRONMENT

# Install extensions
RUN apk update && apk add libzip-dev \
    && docker-php-ext-install zip \
    && docker-php-ext-install pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

# Set WORKDIR
WORKDIR /src

# Set up the application
COPY src/ /src/
COPY etc/php.ini /usr/local/etc/php/php.ini

# Permissions fix
RUN chown -R www-data:www-data /src && chmod -R 755 /src

# Ports
EXPOSE 80
