<!-- Start Navigator -->
<ul class="nav">
{foreach from=$CoreNavigationMenu key=mainMenu item=subMenu name=menu}
    <li>
    {$mainMenu}
    {*<a name='{$subMenu.url}' href='index.php{$subMenu.url}'>{$mainMenu}</a>*}
    <ul>
    {foreach from=$subMenu key=name item=link name=subMenu}
        <li><a href="{$link.url}">{$link.name}</a></li>
    {/foreach}
    </ul>
    </li>
{/foreach}
</ul>
<!-- End Navigator -->
