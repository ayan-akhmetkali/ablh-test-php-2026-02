<div class="filters">
    Сортировка:
    {foreach $sortOptions as $option}
        {if $option.isActive}
            <strong>{$option.label|escape}</strong>
        {else}
            <a href="?sort={$option.value|escape}&page=1">{$option.label|escape}</a>
        {/if}
    {/foreach}
</div>
