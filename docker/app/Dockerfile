FROM php:7-fpm
RUN apt-get update
RUN apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql
RUN apt-get install -y libzip-dev zip unzip libonig-dev
RUN docker-php-ext-install mbstring

ENV TZ='Asia/Tokyo'