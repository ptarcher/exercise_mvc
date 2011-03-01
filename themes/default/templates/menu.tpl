<!-- Start Navigator -->
<ul class="nav">
{foreach from=$Core_NavigationMenu key=mainMenu item=subMenu name=menu}
    <li>
    {$mainMenu}
    {*<a name='{$subMenu.url}' href='index.php{$subMenu.url}'>{$mainMenu}</a>*}
    <ul>
    {foreach from=$subMenu key=name item=urlParams name=subMenu}
        {if strpos($name, '_') !== 0}
        <li><a href="{$urlParams._url|@urlRewriteBasicView}">{$name}</a></li>
        {/if}
    {/foreach}
    </ul>
    </li>
{/foreach}
</ul>
<!-- End Navigator -->
