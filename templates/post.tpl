<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>{$title|escape}</title>
    <meta name="description" content="{$post.description|default:'Статья блога'|escape}">
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body>
<div class="container">
<article class="card">
    <h1>{$post.title|escape}</h1>
    <p>{$post.description|escape}</p>

    {if $post.image}
        <img src="{$post.image|escape}" alt="{$post.title|escape}" style="max-width: 400px;">
    {/if}

    <div>
        {$post.content|escape}
    </div>

    <p class="meta">Просмотры: {$post.views|escape}</p>
    <p class="meta">Дата публикации: {$post.published_at|escape}</p>
</article>

<section class="card">
    <h2>Похожие статьи</h2>
    <ul class="list">
        {foreach $similarPosts as $item}
            <li>
                <a href="/post/{$item.slug|escape}">{$item.title|escape}</a>
            </li>
        {foreachelse}
            <li>Похожие статьи не найдены.</li>
        {/foreach}
    </ul>
</section>

<nav class="nav">
    <a href="/">На главную</a>
</nav>
</div>
</body>
</html>
