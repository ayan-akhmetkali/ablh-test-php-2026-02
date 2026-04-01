<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>{$title|escape}</title>
    <meta name="description" content="{$metaDescription|default:'Категория блога'|escape}">
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body>
<div class="container">
<h1>{$category.name|escape}</h1>
<p>{$category.description|escape}</p>

<nav class="nav">
    <a href="/">На главную</a>
</nav>

<p>
    Сортировка:
    <a href="?sort=date&page=1">По дате</a> |
    <a href="?sort=views&page=1">По просмотрам</a>
</p>

<ul class="list">
    {foreach $posts as $post}
        <li class="card">
            <a href="/post/{$post.slug|escape}">{$post.title|escape}</a>
            <p>{$post.description|escape}</p>
            <small class="meta">Просмотры: {$post.views|escape}, дата: {$post.published_at|escape}</small>
        </li>
    {foreachelse}
        <li>В этой категории статей пока нет.</li>
    {/foreach}
</ul>

{if $totalPages > 1}
    <p>
        Страницы:
        {for $p=1 to $totalPages}
            {if $p === $currentPage}
                <strong>{$p}</strong>
            {else}
                <a href="?sort={$sort|escape}&page={$p}">{$p}</a>
            {/if}
        {/for}
    </p>
{/if}
</div>
</body>
</html>
