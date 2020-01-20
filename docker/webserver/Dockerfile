FROM composer AS composer


FROM php:7.0-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends libicu-dev default-jre libtidy-dev zlib1g-dev git \
    && rm -r /var/lib/apt/lists/*

RUN pecl install xdebug && \
    docker-php-ext-configure intl && \
    docker-php-ext-install pdo_mysql intl zip && \
    docker-php-ext-enable xdebug

COPY ./php.ini /usr/local/etc/php/
COPY ./budget.conf /etc/apache2/sites-enabled

RUN a2enmod rewrite \
            headers

COPY --from=composer /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER 1

WORKDIR /var/www/budget