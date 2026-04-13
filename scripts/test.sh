#!/usr/bin/env sh
set -eu

php -l src/Http/Request.php
php -l src/Support/Paginator.php
php -l src/Cache/FileCache.php
php tests/run.php
