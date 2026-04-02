<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>{$title|escape}</title>
    <meta name="description" content="{$post.description|default:'Статья блога'|escape}">
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body>
<header class="site-header">
    <div class="site-header__inner">
        <a class="logo" href="/">Blogy.</a>
    </div>
</header>

<main class="container">
    <article class="card">
        <div class="page-head">
            <h1>{$post.title|escape}</h1>
            <a href="/">На главную</a>
        </div>

        <p>{$post.description|escape}</p>

        {if $post.image}
            <img class="post-image" src="{$post.image|escape}" alt="{$post.title|escape}">
        {/if}

        <div class="post-content">{$post.content|escape}</div>

        <p class="meta">Просмотры: {$post.views|escape}</p>
        <p class="meta">Дата публикации: {$post.published_at|escape}</p>
    </article>

    <section class="card">
        <h2>Похожие статьи</h2>
        <div class="grid">
            {foreach $similarPosts as $item}
                <article class="post-card">
                    <h3 class="post-card__title">
                        <a href="/post/{$item.slug|escape}">{$item.title|escape}</a>
                    </h3>
                    <p class="post-card__description">{$item.description|default:''|escape}</p>
                </article>
            {foreachelse}
                <p>Похожие статьи не найдены.</p>
            {/foreach}
        </div>
    </section>
</main>

<footer class="site-footer">
    <div class="site-footer__inner">ayan@202.kz Аян Ахметқали</div>
</footer>
</body>
</html>
