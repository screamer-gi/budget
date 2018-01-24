FROM php:5.6-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends php5-intl libicu-dev default-jre libtidy-dev \
    && rm -r /var/lib/apt/lists/*

COPY ./php.ini /usr/local/etc/php/
COPY ./budget.conf /etc/apache2/sites-enabled

RUN a2enmod rewrite \
            headers

RUN pecl install intl xdebug && \
    docker-php-ext-install pdo_mysql mysqli && \
    docker-php-ext-enable xdebug
