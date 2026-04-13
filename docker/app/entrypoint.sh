#!/usr/bin/env sh
set -eu

mkdir -p /var/www/html/var/cache /var/www/html/var/templates_c
chown -R www-data:www-data /var/www/html/var

./scripts/build-css.sh

exec php-fpm
