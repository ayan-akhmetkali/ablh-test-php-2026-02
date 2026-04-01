<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>{$title|escape}</title>
    <meta name="description" content="{$metaDescription|default:'Блог'|escape}">
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body>
<div class="container">
<h1>{$title|escape}</h1>

{if $categories|@count === 0}
    <p>Категорий со статьями пока нет.</p>
{/if}

{foreach $categories as $category}
    <section class="card">
        <h2>{$category.name|escape}</h2>
        <p>{$category.description|escape}</p>
        <p class="meta">Постов: {$category.posts_count|escape}</p>

        <p><a href="/category/{$category.slug|escape}">Все статьи</a></p>

        <ul class="list">
            {foreach $category.latest_posts as $post}
                <li>
                    <a href="/post/{$post.slug|escape}">{$post.title|escape}</a>
                    <p>{$post.description|escape}</p>
                    <small class="meta">Просмотры: {$post.views|escape}, дата: {$post.published_at|escape}</small>
                </li>
            {/foreach}
        </ul>
    </section>
{/foreach}
</div>
</body>
</html>
