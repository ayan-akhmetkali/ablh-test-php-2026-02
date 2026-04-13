{extends file='layouts/base.tpl'}

{block name=content}
    <section class="card">
        <h1>{$title|escape}</h1>
        <p>{$message|escape}</p>
        <a href="/">На главную</a>
    </section>
{/block}
