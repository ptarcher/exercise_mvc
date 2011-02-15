{extends file="templates/layout.tpl"}

{block name=title}Settings{/block}

{block name=css}
	<link rel="stylesheet" type="text/css" href="Module/UserManagement/templates/usersettings.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="themes/default/common.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="libraries/javascript/jquery/plugins/tablesorter/themes/blue/style.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="libraries/javascript/jquery/plugins/jqueryui/themes/base/jquery-ui.css" media="screen" />
{/block}

{block name=javascript}
    <script type="text/javascript" src="themes/common.js"></script>
    <script type="text/javascript" src="libraries/javascript/jquery/jquery-1.4.2.js"></script>
    <script type="text/javascript" src="libraries/javascript/jquery/plugins/jqueryui/jquery-ui-1.8.1.min.js"></script>
    <script type="text/javascript" src="libraries/javascript/jquery/plugins/tablesorter/jquery.tablesorter.min.js"></script>
    <script type="text/javascript" src="libraries/javascript/jquery/plugins/tablesorter/jquery.metadata.js"></script>
    <script type="text/javascript" src="Module/UserManagement/templates/usersettings.js"></script>
{/block}

{block name=body}
<center>
<h1>Settings</h1>
</center>

<!-- draw the table -->
<table class="tablesorter" id="settings" cellspacing="1">
    <thead>
        <tr>
            <th>Setting</th>
            <th>Value</th>
        </tr>
    </thead>
    <tbody>
        {foreach from=$settings key=i item=setting}
        <tr id="row{$i}">
            <td>{$setting.name}:</td>

            {if $setting.editable}
                <td id="{$setting.id}" class="editable">{$setting.value}</td>
            {else}
                <td id="{$setting.id}">{$setting.value}</td>
            {/if}
        </tr>
        {foreachelse}
        <tr>
            <td colspan="2"><center>No Settings Found</center></td>
        </tr>
        {/foreach}
    </tbody>
</table>

<!-- end the table -->
{/block}
