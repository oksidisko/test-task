ARG PHP_VERSION=7.4
ARG PHP_EXTENSION_VERSION=1.2.28

FROM php:${PHP_VERSION}-fpm-alpine

WORKDIR /app

COPY --from=mlocati/php-extension-installer:1.2.28 /usr/bin/install-php-extensions /usr/bin/install-php-extensions
COPY ./docker/app/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
COPY ./docker/app/php.ini /usr/local/etc/php/php.ini
COPY ./docker/app/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# для нормального запуска composer сразу после развертывании докера
COPY --chown=www-data:www-data ./bin ./bin
COPY --chown=www-data:www-data ./config ./config
COPY --chown=www-data:www-data ./migrations ./migrations
COPY --chown=www-data:www-data ./public ./public
COPY --chown=www-data:www-data ./src ./src
COPY --chown=www-data:www-data ./.env ./
COPY --chown=www-data:www-data ./composer.json ./
COPY --chown=www-data:www-data ./composer.lock ./
COPY --chown=www-data:www-data ./symfony.lock ./

RUN chmod +x /usr/local/bin/docker-entrypoint && \
    apk add --no-cache git composer autoconf automake gawk build-base && \
    apk add --no-cache --virtual .build-deps autoconf alpine-sdk && \
    apk del .build-deps && \
    install-php-extensions pdo_mysql intl zip imagick simplexml soap gd bcmath xdebug
RUN composer install --optimize-autoloader --no-progress
RUN chown www-data:www-data -R /app

USER www-data

ENTRYPOINT ["docker-entrypoint"]
