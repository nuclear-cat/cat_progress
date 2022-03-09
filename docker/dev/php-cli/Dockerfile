FROM php:8.1-cli-alpine

RUN apk add --no-cache unzip

ENV XDEBUG_VERSION 3.1.1

RUN apk add --no-cache  \
        postgresql-dev  \
        fcgi  \
        git  \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && git clone --branch $XDEBUG_VERSION --depth 1 https://github.com/xdebug/xdebug.git /usr/src/php/ext/xdebug \
    && docker-php-ext-configure xdebug --enable-xdebug-dev \
    && docker-php-ext-install pdo_pgsql xdebug \
    && apk del --no-cache git \
    && rm -rf /tmp/*

# Setup GD extension
RUN apk add --no-cache \
      freetype \
      libjpeg-turbo \
      libpng \
      freetype-dev \
      libjpeg-turbo-dev \
      libpng-dev \
    && docker-php-ext-configure gd \
      --with-freetype=/usr/include/ \
      --with-jpeg=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-enable gd \
    && apk del --no-cache \
      freetype-dev \
      libjpeg-turbo-dev \
      libpng-dev \
    && rm -rf /tmp/*

RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

#COPY ./common/php/conf.d /usr/local/etc/php/conf.d
#COPY ./development/php/conf.d /usr/local/etc/php/conf.d

ENV COMPOSER_ALLOW_SUPERUSER 1

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer --quiet

WORKDIR /app