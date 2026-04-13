# Architecture (Stage 0)

## Goal
Build a simple but production-like blog on pure PHP + MySQL + Smarty without frameworks, with clear separation of responsibilities.

## Routing
- `GET /` — home page
- `GET /category/{slug}` — category page (sorting + pagination)
- `GET /post/{slug}` — post page
- `GET /robots.txt` — robots
- `GET /sitemap.xml` — sitemap

## Layers
- **Controller** (`src/Controller`): receives request, validates input, orchestrates use-cases.
- **Repository** (`src/Model`): SQL access and data retrieval.
- **View** (`src/View` + `templates`): presentation via Smarty only.
- **Support/Core** (`src/Core`, `src/Support`, `src/Http`, `src/Cache`): routing, request helpers, pagination, cache.

## Responsibility boundaries
- Business/data rules live in PHP classes.
- Templates are for rendering only (loops/conditions/output), no database/business logic.
- SQL is isolated in repositories.

## Data model (Stage 1)
- `categories`
- `posts`
- `post_categories` (many-to-many between posts and categories)

This model supports: one post in multiple categories, category pages with sorting/pagination, and related posts by shared categories.
