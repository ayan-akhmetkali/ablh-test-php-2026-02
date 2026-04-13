{extends file='layouts/base.tpl'}

{block name=content}
    <section class="section-card">
        <div class="page-head">
            <h1>{$category.name|escape}</h1>
            <a href="/">На главную</a>
        </div>

        <p>{$category.description|escape}</p>

        <div class="filters">
            Сортировка:
            {if $sort === 'date'}
                <strong>По дате</strong>
            {else}
                <a href="?sort=date&page=1">По дате</a>
            {/if}

            {if $sort === 'views'}
                <strong>По просмотрам</strong>
            {else}
                <a href="?sort=views&page=1">По просмотрам</a>
            {/if}
        </div>

        <div class="grid">
            {foreach $posts as $post}
                {include file='partials/post-card.tpl' post=$post showImage=true showMeta=true}
            {foreachelse}
                <p>В этой категории статей пока нет.</p>
            {/foreach}
        </div>

        {if $totalPages > 1}
            <div class="pagination">
                Страницы:
                {for $p=1 to $totalPages}
                    {if $p === $currentPage}
                        <strong>{$p}</strong>
                    {else}
                        <a href="?sort={$sort|escape}&page={$p}">{$p}</a>
                    {/if}
                {/for}
            </div>
        {/if}
    </section>
{/block}
