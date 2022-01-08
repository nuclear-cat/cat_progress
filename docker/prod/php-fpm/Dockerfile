FROM php:8.1-fpm-alpine

RUN apk add --no-cache postgresql-dev fcgi git \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo_pgsql \
    && apk del git

RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

#COPY ./common/php/conf.d /usr/local/etc/php/conf.d
#COPY ./common/php/php-fpm.d /usr/local/etc/php-fpm.d
#COPY ./development/php/conf.d /usr/local/etc/php/conf.d
#COPY ./development/php-fpm/conf.d /usr/local/etc/php/conf.d

WORKDIR /app
