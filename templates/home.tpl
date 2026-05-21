{extends file='layouts/base.tpl'}

{block name=content}
    <h1 class="page-title">{$title|escape}</h1>

    {if $categories|@count === 0}
        <section class="section-card">
            <p>Категорий со статьями пока нет.</p>
        </section>
    {/if}

    {foreach $categories as $category}
        {include file='partials/category-section.tpl' category=$category}
    {/foreach}
{/block}
