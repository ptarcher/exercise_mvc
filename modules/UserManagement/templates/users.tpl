{include file="templates/header.tpl"}
<head>
    <title>Bike &rsaquo; Users</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	{postEvent name="template_css_import"}
    <!-- TODO: Move this into the module code -->
	<link rel="stylesheet" type="text/css" href="themes/default/common.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="libraries/javascript/jquery/plugins/tablesorter/themes/blue/style.css" media="screen" />
	{postEvent name="template_js_import"}
    <!-- TODO: Move this into the module code -->
    <script type="text/javascript" src="themes/common.js"></script>
    <script type="text/javascript" src="libraries/javascript/sprintf.js"></script>
    <script type="text/javascript" src="libraries/javascript/jquery/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="libraries/javascript/jquery/plugins/tablesorter/jquery.tablesorter.min.js"></script>

    {literal}
    <script type="text/javascript">
    $(document).ready( function() {
        $('table#users').tablesorter({
                widgets: ['zebra',],
        });
    });

    </script>
    {/literal}
</head>

{include file="default/templates/menu.tpl"}

<center>
<h1>Users</h1>
</center>

<!-- draw the table -->
<table class="tablesorter" id="users" cellspacing="1">
    <thead>
    <tr>
        <th>UserID</th>
        <th>Coach</th>
        <th>Athlete</th>
        <th>Superuser</th>
    </tr>
    </thead>
    <tbody>
    {foreach from=$users key=i item=user}
    <tr>
        <td id="userid">{$user.userid}</td>
        <td id="coach">
            {if $user.coach}
                <img src="themes/default/images/ok.png">
            {else}
                <img src="themes/default/images/remove.png">
            {/if}
        </td>
        <td id="athlete">
            {if $user.athlete}
                <img src="themes/default/images/ok.png">
            {else}
                <img src="themes/default/images/remove.png">
            {/if}
        </td>
        <td id="superuser">
            {if $user.superuser}
                <img src="themes/default/images/ok.png">
            {else}
                <img src="themes/default/images/remove.png">
            {/if}
        </td>
    </tr>
    {foreachelse}
    <tr>
        <td colspan="4"><center>No users</center></td>
    </tr>
    {/foreach}
    </tbody>
</table>

<!-- end the table -->

{include file="templates/footer.tpl"}
