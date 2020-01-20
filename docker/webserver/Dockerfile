FROM composer AS composer

FROM php:7.4-apache

RUN pecl install xdebug && \
    docker-php-ext-install pdo_mysql && \
    docker-php-ext-enable xdebug

COPY ./php.ini /usr/local/etc/php/
COPY ./budget.conf /etc/apache2/sites-enabled

RUN a2enmod rewrite \
            headers

COPY --from=composer /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER 1
# todo: add composer and vendor/bin to default path

WORKDIR /var/www/budget
