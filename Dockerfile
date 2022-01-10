FROM php:7.2-fpm-alpine3.12
WORKDIR /var/www
RUN docker-php-ext-install pdo_mysql && docker-php-ext-enable pdo_mysql
