<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{block name=title}{$title|default:'Blog'|escape}{/block}</title>
    <meta name="description" content="{block name=meta}{$metaDescription|default:'Блог'|escape}{/block}">
    <link rel="canonical" href="{$canonicalUrl|default:$appUrl|escape}">
    <meta property="og:type" content="{$ogType|default:'website'|escape}">
    <meta property="og:title" content="{$title|default:'Blog'|escape}">
    <meta property="og:description" content="{$metaDescription|default:'Блог'|escape}">
    <meta property="og:url" content="{$canonicalUrl|default:$appUrl|escape}">
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body>
<header class="site-header">
    <div class="site-header__inner">
        <a class="logo" href="/">Blogy.</a>
    </div>
</header>

<main class="container">
    {block name=content}{/block}
</main>

<footer class="site-footer">
    <div class="site-footer__inner">ayan@202.kz Аян Ахметқали</div>
</footer>
</body>
</html>
