FROM node:10-alpine AS js-build

WORKDIR /app

COPY package.json yarn.lock ./
COPY resources ./resources
COPY webpack.config.js .

RUN yarn install && npm run build


FROM composer AS php-build

WORKDIR /app

COPY ./composer.json ./composer.lock ./

RUN composer install


FROM webdevops/php-nginx:alpine AS final

ENV WEB_DOCUMENT_ROOT=/app/public

COPY --chown=application:application --from=php-build /app/vendor /app/vendor
COPY --chown=application:application ./src /app/src
COPY --chown=application:application ./public /app/public
COPY --chown=application:application ./trrss_db.sqlite /app/trrss_db.sqlite
COPY --chown=application:application --from=js-build /app/public/assets /app/public/assets
COPY ./config.php.example /app/config.php

