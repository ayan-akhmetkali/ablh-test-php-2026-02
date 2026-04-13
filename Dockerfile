FROM php:8.4-fpm

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        libzip-dev \
        nodejs \
        npm \
    && docker-php-ext-install pdo pdo_mysql \
    && npm install -g sass \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . /var/www/html

COPY docker/php/conf.d/app.ini /usr/local/etc/php/conf.d/zz-app.ini
COPY docker/app/entrypoint.sh /usr/local/bin/app-entrypoint

RUN chmod +x /usr/local/bin/app-entrypoint \
    && mkdir -p /var/www/html/var/templates_c /var/www/html/var/cache \
    && chown -R www-data:www-data /var/www/html/var

CMD ["app-entrypoint"]
