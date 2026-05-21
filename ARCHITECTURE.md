# Architecture (Stages 1–4)

## Goal
Build a simple but production-like blog on pure PHP + MySQL + Smarty without frameworks, with explicit application use-cases and thin templates.

## Routing
- `GET /` — home page
- `GET /category/{slug}` — category page (sorting + pagination)
- `GET /post/{slug}` — post page
- `GET /robots.txt` — robots
- `GET /sitemap.xml` — sitemap

## DDD-oriented layers
- **Application (`src/Application`)**
  - `GetHomePageData`
  - `GetCategoryPageData`
  - `GetPostPageData`
  
  These classes orchestrate scenarios and prepare page payloads.
- **Domain/Model (`src/Model`)**
  - Repositories encapsulate SQL access for posts/categories.
  - Domain rules are kept in PHP flow (not in templates):
    - publish constraints (`published_at <= NOW()`),
    - category/article relation,
    - related posts exclude current post,
    - view count increment on article view.
- **Presentation (`src/View` + `templates`)**
  - Smarty is used as view-only layer:
    - template inheritance (`layouts/base.tpl`),
    - partials (`partials/*.tpl`),
    - loops/conditions for rendering only.

## Smarty contract (Stage 1 focus)
Templates receive ready-to-render data and must not implement business rules.

- `home.tpl` receives `categories[]` where each category already has `latest_posts[]`.
- `category.tpl` receives:
  - `posts[]`
  - `sortOptions[]` with `label/value/isActive`
  - `currentPage`, `totalPages`, `currentSort`
- `post.tpl` receives `post` and `similarPosts[]`.

## Responsibility boundaries
- Controllers are thin HTTP adapters.
- Application use-cases assemble page ViewModels.
- Repositories own SQL.
- Smarty owns markup composition only.


## Stage 3–4 refinements
- Separated post read and write concerns at application level:
  - `GetPostPageData` handles read-model assembly only.
  - `IncrementPostViews` performs view-counter mutation.
- Normalized category sort option construction via a dedicated shared factory (`SortOptionFactory`) to keep consistent view-model contracts.
- Increased Smarty reuse by extracting `post-meta.tpl` partial shared between article card and article page.


## Alignment with lead feedback
- Removed presentation-heavy section wrappers to make templates and styling closer to the provided flat editorial layout reference.
- Kept Smarty responsibilities limited to composition (`extends`, `include`, loops, escaping, formatting), without data-selection logic in templates.
- Clarified that current implementation follows **DDD-lite** boundaries for a test-task scope: thin controllers, application use-cases, repository-backed data access.
