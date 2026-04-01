# ablh-test-php-2026-02

Реализованы этапы 1–7:
- Этап 1: MVC-каркас.
- Этап 2: MySQL схема, connection, репозитории, сидер.
- Этап 3: обязательные страницы и функционал блога.
- Этап 4: Docker + базовая стилизация (SCSS/CSS).
- Этап 5: улучшение стабильности и UX.
- Этап 6: контейнеризация на Nginx + PHP 8.4 FPM.
- Этап 7: SEO и базовое кэширование.

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

## Запуск через Docker
1. `docker compose up -d --build`
2. `docker compose exec app composer install`
3. `docker compose exec app php scripts/seed.php`
4. Открыть `http://localhost:8080`

## Запуск локально
1. `composer install`
2. `mysql -u root -p abelohost_blog < database/schema.sql`
3. `php scripts/seed.php`
4. `php -S localhost:8080 -t public`
