FROM php:5.6-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends libicu-dev default-jre libtidy-dev \
    && rm -r /var/lib/apt/lists/*

RUN pecl install intl xdebug && \
    docker-php-ext-install pdo_mysql intl && \
    docker-php-ext-enable xdebug

COPY ./php.ini /usr/local/etc/php/
COPY ./budget.conf /etc/apache2/sites-enabled

RUN a2enmod rewrite \
            headers
