<!-- Start Navigator -->
<div id="main-nav">
<ul>
{foreach from=$Core_NavigationMenu key=mainMenu item=subMenu name=menu}
    {if $currentModule neq $mainMenu} 
        {assign var=lvl1 value="level1"}
        {assign var=lvl2 value="level2 hidden"}
    {else}
        {assign var=lvl1 value="level1 selected"}
        {assign var=lvl2 value="level2"}
    {/if}
    <li id="{$mainMenu}" class="{$lvl1}">
        <a href="?module={$mainMenu}">{$mainMenu}</a>
        <div class="{$lvl2}">
            <ul>
            {foreach from=$subMenu key=name item=urlParams name=subMenu}
                {if strpos($name, '_') !== 0}
                    <li><a href="{$urlParams._url|@urlRewriteBasicView}">{$name}</a></li>
                {/if}
            {/foreach}
            </ul>
        </div> <!-- level2 -->
    </li>
{/foreach}
</ul>
</div> <!-- main-nav -->
<!-- End Navigator -->
