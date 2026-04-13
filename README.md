# ablh-test-php-2026-02

Реализованы этапы 0–10:
- Этап 0: базовое архитектурное планирование (структура слоев, маршруты, документ `ARCHITECTURE.md`).
- Этап 1: MySQL-схема с many-to-many между статьями и категориями.
- Этап 2: MVC-каркас (bootstrap `src/App.php`, маршруты в `routes/web.php`, тонкий `public/index.php`).
- Этап 3: обязательные страницы и функционал блога (главная, категория с сортировкой/пагинацией, статья + похожие; шаблоны вынесены в Smarty layout/partials).
- Этап 4: Docker + базовая стилизация (SCSS/CSS) с автоматической компиляцией Sass в контейнере.
- Этап 5: улучшение стабильности и UX (фильтрация только опубликованных постов, fallback похожих статей, канонизация страниц пагинации).
- Этап 6: контейнеризация на Nginx + PHP 8.4 FPM (entrypoint, healthchecks для app/nginx/db, php.ini настройки).
- Этап 7: SEO и кэширование (кэш sitemap.xml, canonical/OG мета, cache-control для robots/sitemap).
- Этап 8: базовые smoke-тесты и локальный quality gate (`tests/run.php`, `scripts/test.sh`).
- Этап 9: CI-пайплайн на GitHub Actions (матрица PHP 8.1/8.2/8.3 + запуск `./scripts/test.sh`).
- Этап 10: обработка аварий и hardening рантайма (request-id, security headers, логирование исключений, шаблон 500).

## Что добавлено на этапе 7
- `src/Cache/FileCache.php`
  - file-based cache с TTL;
  - подключен для главной и категории.
- `src/Controller/SeoController.php`
  - `GET /robots.txt`
  - `GET /sitemap.xml`
- SEO-мета в шаблонах:
  - `templates/home.tpl`
  - `templates/category.tpl`
  - `templates/post.tpl`


## SCSS: подключение и компиляция
- В шаблонах подключается **скомпилированный CSS**: `<link rel="stylesheet" href="/assets/css/styles.css">`.
- Файл `public/assets/scss/styles.scss` используется как исходник для правок стилей.
- Для запуска проекта компиляция **не обязательна** (актуальный `styles.css` уже в репозитории).
- Если вы меняете SCSS, пересоберите CSS:
  - локально: `./scripts/build-css.sh`
  - в Docker: `docker compose exec app ./scripts/build-css.sh`
- В `docker-compose.yml` для сервиса `app` вызов `./scripts/build-css.sh` добавлен в startup-команду (не блокирует запуск, если `sass` не установлен).

## Запуск через Docker
1. `docker compose up -d --build`
2. `docker compose exec app composer install`
3. `docker compose exec app php scripts/seed.php`
4. Открыть `http://localhost:8080`

При старте контейнера `app` SCSS автоматически компилируется в `public/assets/css/styles.css`.

### Примечания по Docker Compose
- Для входа в контейнер используйте: `docker compose exec app bash`
  (флаг `-it` в Compose v2 обычно не нужен и может вызывать ошибку `exec: "-it": executable file not found`).
- При старте сервиса `app` директории `var/cache` и `var/templates_c` создаются автоматически внутри `/var/www/html`,
  чтобы Smarty и файловый кэш работали корректно при bind-mount `./:/var/www/html`.

- Для корректных canonical/robots/sitemap ссылок задайте `APP_URL` (например `http://localhost:8080`).

## Проверки
- Запустить быстрые smoke-тесты и линт: `./scripts/test.sh`

## Запуск локально
1. `composer install`
2. `mysql -u root -p abelohost_blog < database/schema.sql`
3. `php scripts/seed.php`
4. `php -S localhost:8080 -t public`


## CI
- Автопроверки запускаются через `.github/workflows/ci.yml`.
- В CI выполняется `composer install` и `./scripts/test.sh` на PHP 8.1, 8.2 и 8.3.
