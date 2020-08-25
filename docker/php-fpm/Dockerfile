FROM php:7.4-fpm as base

RUN apt update && apt install -y \
    curl \
    git \
    libzip-dev libpq-dev \
    unzip \
    && apt clean

RUN docker-php-ext-install zip pdo pdo_pgsql pdo_mysql \
    && docker-php-ext-configure opcache --enable-opcache \
    && docker-php-ext-install opcache \
    && docker-php-source delete

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN composer global require hirak/prestissimo --no-progress
