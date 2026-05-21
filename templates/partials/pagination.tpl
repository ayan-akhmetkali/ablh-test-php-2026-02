{if $totalPages > 1}
    <div class="pagination">
        Страницы:
        {for $p=1 to $totalPages}
            {if $p === $currentPage}
                <strong>{$p}</strong>
            {else}
                <a href="?sort={$currentSort|escape}&page={$p}">{$p}</a>
            {/if}
        {/for}
    </div>
{/if}
