ARG PHP_VERSION=8.4
FROM php:${PHP_VERSION}-cli

# git and unzip are needed by composer to install packages.
# The official PHP image already ships dom, mbstring, xml, xmlwriter, tokenizer, json and curl.
ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN install-php-extensions @composer zip

COPY .docker/custom.ini /usr/local/etc/php/conf.d/custom.ini

# The container runs as the host user (see docker-compose.yml)
ENV COMPOSER_HOME=/tmp/composer \
    COMPOSER_CACHE_DIR=/tmp/composer/cache

WORKDIR /app
