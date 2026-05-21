<section class="section-card">
    <div class="section-head">
        <h2>{$category.name|escape}</h2>
        <a href="/category/{$category.slug|escape}">Все статьи</a>
    </div>

    <p class="section-description">{$category.description|escape}</p>
    <p class="meta">Постов: {$category.posts_count|escape}</p>

    <div class="grid">
        {foreach $category.latest_posts as $post}
            {include file='partials/post-card.tpl' post=$post showImage=true showMeta=true}
        {/foreach}
    </div>
</section>
