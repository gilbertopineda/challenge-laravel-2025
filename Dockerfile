FROM php:8.2-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    git \
    unzip \
    libpq-dev \
    bash \
    curl \
    icu-dev \
    oniguruma-dev \
    autoconf \
    g++ \
    make

# Install required PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql mbstring bcmath intl

# Install Redis extension (optional if you use Redis for cache/queue)
RUN pecl install redis \
    && docker-php-ext-enable redis

WORKDIR /var/www
