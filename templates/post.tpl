{extends file='layouts/base.tpl'}

{block name=meta}{$post.description|default:'Статья блога'|escape}{/block}

{block name=content}
    <article class="card">
        <div class="page-head">
            <h1>{$post.title|escape}</h1>
            <a href="/">На главную</a>
        </div>

        <p>{$post.description|escape}</p>

        {if $post.image}
            <img class="post-image" src="{$post.image|escape}" alt="{$post.title|escape}">
        {/if}

        <div class="post-content">{$post.content|escape|nl2br}</div>

        {include file='partials/post-meta.tpl' post=$post}
    </article>

    <section class="card">
        <h2>Похожие статьи</h2>
        <div class="grid">
            {foreach $similarPosts as $post}
                {include file='partials/post-card.tpl' post=$post showImage=false showMeta=false}
            {foreachelse}
                <p>Похожие статьи не найдены.</p>
            {/foreach}
        </div>
    </section>
{/block}
