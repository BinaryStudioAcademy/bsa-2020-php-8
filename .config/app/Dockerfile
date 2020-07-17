FROM php:7.4.7-fpm

WORKDIR /app

ARG UID=1000
ARG GID=1000

RUN apt-get update && apt-get install -y \
        libjpeg62-turbo-dev \
        libpng-dev \
        libmagickwand-dev --no-install-recommends \
    && pecl install imagick \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && docker-php-ext-configure gd \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-enable imagick \
    && docker-php-ext-install pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN addgroup --gid=$GID appuser \
    && useradd -r -u $UID -g appuser appuser \
    && mkdir -p /home/appuser \
    && chown appuser:appuser /home/appuser

RUN mkdir -p public/storage \
    && ln -s /app/storage/app/public /app/public/storage/files

USER appuser
