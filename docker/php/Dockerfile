FROM php:8.0.8-fpm

RUN apt-get update && apt-get install -y \
        zlib1g-dev \
        libicu-dev \
        libxml2 \
        libpq-dev \
        libc-client-dev \
        libjpeg-dev \
        libpng-dev \
        libkrb5-dev \
        g++ \
        git \
        zip \
        libzip-dev

RUN docker-php-ext-configure gd --with-jpeg
RUN docker-php-ext-install -j$(nproc) gd pdo pdo_pgsql zip exif

RUN curl -sS https://getcomposer.org/installer -o composer-setup.php \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
