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

# Install xdebug
ENV XDEBUG_VERSION 2.9.6
ENV XDEBUG_SHA256 b0f3283aa185c23fcd0137c3aaa58554d330995ef7a3421e983e8d018b05a4a6

RUN set -x \
	&& curl -SL "http://www.xdebug.org/files/xdebug-$XDEBUG_VERSION.tgz" -o xdebug.tgz \
	&& mkdir -p /usr/src/xdebug \
	&& tar -xf xdebug.tgz -C /usr/src/xdebug --strip-components=1 \
	&& rm xdebug.* \
	&& cd /usr/src/xdebug \
	&& phpize \
	&& ./configure \
	&& make -j"$(nproc)" \
	&& make install \
	&& make clean

RUN echo "zend_extension=xdebug.so \n\r \
xdebug.remote_enable=1 \n\r \
xdebug.remote_autostart=1 \n\r \
xdebug.remote_connect_back=0" >> /usr/local/etc/php/conf.d/ext-xdebug.ini

# Initialize user
RUN addgroup --gid=$GID appuser \
    && useradd -r -u $UID -g appuser appuser \
    && mkdir -p /home/appuser \
    && chown appuser:appuser /home/appuser

RUN mkdir -p public/storage \
    && ln -s /app/storage/app/public /app/public/storage/files

USER appuser
