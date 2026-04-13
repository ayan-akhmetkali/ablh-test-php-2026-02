{extends file='layouts/base.tpl'}

{block name=content}
    <section class="card">
        <h1>500 Internal Server Error</h1>
        <p>Произошла внутренняя ошибка. Попробуйте обновить страницу позже.</p>
        {if $requestId|default:''}
            <p class="meta">Request ID: {$requestId|escape}</p>
        {/if}
        <a href="/">На главную</a>
    </section>
{/block}
