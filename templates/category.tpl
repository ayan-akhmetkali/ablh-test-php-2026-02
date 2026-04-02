<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{$title|escape}</title>
    <meta name="description" content="{$metaDescription|default:'Категория блога'|escape}">
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body>
<header class="site-header">
    <div class="site-header__inner">
        <a class="logo" href="/">Blogy.</a>
    </div>
</header>

<main class="container">
    <section class="section-card">
        <div class="page-head">
            <h1>{$category.name|escape}</h1>
            <a href="/">На главную</a>
        </div>

        <p>{$category.description|escape}</p>

        <div class="filters">
            Сортировка:
            <a href="?sort=date&page=1">По дате</a>
            <a href="?sort=views&page=1">По просмотрам</a>
        </div>

        <div class="grid">
            {foreach $posts as $post}
                <article class="post-card">
                    {if $post.image}
                        <img class="post-image" src="{$post.image|escape}" alt="{$post.title|escape}">
                    {/if}

                    <h2 class="post-card__title">
                        <a href="/post/{$post.slug|escape}">{$post.title|escape}</a>
                    </h2>
                    <p class="post-card__description">{$post.description|escape}</p>
                    <p class="meta">Просмотры: {$post.views|escape}, дата: {$post.published_at|date_format:"%d.%m.%Y"}</p>
                </article>
            {foreachelse}
                <p>В этой категории статей пока нет.</p>
            {/foreach}
        </div>

        {if $totalPages > 1}
            <div class="pagination">
                Страницы:
                {for $p=1 to $totalPages}
                    {if $p === $currentPage}
                        <strong>{$p}</strong>
                    {else}
                        <a href="?sort={$sort|escape}&page={$p}">{$p}</a>
                    {/if}
                {/for}
            </div>
        {/if}
    </section>
</main>

<footer class="site-footer">
    <div class="site-footer__inner">ayan@202.kz Аян Ахметқали</div>
</footer>
</body>
</html>
