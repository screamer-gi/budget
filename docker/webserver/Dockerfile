FROM composer AS composer

FROM php:7.4-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends zlib1g-dev git

RUN pecl install xdebug && \
    docker-php-ext-install pdo_mysql zip && \
    docker-php-ext-enable xdebug

COPY ./php.ini /usr/local/etc/php/
COPY ./budget.conf /etc/apache2/sites-enabled

RUN a2enmod rewrite \
            headers

COPY --from=composer /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER 1
# todo: add composer and vendor/bin to default path

WORKDIR /var/www/budget
