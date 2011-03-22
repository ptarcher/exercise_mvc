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

<!-- The new menu -->
<div id="main-nav">
<ul>
    <li id="dashboard"><a href="#">DashBoard</a>
        <!--ul>
            <li>View<li>
        </ul-->
    </li>
    <li><a href="#">Sessions</a>
        <!--ul>
            <li>View<li>
        </ul-->
    </li>

</ul>


</div> <!-- main-nav -->
<!-- End Navigator -->
