<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>{$title|escape}</title>
    <meta name="description" content="{$metaDescription|default:'Блог'|escape}">
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body>
<header class="site-header">
    <div class="site-header__inner">
        <a class="logo" href="/">Blogy.</a>
    </div>
</header>

<main class="container">
    <h1 class="page-title">{$title|escape}</h1>

    {if $categories|@count === 0}
        <section class="section-card">
            <p>Категорий со статьями пока нет.</p>
        </section>
    {/if}

    {foreach $categories as $category}
        <section class="section-card">
            <div class="section-head">
                <h2>{$category.name|escape}</h2>
                <a href="/category/{$category.slug|escape}">Все статьи</a>
            </div>

            <p class="section-description">{$category.description|escape}</p>
            <p class="meta">Постов: {$category.posts_count|escape}</p>

            <div class="grid">
                {foreach $category.latest_posts as $post}
                    <article class="post-card">
                        {if $post.image}
                            <img class="post-image" src="{$post.image|escape}" alt="{$post.title|escape}">
                        {/if}

                        <h3 class="post-card__title">
                            <a href="/post/{$post.slug|escape}">{$post.title|escape}</a>
                        </h3>
                        <p class="post-card__description">{$post.description|escape}</p>
                        <p class="meta">Просмотры: {$post.views|escape}, дата: {$post.published_at|escape}</p>
                    </article>
                {/foreach}
            </div>
        </section>
    {/foreach}
</main>

<footer class="site-footer">
    <div class="site-footer__inner">ayan@202.kz Аян Ахметқали</div>
</footer>
</body>
</html>
