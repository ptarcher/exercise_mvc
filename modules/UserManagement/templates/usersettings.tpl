{include file="templates/header.tpl"}
<head>
    <title>Bike &rsaquo; Settings</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	{postEvent name="template_css_import"}
    <!-- TODO: Move this into the module code -->
	<link rel="stylesheet" type="text/css" href="themes/default/common.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="libraries/javascript/jquery/plugins/tablesorter/themes/blue/style.css" media="screen" />
	{postEvent name="template_js_import"}
    <!-- TODO: Move this into the module code -->
    <script type="text/javascript" src="themes/common.js"></script>
    <script type="text/javascript" src="libraries/javascript/jquery/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="libraries/javascript/jquery/plugins/tablesorter/jquery.tablesorter.min.js"></script>
    <script type="text/javascript" src="libraries/javascript/jquery/plugins/tablesorter/jquery.metadata.js"></script>

    <script type="text/javascript" src="modules/UserManagement/templates/usersettings.js"></script>
</head>

<body>

{include file="default/templates/menu.tpl"}

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
        <tr>
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

{include file="templates/footer.tpl"}
