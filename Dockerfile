FROM php:8.4-fpm

RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip libzip-dev \
    && docker-php-ext-install pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . /var/www/html

RUN mkdir -p /var/www/html/var/templates_c /var/www/html/var/cache \
    && chown -R www-data:www-data /var/www/html/var

CMD ["php-fpm"]
