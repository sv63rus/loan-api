FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libsqlite3-dev \
  && docker-php-ext-install pdo pdo_sqlite

WORKDIR /var/www/html

COPY . .

COPY composer.json composer.lock ./
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer install --no-dev --no-scripts --optimize-autoloader

ENV APP_ENV=prod \
    APP_DEBUG=0

RUN php bin/console cache:clear --no-warmup --env=prod \
 && php bin/console cache:warmup --env=prod

RUN chown -R www-data:www-data var

EXPOSE 9000

CMD ["php-fpm"]