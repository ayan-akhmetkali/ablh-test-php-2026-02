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
        {include file='partials/post-meta.tpl' post=$post}
    {/if}
</article>
