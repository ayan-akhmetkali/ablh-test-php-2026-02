{extends file='layouts/base.tpl'}

{block name=content}
    <section class="section-card">
        <div class="page-head">
            <h1>{$category.name|escape}</h1>
            <a href="/">На главную</a>
        </div>

        <p>{$category.description|escape}</p>

        {include file='partials/sort-controls.tpl' sortOptions=$sortOptions}

        <div class="grid">
            {foreach $posts as $post}
                {include file='partials/post-card.tpl' post=$post showImage=true showMeta=true}
            {foreachelse}
                <p>В этой категории статей пока нет.</p>
            {/foreach}
        </div>

        {include file='partials/pagination.tpl' totalPages=$totalPages currentPage=$currentPage currentSort=$currentSort}
    </section>
{/block}
