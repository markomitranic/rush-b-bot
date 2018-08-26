FROM php:7.2.8-fpm AS base

RUN apt-get update
RUN apt-get install wget -y
RUN docker-php-ext-install pdo_mysql
RUN apt-get install -y libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libwebp-dev
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ \
        --with-webp-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
        --with-png-dir=/usr/include/ --with-zlib-dir=/usr/ \
    && docker-php-ext-install gd

WORKDIR /usr/share/nginx/rush-b

## DEVELOPMENT
FROM base AS dev

COPY conf-dev/php-fpm.conf /usr/local/etc/php-fpm.d/zzz-rush-b-fpm.conf
COPY conf-dev/php.ini /usr/local/etc/php/conf.d/rush-b-php.ini
COPY ssl/certs /usr/local/etc/ssl

FROM base AS prod

COPY conf-prod/php-fpm.conf /usr/local/etc/php-fpm.d/zzz-rush-b-fpm.conf
COPY conf-prod/php.ini /usr/local/etc/php/conf.d/rush-b-php.ini
