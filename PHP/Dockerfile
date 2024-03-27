FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    apt-utils \
    libpq-dev \
    libpng-dev \
    libzip-dev \
    zip unzip \
    git && \
    docker-php-ext-install pdo_mysql && \
    docker-php-ext-install bcmath && \
    docker-php-ext-install gd && \
    docker-php-ext-install zip && \
    pecl install xdebug-3.3.0 && \
    docker-php-ext-enable xdebug && \
    apt-get clean && \
    rm -rf /vat/lib/apt/lists/* /tmp/* /var/tmp/*

COPY ./PHP/php.ini /usr/local/etc/php/conf.d/php.ini
COPY ./PHP/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

#Composer install
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- \
    --filename=composer \
    --install-dir=/usr/local/bin

WORKDIR /var/www

