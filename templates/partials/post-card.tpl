<article class="post-card">
    {if $showImage|default:true and $post.image}
        <img class="post-image" src="{$post.image|escape}" alt="{$post.title|escape}">
    {/if}

    <h3 class="post-card__title">
        <a href="/post/{$post.slug|escape}">{$post.title|escape}</a>
    </h3>

    {if $post.description|default:''}
        <p class="post-card__description">{$post.description|escape}</p>
    {/if}

    {if $showMeta|default:true}
        <p class="meta">Просмотры: {$post.views|escape}{if $post.published_at|default:''}, дата: {$post.published_at|date_format:"%d.%m.%Y"}{/if}</p>
    {/if}
</article>
