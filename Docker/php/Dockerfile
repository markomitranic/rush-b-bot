FROM php:7.2.8-fpm AS base

RUN apt-get update
RUN apt-get install wget -y
RUN docker-php-ext-install pdo_mysql

WORKDIR /usr/share/nginx/rush-b

## DEVELOPMENT
FROM base AS dev

COPY conf-dev/php-fpm.conf /usr/local/etc/php-fpm.d/zzz-rush-b-fpm.conf
COPY conf-dev/php.ini /usr/local/etc/php/conf.d/rush-b-php.ini
COPY ssl/certs /usr/local/etc/ssl
