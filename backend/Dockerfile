FROM php:7-alpine

RUN mkdir /app

WORKDIR /app

# Install composer for dependency installation
RUN php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/local/bin --filename=composer \
    && chmod +x /usr/local/bin/composer

COPY ./ /app

ENV COMPOSER_ALLOW_SUPERUSER=1

# This env var is used in backend's broadcaster to retrieve host of the Crossbar instance.
ENV DOCKER_SETUP=1

RUN composer --working-dir=/app install --optimize-autoloader --classmap-authoritative --no-interaction --prefer-dist --no-scripts

CMD ["php", "broadcast.php"]
