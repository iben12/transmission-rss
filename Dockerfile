FROM node:10-alpine AS js-build

WORKDIR /app

COPY package.json yarn.lock ./
COPY resources ./resources
COPY webpack.config.js .

RUN yarn install && npm run build


FROM composer AS php-build

WORKDIR /app

COPY ./composer.json ./composer.lock ./

RUN composer install --no-dev


FROM php:7.4-apache as php

RUN a2enmod rewrite headers

RUN docker-php-ext-install pdo_mysql

USER 1000

COPY php.ini /usr/local/etc/php/php.ini
COPY apache-ports.conf /etc/apache2/ports.conf
COPY apache-site-default.conf /etc/apache2/sites-available/000-default.conf

FROM php as prod

COPY --chown=1000:1000 --from=php-build /app/vendor /var/www/html/vendor
COPY --chown=1000:1000 ./src /var/www/html/src
COPY --chown=1000:1000 ./public /var/www/html/public
COPY --chown=1000:1000 --from=js-build /app/public/assets /var/www/html/public/assets
COPY ./config.php.example /var/www/html/config.php

